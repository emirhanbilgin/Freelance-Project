<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customers.index') }}" 
                   class="text-sm font-semibold px-4 py-2 rounded shadow transition duration-200 bg-gray-600 text-white hover:bg-gray-700">
                    ← Müşterilere Dön
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $customer->name }} - Cari Hareketler
                </h2>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="text-sm font-semibold px-4 py-2 rounded shadow transition duration-200 bg-primary-600 text-white hover:bg-primary-700">
                Ana Sayfa
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-8">
        {{-- Müşteri Bilgileri --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-primary-600 font-bold text-2xl">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $customer->name }}</h3>
                    <p class="text-gray-600">Müşteri ID: {{ $customer->id }}</p>
                    <p class="text-sm text-gray-500">Toplam {{ $customer->receipts->count() }} fiş</p>
                </div>
            </div>
        </div>

        {{-- Cari Özet --}}
        @php
            $totalAmount = 0;
            $paidAmount = 0;
            $creditAmount = 0;
            $creditReceipts = $customer->receipts->where(function($receipt) {
                return is_null($receipt->payment_method) || $receipt->payment_method === '';
            });
            $paidReceipts = $customer->receipts->whereIn('payment_method', ['cash', 'credit_card']);
            
            foreach ($customer->receipts as $receipt) {
                $totalAmount += $receipt->calculateTotalAmount();
                if (is_null($receipt->payment_method) || $receipt->payment_method === '') {
                    $creditAmount += $receipt->calculateTotalAmount();
                } else {
                    $paidAmount += $receipt->calculateTotalAmount();
                }
            }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Toplam Alışveriş</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($totalAmount, 2) }} ₺</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Ödenen Tutar</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($paidAmount, 2) }} ₺</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Veresiye Bakiyesi</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($creditAmount, 2) }} ₺</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fişler Listesi --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Tüm Fişler</h3>
                <p class="text-sm text-gray-600 mt-1">Müşterinin tüm alışveriş geçmişi</p>
            </div>

            @if($customer->receipts->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500">Bu müşteriye ait fiş bulunmuyor</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fiş No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tarih
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tutar
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ödeme Durumu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    İşlemler
                                </th>
            </tr>
            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($customer->receipts as $receipt)
                @php
                    $isCredit = is_null($receipt->payment_method) || $receipt->payment_method === '';
                @endphp
                                <tr class="{{ $isCredit ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $receipt->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $receipt->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ number_format($receipt->calculateTotalAmount(), 2) }} ₺
                                        @if($receipt->payment_method === 'credit_card')
                                            <span class="text-xs text-blue-600">(+%1 komisyon)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($isCredit)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                📝 Veresiye
                                            </span>
                                        @else
                                            @if($receipt->payment_method === 'cash')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    💵 Nakit
                                                </span>
                        @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    💳 Kredi Kartı
                                                </span>
                                            @endif
                        @endif
                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('receipts.show', $receipt->id) }}" 
                                               class="text-primary-600 hover:text-primary-900 px-3 py-1 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                                                Görüntüle
                                            </a>
                                            <a href="{{ route('receipts.pdf', $receipt->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                                PDF
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
</x-app-layout>
