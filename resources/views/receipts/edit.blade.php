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
                        <span class="ml-2 text-sm text-gray-700">💵 Nakit</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="credit_card" 
                               {{ $receipt->payment_method === 'credit_card' ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">💳 Kredi Kartı</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="" 
                               {{ !in_array($receipt->payment_method, ['cash', 'credit_card']) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">📝 Veresiye</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">Ödeme yöntemini değiştirerek fişi güncelleyebilirsiniz</p>
            </div>

            <div class="overflow-x-auto bg-white shadow rounded-lg mt-6">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Ürün</th>
                        <th class="px-4 py-2 border">Miktar</th>
                        <th class="px-4 py-2 border">Fiyat</th>
                        <th class="px-4 py-2 border">Ara Toplam</th>
                        <th class="px-4 py-2 border">Açıklama</th>
                        <th class="px-4 py-2 border">Sil</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($receipt->items as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->product->name }}</td>
                            <td class="border px-4 py-2">
                                <input type="number" name="items[{{ $loop->index }}][quantity]"
                                       value="{{ $item->quantity }}" class="w-full px-2 py-1 border rounded" required>
                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            </td>
                            <td class="border px-4 py-2">
                                <input type="number" name="items[{{ $loop->index }}][price]"
                                       value="{{ $item->price }}" step="0.01"
                                       class="w-full px-2 py-1 border rounded" required>
                            </td>
                            <td class="border px-4 py-2 text-center font-semibold text-primary-600">
                                {{ number_format($item->quantity * $item->price, 2) }} ₺
                            </td>
                            <td class="border px-4 py-2">
                                <input type="text" name="items[{{ $loop->index }}][note]"
                                       value="{{ $item->note ?? $receipt->description }}"
                                       class="w-full px-2 py-1 border rounded">
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <input type="checkbox" name="items[{{ $loop->index }}][_delete]" value="1"
                                       class="h-5 w-5 text-red-600">
                            </td>
                        </tr>
                    @endforeach
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
