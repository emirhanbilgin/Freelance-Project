<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Fiş Düzenle: {{ $receipt->customer->name }}
            </h2>

            <a href="{{ route('receipts.pdf', $receipt->id) }}"
               class="text-sm font-semibold px-4 py-2 rounded shadow transition duration-200"
               style="background-color: #0572b5 !important; color: white !important;">
                📄 PDF Olarak İndir
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 space-y-8">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Ürün Ekle Butonu --}}
        <div>
            <button onclick="document.getElementById('new-product-form').classList.toggle('hidden')"
                    class="font-semibold px-4 py-2 rounded shadow transition"
                    style="background-color: #059669 !important; color: white !important;">
                ➕ Ürün Ekle
            </button>
        </div>

        {{-- Yeni Ürün Ekleme Formu --}}
        <div id="new-product-form" class="hidden mt-4 border rounded-lg p-4 bg-white shadow">
            <form method="POST" action="{{ route('receipt_items.store', $receipt->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ürün</label>
                        <select name="product_id" class="w-full border rounded px-3 py-2" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Miktar</label>
                        <input type="number" name="quantity" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fiyat (₺)</label>
                        <input type="number" name="price" step="0.01" class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="submit"
                            class="font-semibold px-4 py-2 rounded shadow transition"
                            style="background-color: #F59E0B !important; color: white !important;">
                        💾 Kaydet
                    </button>
                </div>
            </form>
        </div>

        {{-- Fişteki Ürünleri Güncelleme Tablosu --}}
        <form method="POST" action="{{ route('receipt_items.update_batch', ['receipt' => $receipt->id]) }}">
            @csrf
            @method('PUT')

            {{-- Ödeme Yöntemi Seçimi --}}
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">💳 Ödeme Yöntemi</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="cash" 
                               {{ $receipt->payment_method === 'cash' ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">💵 Nakit (Komisyon Yok)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="credit_card" 
                               {{ $receipt->payment_method === 'credit_card' ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">💳 Kredi Kartı (+%1 Komisyon)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="" 
                               {{ !in_array($receipt->payment_method, ['cash', 'credit_card']) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">📝 Veresiye (Ödeme Sonra)</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">Kredi kartı seçildiğinde toplam tutara %1 komisyon eklenir</p>
            </div>

            <div class="overflow-x-auto bg-white shadow rounded-lg mt-6">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 border w-1/4">Ürün</th>
                        <th class="px-2 py-2 border w-20">Miktar</th>
                        <th class="px-2 py-2 border w-24">Fiyat</th>
                        <th class="px-2 py-2 border w-24">Ara Toplam</th>
                        <th class="px-3 py-2 border w-32">Açıklama</th>
                        <th class="px-2 py-2 border w-12">Sil</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($receipt->items as $item)
                        @php $subtotal = $item->quantity * $item->price; @endphp
                        <tr>
                            <td class="border px-3 py-2 text-sm">{{ $item->product->name }}</td>
                            <td class="border px-2 py-2">
                                <input type="number" name="items[{{ $loop->index }}][quantity]"
                                       value="{{ $item->quantity }}" step="0.1" min="0.1"
                                       class="w-16 px-1 py-1 border rounded text-sm" required>
                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            </td>
                            <td class="border px-2 py-2">
                                <input type="number" name="items[{{ $loop->index }}][price]"
                                       value="{{ $item->price }}" step="0.01" min="0"
                                       class="w-20 px-1 py-1 border rounded text-sm" required>
                            </td>
                            <td class="border px-2 py-2 text-center font-semibold text-primary-600 text-sm">
                                {{ number_format($subtotal, 2) }} ₺
                            </td>
                            <td class="border px-3 py-2">
                                <input type="text" name="items[{{ $loop->index }}][note]"
                                       value="{{ $item->note ?? '' }}"
                                       class="w-28 px-1 py-1 border rounded text-sm" placeholder="Not...">
                            </td>
                            <td class="border px-2 py-2 text-center">
                                <input type="checkbox" name="items[{{ $loop->index }}][_delete]" value="1"
                                       class="h-4 w-4 text-red-600">
                            </td>
                        </tr>
                    @endforeach
                    
                    {{-- Toplam Satırı --}}
                    <tr class="bg-gray-50">
                        <td colspan="3" class="border px-3 py-2 font-semibold text-right">ARA TOPLAM:</td>
                        <td class="border px-2 py-2 text-center font-bold text-primary-600">{{ number_format($receipt->calculateSubtotal(), 2) }} ₺</td>
                        <td colspan="2" class="border"></td>
                    </tr>
                    
                    @if($receipt->payment_method === 'credit_card')
                        <tr class="bg-blue-50">
                            <td colspan="3" class="border px-3 py-2 font-semibold text-right text-blue-700">💳 Kredi Kartı Komisyonu (%1):</td>
                            <td class="border px-2 py-2 text-center font-bold text-blue-700">+{{ number_format($receipt->calculateCommission(), 2) }} ₺</td>
                            <td colspan="2" class="border"></td>
                        </tr>
                        <tr class="bg-primary-50">
                            <td colspan="3" class="border px-3 py-2 font-bold text-right text-primary-700">TOPLAM:</td>
                            <td class="border px-2 py-2 text-center font-bold text-lg text-primary-700">{{ number_format($receipt->calculateTotalAmount(), 2) }} ₺</td>
                            <td colspan="2" class="border"></td>
                        </tr>
                    @else
                        <tr class="bg-primary-50">
                            <td colspan="3" class="border px-3 py-2 font-bold text-right text-primary-700">TOPLAM:</td>
                            <td class="border px-2 py-2 text-center font-bold text-lg text-primary-700">{{ number_format($receipt->calculateTotalAmount(), 2) }} ₺</td>
                            <td colspan="2" class="border"></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="text-right mt-4">
                <button type="submit"
                        class="font-semibold px-6 py-2 rounded shadow transition"
                        style="background-color: #e39537 !important; color: white !important;">
                    💾 Güncelle
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
