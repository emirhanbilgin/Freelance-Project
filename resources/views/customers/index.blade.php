<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                👥 Müşteriler
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Toplam {{ $customers->count() }} müşteri
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto space-y-6">
        
        @if(session('success'))
            <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Müşteri İstatistikleri --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm opacity-90">Toplam Müşteri</p>
                        <p class="text-2xl font-bold">{{ $customers->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm opacity-90">Toplam Fiş</p>
                        <p class="text-2xl font-bold">{{ $customers->sum('receipts_count') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm opacity-90">Aktif Müşteri</p>
                        <p class="text-2xl font-bold">{{ $customers->where('receipts_count', '>', 0)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Müşteri Arama --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="customer-search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Müşteri Ara
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="customer-search" 
                               placeholder="Müşteri adı yazın..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="button" 
                            id="clear-search"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors duration-200">
                        Temizle
                    </button>
                </div>
            </div>
            
            {{-- Arama Sonuçları --}}
            <div id="search-results" class="mt-4 hidden">
                <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Arama Sonuçları:</h4>
                    <div id="search-results-list" class="space-y-2"></div>
                </div>
            </div>
        </div>

        {{-- Müşteri Listesi --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Müşteri Listesi</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Tüm müşterilerin listesi ve fiş bilgileri</p>
            </div>

            @if ($customers->isEmpty())
                <div class="p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Henüz müşteri bulunmuyor</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">İlk fişi oluşturduğunuzda müşteri otomatik olarak eklenecek</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Müşteri
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fiş Sayısı
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Son Fiş
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($customers as $customer)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $customer->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    ID: {{ $customer->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                {{ $customer->receipts_count }} fiş
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if($customer->receipts_count > 0)
                                            {{ $customer->receipts->first()->created_at->setTimezone('Europe/Istanbul')->format('d.m.Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('customers.receipts', $customer->id) }}" 
                                               class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 px-3 py-1 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900 transition-colors duration-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Fişleri Gör
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('customer-search');
            const searchResults = document.getElementById('search-results');
            const searchResultsList = document.getElementById('search-results-list');
            const clearButton = document.getElementById('clear-search');
            let searchTimeout;

            // Arama fonksiyonu
            function performSearch(query) {
                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                fetch(`{{ route('customers.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(customers => {
                        if (customers.length > 0) {
                            displaySearchResults(customers);
                        } else {
                            searchResultsList.innerHTML = '<p class="text-gray-500 text-sm">Arama kriterlerine uygun müşteri bulunamadı.</p>';
                            searchResults.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Arama hatası:', error);
                        searchResultsList.innerHTML = '<p class="text-red-500 text-sm">Arama sırasında bir hata oluştu.</p>';
                        searchResults.classList.remove('hidden');
                    });
            }

            // Arama sonuçlarını göster
            function displaySearchResults(customers) {
                searchResultsList.innerHTML = customers.map(customer => `
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm">
                                    ${customer.name.charAt(0).toUpperCase()}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">${customer.name}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">ID: ${customer.id}</p>
                            </div>
                        </div>
                        <a href="/customers/${customer.id}/receipts" 
                           class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 px-3 py-1 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900 transition-colors duration-200 text-sm">
                            Fişleri Gör
                        </a>
                    </div>
                `).join('');
                searchResults.classList.remove('hidden');
            }

            // Input event listener
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300); // 300ms debounce
            });

            // Clear button
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchResults.classList.add('hidden');
            });

            // Enter key handling
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        performSearch(query);
                    }
                }
            });
        });
    </script>
</x-app-layout>
