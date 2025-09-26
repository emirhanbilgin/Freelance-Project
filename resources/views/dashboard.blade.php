<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl sm:text-2xl text-gray-800 dark:text-gray-200">
                🏪 Hakan Gıda Sipariş
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('receipts.archived') }}" 
                   class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg transition-colors duration-200">
                    📁 Arşiv
                </a>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ now()->setTimezone('Europe/Istanbul')->format('d.m.Y H:i') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto space-y-6">

        {{-- Ürünleri JS'e aktar --}}
        <script>
            const products = {!! isset($products) ? json_encode($products) : '[]' !!};
        </script>

        {{-- İstatistikler --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm opacity-90">Toplam Fiş</p>
                        <p class="text-2xl font-bold">{{ $receipts->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-warning-500 to-warning-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm opacity-90">Müşteriler</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Customer::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm opacity-90">Ürünler</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Product::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Günlük Satış Raporu Butonu --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 bg-info-100 dark:bg-info-900 rounded-xl">
                        <svg class="w-8 h-8 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Günlük Satış Raporu</h3>
                        <p class="text-gray-600 dark:text-gray-400">Bugün hangi üründen ne kadar satıldığını görün</p>
                    </div>
                </div>
                <button onclick="showDailyReport()" class="bg-info-600 hover:bg-info-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Raporu Görüntüle
                </button>
            </div>
        </div>

        {{-- Hızlı İşlemler --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Yeni Fiş Oluşturma --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-xl">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Yeni Fiş Oluştur</h3>
                        <p class="text-gray-600 dark:text-gray-400">Hızlı sipariş alma</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('receipts.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="customer_name" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">
                            👤 Müşteri Adı
                        </label>
                        <input type="text" name="customer_name" id="customer_name" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                               placeholder="Müşteri adını girin" required>
                    </div>

                    <div>
                        <label for="description" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">
                            📝 Açıklama (Opsiyonel)
                        </label>
                        <textarea name="description" id="description" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                  placeholder="Sipariş notları..."></textarea>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">
                            💳 Ödeme Biçimi (Opsiyonel)
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="payment_methods[]" value="cash" 
                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">💵 Nakit</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="payment_methods[]" value="credit_card" 
                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">💳 Kredi Kartı</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Hiçbiri seçilmezse veresiye olarak kaydedilir</p>
                    </div>

                    {{-- Dinamik Ürün Listesi --}}
                    <div id="product-list" class="space-y-3">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
                            <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 items-end">
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ürün</label>
                                    <select name="product_id[]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm" required></select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fiyat (₺)</label>
                                    <input type="number" name="price[]" placeholder="0.00" step="0.01" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">kg</label>
                                    <input type="number" name="quantity[]" placeholder="1" min="0.1" step="0.1" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="addProductRow()" 
                                            class="w-full bg-success-500 hover:bg-success-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Ekle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            💾 Fişi Kaydet
                        </button>
                    </div>
                </form>
            </div>

            {{-- Son Fişler --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-warning-100 dark:bg-warning-900 rounded-xl">
                            <svg class="w-8 h-8 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Bugünkü Fişler</h3>
                            <p class="text-gray-600 dark:text-gray-400">Bugün oluşturulan siparişler</p>
                        </div>
                    </div>
                    
                    <!-- Toplu İşlem Butonları -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <div id="selectAllSection" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <button onclick="selectAllReceipts()" 
                                    class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-3 rounded-lg transition-all duration-200 flex items-center justify-center text-sm min-h-[44px] w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="hidden lg:inline">Tümünü Seç</span>
                                <span class="hidden sm:inline lg:hidden">Tümü</span>
                                <span class="sm:hidden">Tümü</span>
                            </button>
                            <button onclick="deselectAllReceipts()" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-3 rounded-lg transition-all duration-200 flex items-center justify-center text-sm min-h-[44px] w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="hidden lg:inline">Seçimi Kaldır</span>
                                <span class="hidden sm:inline lg:hidden">Kaldır</span>
                                <span class="sm:hidden">Kaldır</span>
                            </button>
                        </div>
                        
                        <!-- Toplu Silme Butonu -->
                        <div id="bulkDeleteSection" class="hidden w-full sm:w-auto">
                            <button onclick="deleteSelectedReceipts()" 
                                    class="bg-danger-600 hover:bg-danger-700 text-white font-bold py-3 px-3 rounded-lg transition-all duration-200 flex items-center justify-center min-h-[44px] w-full sm:w-auto text-sm">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="hidden lg:inline">Seçilenleri Sil</span>
                                <span class="hidden sm:inline lg:hidden">Sil</span>
                                <span class="sm:hidden">Sil</span>
                                <span class="ml-1">(<span id="selectedCount">0</span>)</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if ($receipts->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Henüz fiş oluşturulmamış</p>
                    </div>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto p-1">
                        @foreach ($receipts->take(10) as $receipt)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 min-h-[60px]">
                                <div class="flex items-center flex-1 min-w-0">
                                    <!-- Checkbox -->
                                    <input type="checkbox" 
                                           id="receipt_{{ $receipt->id }}" 
                                           value="{{ $receipt->id }}" 
                                           class="receipt-checkbox w-5 h-5 sm:w-4 sm:h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 min-w-[20px] min-h-[20px]"
                                           onchange="updateBulkDeleteSection()">
                                    
                                    <div class="p-2 bg-primary-100 dark:bg-primary-900 rounded-lg ml-3">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 dark:text-white truncate">{{ $receipt->customer->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Fiş #{{ $receipt->id }}</p>
                                        <p class="text-sm font-semibold text-primary-600">{{ number_format($receipt->total_amount, 2) }} ₺</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <a href="{{ route('receipts.edit', $receipt->id) }}" 
                                       class="p-2 text-primary-600 hover:bg-primary-100 rounded-lg transition-colors duration-200 min-w-[44px] min-h-[44px] flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Template Row for JS --}}
    <template id="product-row-template">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ürün</label>
                    <select name="product_id[]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm" required></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fiyat (₺)</label>
                    <input type="number" name="price[]" placeholder="0.00" step="0.01" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">kg</label>
                    <input type="number" name="quantity[]" placeholder="1" min="0.1" step="0.1" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-600 dark:text-white text-sm">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="this.closest('.bg-gray-50').remove()" 
                            class="w-full bg-danger-500 hover:bg-danger-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Sil
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- Günlük Satış Raporu Modal --}}
    <div id="dailyReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-2xl bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Günlük Satış Raporu</h3>
                <button onclick="closeDailyReport()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="dailyReportContent" class="space-y-4">
                <!-- Rapor içeriği buraya gelecek -->
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        function populateSelect(select) {
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                select.appendChild(option);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const firstSelect = document.querySelector('select[name="product_id[]"]');
            if (firstSelect) {
                populateSelect(firstSelect);
            }
        });

        function addProductRow() {
            const container = document.getElementById('product-list');
            const template = document.getElementById('product-row-template');
            const clone = template.content.cloneNode(true);
            const select = clone.querySelector('select[name="product_id[]"]');
            populateSelect(select);
            container.appendChild(clone);
        }

        function showDailyReport() {
            // AJAX ile günlük raporu getir
            fetch('/api/daily-sales-report')
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('dailyReportContent');
                    if (data.length === 0) {
                        content.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-8">Bugün henüz satış yapılmamış.</p>';
                    } else {
                        let html = '<div class="space-y-3">';
                        data.forEach(item => {
                            html += `
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-white">${item.product_name}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">${item.total_quantity} kg</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-primary-600">${item.total_amount} ₺</p>
                                        <p class="text-sm text-gray-500">${item.receipt_count} fiş</p>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        content.innerHTML = html;
                    }
                    document.getElementById('dailyReportModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('dailyReportContent').innerHTML = '<p class="text-red-500 text-center py-8">Rapor yüklenirken hata oluştu.</p>';
                    document.getElementById('dailyReportModal').classList.remove('hidden');
                });
        }

        function closeDailyReport() {
            document.getElementById('dailyReportModal').classList.add('hidden');
        }

        // Toplu Silme Fonksiyonları
        let selectedReceiptIds = new Set();

        function updateBulkDeleteSection() {
            const checkboxes = document.querySelectorAll('.receipt-checkbox');
            const bulkDeleteSection = document.getElementById('bulkDeleteSection');
            const selectedCountSpan = document.getElementById('selectedCount');

            selectedReceiptIds.clear();
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedReceiptIds.add(checkbox.value);
                }
            });

            if (selectedReceiptIds.size > 0) {
                bulkDeleteSection.classList.remove('hidden');
                selectedCountSpan.textContent = selectedReceiptIds.size;
            } else {
                bulkDeleteSection.classList.add('hidden');
                selectedCountSpan.textContent = '0';
            }
        }

        function deleteSelectedReceipts() {
            if (selectedReceiptIds.size === 0) {
                alert('Lütfen silmek istediğiniz fişleri seçin.');
                return;
            }

            if (!confirm('Seçili fişleri silmek istediğinize emin misiniz?')) {
                return;
            }

            const receiptIdsToDelete = Array.from(selectedReceiptIds);

            fetch(`/receipts/bulk-delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ receipt_ids: receiptIdsToDelete })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Seçili fişler başarıyla silindi.');
                    window.location.reload(); // Sayfayı yeniden yükle
                } else {
                    alert('Seçili fişler silinirken hata oluştu: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Seçili fişler silinirken hata oluştu.');
            });
        }

        // Toplu Seçim Fonksiyonları
        function selectAllReceipts() {
            const checkboxes = document.querySelectorAll('.receipt-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateBulkDeleteSection(); // Güncelle
        }

        function deselectAllReceipts() {
            const checkboxes = document.querySelectorAll('.receipt-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkDeleteSection(); // Güncelle
        }

        // Form validation
        document.querySelector('form[method="POST"]').addEventListener('submit', function(e) {
            const customerName = document.getElementById('customer_name').value.trim();
            if (!customerName) {
                e.preventDefault();
                alert('Müşteri adı gereklidir.');
                return;
            }

            const productSelects = document.querySelectorAll('select[name="product_id[]"]');
            const prices = document.querySelectorAll('input[name="price[]"]');
            
            let hasValidProduct = false;
            productSelects.forEach((select, index) => {
                if (select.value && prices[index].value) {
                    hasValidProduct = true;
                }
            });

            if (!hasValidProduct) {
                e.preventDefault();
                alert('En az bir ürün seçmeli ve fiyat girmelisiniz.');
                return;
            }
        });
    </script>
</x-app-layout>
