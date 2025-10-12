<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                📄 Fiş #{{ $receipt->id }}
            </h2>
            <div class="flex items-center space-x-2">
                @if($receipt->payment_method === 'cash')
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-medium">💵 Nakit</span>
                @elseif($receipt->payment_method === 'credit_card')
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-medium">💳 Kredi Kartı</span>
                @else
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full font-medium">📝 Veresiye</span>
                @endif
                <a href="{{ route('receipts.pdf', $receipt->id) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>PDF</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto space-y-6">
        
        {{-- Fiş Bilgileri --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">👤 Müşteri Bilgileri</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-semibold">{{ $receipt->customer->name }}</span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">📅 Tarih</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $receipt->created_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">📝 Açıklama</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $receipt->description ?: 'Açıklama yok' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ürün Listesi --}}
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">🛒 Ürünler</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Ürün</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">kg</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Fiyat</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Toplam</th>
                                @if(!$receipt->payment_method || $receipt->payment_method === '')
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">İşlem</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($receipt->items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-white">{{ $item->product->name }}</p>
                                                @if($item->note)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->note }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3">
                                        @if(!$receipt->payment_method || $receipt->payment_method === '')
                                            <form method="POST" action="{{ route('receipt_items.update', $item->id) }}" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" step="0.01" name="price" value="{{ $item->price }}"
                                                       class="w-20 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm">
                                                <span class="text-sm text-gray-500">₺</span>
                                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-2 py-1 rounded-lg text-xs transition-colors duration-200">
                                                    Kaydet
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400">{{ number_format($item->price, 2) }} ₺</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800 dark:text-white">
                                        {{ number_format($item->quantity * $item->price, 2) }} ₺
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="text-primary-600 hover:text-primary-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800 dark:text-white">
                                    Toplam:
                                </td>
                                <td class="px-4 py-3 font-bold text-xl text-primary-600">
                                    {{ number_format($receipt->items->sum(function($item) { return $item->quantity * $item->price; }), 2) }} ₺
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Yeni Ürün Ekleme --}}
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            {{-- Yeni Ürün Ekle --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yeni Ürün Ekle
                </h3>
                
                <form action="{{ route('receipt_items.store', $receipt->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="receipt_id" value="{{ $receipt->id }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="product_id" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">Ürün</label>
                            <select name="product_id" id="product_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Ürün seçin</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="quantity" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">kg</label>
                            <input type="number" name="quantity" id="quantity" min="0.1" step="0.1" value="1"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-success-600 hover:bg-success-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ürünü Ekle
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
