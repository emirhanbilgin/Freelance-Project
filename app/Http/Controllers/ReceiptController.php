<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    // Dashboard'dan gelen müşteri adıyla fiş başlat
    public function start(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
        ]);

        $customer = Customer::firstOrCreate([
            'name' => $request->customer_name,
        ]);

        $receipt = Receipt::create([
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
            'description' => '',
        ]);

        return redirect()->route('receipts.edit', $receipt->id);
    }

    // Fiş oluşturma formu (şu anda kullanılmıyor ama hazır dursun)
    public function create()
    {
        $products = Product::all();
        return view('receipts.create', compact('products'));
    }

    // Çoklu ürün desteği ile fişi oluştur ve ürünleri kaydet
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:0.1',
            'note' => 'nullable|array',
            'note.*' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'nullable|in:cash,credit_card,',
        ]);

        $customer = Customer::firstOrCreate([
            'name' => $request->customer_name,
        ]);

        // Ödeme biçimini belirle
        $paymentMethod = null;
        if ($request->has('payment_methods') && !empty($request->payment_methods)) {
            // Eğer her ikisi de seçilmişse, ilkini al (nakit öncelikli)
            $paymentMethod = $request->payment_methods[0];
        }

        $receipt = Receipt::create([
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
            'description' => isset($request->description) ? $request->description : '',
            'payment_method' => $paymentMethod,
        ]);

        // Dizileri normalize et (boşları ayıkla ve indexleri hizala)
        $productIds = array_values(array_filter($request->product_id ?? []));
        $prices     = array_values($request->price ?? []);
        $quantities = array_values($request->quantity ?? []);
        $notes      = array_values($request->note ?? []);

        foreach ($productIds as $i => $productId) {
            // price/quantity yoksa veya sayısal değilse bu satırı atla
            if (!isset($prices[$i], $quantities[$i])) {
                continue;
            }
            if (!is_numeric($prices[$i]) || !is_numeric($quantities[$i])) {
                continue;
            }

            ReceiptItem::create([
                'receipt_id' => $receipt->id,
                'product_id' => $productId,
                'quantity'   => (float) $quantities[$i],
                'price'      => (float) $prices[$i],
                'note'       => $notes[$i] ?? null,
            ]);
        }

        // Artık total_amount accessor'ı otomatik komisyon hesaplıyor

        return redirect()->route('dashboard')->with('success', 'Fiş başarıyla oluşturuldu.');
    }

    // Fiş düzenleme ekranı
    public function edit($id)
    {
        $receipt = Receipt::with(['customer', 'items.product'])->findOrFail($id);
        $products = Product::all();

        return view('receipts.edit', compact('receipt', 'products'));
    }

    // Tekil fişi göster
    public function show($id)
    {
        try {
            // Eager loading ile ilişkileri yükle ve hata kontrolü ekle
            $receipt = Receipt::with(['customer', 'items.product'])
                ->findOrFail($id);
            
            // Receipt items'ların product ilişkilerini kontrol et
            foreach ($receipt->items as $item) {
                if (!$item->product) {
                    \Log::warning("ReceiptItem {$item->id} has no product relationship for receipt {$id}");
                }
            }
            
            $products = Product::all();

            return view('receipts.show', compact('receipt', 'products'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error("Receipt not found: {$id}");
            abort(404, 'Fiş bulunamadı.');
        } catch (\Exception $e) {
            \Log::error("Error showing receipt {$id}: " . $e->getMessage());
            abort(500, 'Fiş detayları yüklenirken hata oluştu.');
        }
    }

    // ✅ Fişi PDF'e dönüştür ve indir
    public function generatePDF($id)
    {
        $receipt = Receipt::with(['customer', 'items.product'])->findOrFail($id);

        $pdf = Pdf::loadView('receipts.pdf', compact('receipt'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("fis_{$receipt->id}.pdf");
    }



    // Dashboard (fiş ve ürünleri listele)
    public function index()
    {
        // İstanbul gününe göre: gün başlangıcını IST'de hesapla, UTC'ye çevir ve "şu an"a kadar getir
        $istanbulNow = now('Europe/Istanbul');

        // Veresiye fişler dahil: daily_reset=false olan tüm fişleri göster
        $receipts = Receipt::with('customer')
            ->where('daily_reset', false)
            ->latest()
            ->get();
            
        $products = Product::all();

        return view('dashboard', [
            'receipts' => $receipts,
            'products' => $products,
        ]);
    }

    public function updateBatch(Request $request, $receiptId)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:receipt_items,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'payment_method' => 'nullable|in:cash,credit_card,',
        ]);

        // Ödeme yöntemini güncelle
        $receipt = Receipt::findOrFail($receiptId);
        $oldPaymentMethod = $receipt->payment_method;
        $newPaymentMethod = $request->payment_method ?: null;

        // Eğer ödeme yöntemi nakit/kart olarak değişiyorsa ve archived_at boşsa, archived_at'ı şimdiye set et
        if (($oldPaymentMethod === null || $oldPaymentMethod === '') && in_array($newPaymentMethod, ['cash', 'credit_card']) && !$receipt->archived_at) {
            $receipt->update([
                'payment_method' => $newPaymentMethod,
                'archived_at' => now(),
            ]);
        } else {
            $receipt->update([
                'payment_method' => $newPaymentMethod,
            ]);
        }

        foreach ($request->items as $item) {
            // Eğer silme işareti varsa ürünü sil
            if (isset($item['_delete']) && $item['_delete'] == 1) {
                ReceiptItem::where('id', $item['id'])->delete();
            } else {
                // Yoksa güncelle
                ReceiptItem::where('id', $item['id'])->update([
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'] ?? null,
                ]);
            }
        }

        // Artık total_amount accessor'ı otomatik komisyon hesaplıyor

        // Eğer ödeme yöntemi değişti ve artık ödenmişse (nakit veya kart)
        if (($oldPaymentMethod === null || $oldPaymentMethod === '') && 
            in_array($request->payment_method, ['cash', 'credit_card'])) {
            
            // Fişin oluşturulduğu günün arşiv sayfasına yönlendir
            $receiptDate = $receipt->created_at->format('Y-m-d');
            return redirect()->route('receipts.archived.by-date', $receiptDate)
                           ->with('success', 'Fiş ödendi ve ilgili günün arşivine eklendi.');
        }

        return redirect()->route('dashboard')->with('success', 'Fiş güncellendi.');
    }

    // Günlük satış raporu API endpoint'i
    public function dailySalesReport()
    {
        $today = now()->setTimezone('Europe/Istanbul')->startOfDay();
        $todayEnd = $today->copy()->endOfDay();
        
        // ReceiptItem'ları product ilişkisi ile birlikte yükle
        $receiptItems = ReceiptItem::with(['product', 'receipt'])
            ->whereHas('receipt', function($query) use ($today, $todayEnd) {
                $query->whereDate('created_at', $today);
            })
            ->get();
        
        // Ürün bazında grupla
        $grouped = $receiptItems->groupBy(function($item) {
            // Eğer product yoksa veya product_id null ise, "Ürün Bulunamadı" olarak grupla
            return $item->product ? $item->product->id : 'missing_' . $item->product_id;
        });
        
        $dailySales = $grouped->map(function($items, $productId) {
            $firstItem = $items->first();
            $product = $firstItem->product;
            $productName = $product ? $product->name : 'Ürün Bulunamadı';
            
            $totalQuantity = $items->sum('quantity');
            $totalAmount = $items->sum(function($item) {
                return $item->quantity * $item->price;
            });
            $receiptCount = $items->pluck('receipt_id')->unique()->count();
            
            // Ödeme yöntemine göre grupla
            $cashItems = $items->filter(function($item) {
                return $item->receipt && $item->receipt->payment_method === 'cash';
            });
            $cardItems = $items->filter(function($item) {
                return $item->receipt && $item->receipt->payment_method === 'credit_card';
            });
            $creditItems = $items->filter(function($item) {
                return !$item->receipt || (!$item->receipt->payment_method || $item->receipt->payment_method === '');
            });
            
            return [
                'product_name' => $productName,
                'product_id' => $product ? $product->id : null,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'receipt_count' => $receiptCount,
                'cash_quantity' => $cashItems->sum('quantity'),
                'cash_amount' => $cashItems->sum(function($item) {
                    return $item->quantity * $item->price;
                }),
                'credit_card_quantity' => $cardItems->sum('quantity'),
                'credit_card_amount' => $cardItems->sum(function($item) {
                    return $item->quantity * $item->price;
                }),
                'credit_quantity' => $creditItems->sum('quantity'),
                'credit_amount' => $creditItems->sum(function($item) {
                    return $item->quantity * $item->price;
                }),
            ];
        })->values()->sortByDesc('total_amount');

        // Genel toplamlar
        $totals = ReceiptItem::join('receipts', 'receipt_items.receipt_id', '=', 'receipts.id')
            ->whereDate('receipts.created_at', $today)
            ->select(
                \DB::raw('SUM(receipt_items.quantity) as total_quantity'),
                \DB::raw('SUM(receipt_items.quantity * receipt_items.price) as total_amount'),
                \DB::raw('SUM(CASE WHEN receipts.payment_method = "cash" THEN receipt_items.quantity ELSE 0 END) as cash_quantity'),
                \DB::raw('SUM(CASE WHEN receipts.payment_method = "cash" THEN receipt_items.quantity * receipt_items.price ELSE 0 END) as cash_amount'),
                \DB::raw('SUM(CASE WHEN receipts.payment_method = "credit_card" THEN receipt_items.quantity ELSE 0 END) as credit_card_quantity'),
                \DB::raw('SUM(CASE WHEN receipts.payment_method = "credit_card" THEN receipt_items.quantity * receipt_items.price ELSE 0 END) as credit_card_amount'),
                \DB::raw('SUM(CASE WHEN receipts.payment_method IS NULL OR receipts.payment_method = "" THEN receipt_items.quantity ELSE 0 END) as credit_quantity'),
                \DB::raw('SUM(CASE WHEN receipts.payment_method IS NULL OR receipts.payment_method = "" THEN receipt_items.quantity * receipt_items.price ELSE 0 END) as credit_amount')
            )
            ->first();

        return response()->json([
            'products' => $dailySales,
            'totals' => $totals
        ]);
    }

    // Arşivlenmiş fişleri listele
    public function archived()
    {
        // 1. Günlük reset ile arşivlenmiş fişler
        $dailyResetReceipts = Receipt::with(['customer', 'items.product'])
            ->where('daily_reset', true)
            ->whereNotNull('archived_at')
            ->get()
            ->groupBy(function($receipt) {
                return $receipt->archived_at->format('Y-m-d');
            });

        // 2. Ödenmiş veresiye fişler (oluşturuldukları güne göre)
        $paidCreditReceipts = Receipt::with(['customer', 'items.product'])
            ->whereIn('payment_method', ['cash', 'credit_card'])
            ->whereNotNull('archived_at')
            ->get()
            ->groupBy(function($receipt) {
                return $receipt->created_at->format('Y-m-d');
            });

        // 3. İki listeyi birleştir
        $allArchivedReceipts = $dailyResetReceipts->toArray();
        
        foreach ($paidCreditReceipts as $date => $receipts) {
            if (isset($allArchivedReceipts[$date])) {
                // Eğer o gün zaten daily_reset fişleri varsa, ödenmiş veresiye fişleri de ekle
                $allArchivedReceipts[$date] = array_merge($allArchivedReceipts[$date], $receipts->toArray());
            } else {
                // Eğer o gün sadece ödenmiş veresiye fişleri varsa, yeni gün olarak ekle
                $allArchivedReceipts[$date] = $receipts->toArray();
            }
        }

        // Günleri tarih sırasına göre sırala (en yeni en üstte)
        $allArchivedReceipts = collect($allArchivedReceipts)->sortKeysDesc();

        return view('receipts.archived', compact('allArchivedReceipts'));
    }

    // Belirli bir günün arşivlenmiş fişlerini listele
    public function archivedByDate($date)
    {
        $selectedDate = \Carbon\Carbon::parse($date);
        
        $receipts = Receipt::with(['customer', 'items.product'])
            ->where(function($query) use ($selectedDate) {
                // Arşivlenmiş fişler (daily_reset = true)
                $query->where('daily_reset', true)
                      ->whereNotNull('archived_at')
                      ->whereDate('archived_at', $selectedDate);
            })
            ->orWhere(function($query) use ($selectedDate) {
                // O gün oluşturulmuş ve ödenmiş fişler (nakit veya kart)
                $query->whereDate('created_at', $selectedDate)
                      ->whereIn('payment_method', ['cash', 'credit_card'])
                      ->whereNotNull('archived_at');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('receipts.archived-by-date', compact('receipts', 'selectedDate'));
    }

    // Veresiye fişlerini listele
    public function creditReceipts()
    {
        $creditReceipts = Receipt::with(['customer', 'items.product'])
            ->where(function($query) {
                $query->whereNull('payment_method')
                      ->orWhere('payment_method', '');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('receipts.credit', compact('creditReceipts'));
    }

    // Toplu silme fonksiyonu
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'receipt_ids' => 'required|array',
            'receipt_ids.*' => 'integer|exists:receipts,id'
        ]);

        try {
            $receiptIds = $request->receipt_ids;
            
            // Arşivlenmemiş tüm fişleri sil (tarihten bağımsız)
            $receipts = Receipt::whereIn('id', $receiptIds)
                ->where('daily_reset', false)
                ->get();

            foreach ($receipts as $receipt) {
                // Önce receipt items'ları sil
                $receipt->items()->delete();
                // Sonra receipt'i sil
                $receipt->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Seçili fişler başarıyla silindi.',
                'deleted_count' => $receipts->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fişler silinirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Veresiye fişin ödeme yöntemini güncelle (nakit / kredi kartı) ve arşive taşı
    public function updatePaymentMethod(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,credit_card',
        ]);

        $receipt = Receipt::findOrFail($id);
        $oldPaymentMethod = $receipt->payment_method;

        // Ödeme yöntemi güncelleniyor
        $receipt->payment_method = $request->payment_method;

        // İlk kez ödeniyorsa arşiv zamanını işaretle
        if (($oldPaymentMethod === null || $oldPaymentMethod === '') && !$receipt->archived_at) {
            $receipt->archived_at = now();
        }

        $receipt->save();

        // Oluşturulduğu günün arşivine dön
        $receiptDate = $receipt->created_at->format('Y-m-d');
        return redirect()->route('receipts.archived.by-date', $receiptDate)
            ->with('success', 'Ödeme yöntemi güncellendi ve fiş arşive taşındı.');
    }

}
