<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Arşivlenmiş Fişler
            </h2>
            <a href="{{ route('dashboard') }}" 
               class="text-sm font-semibold px-4 py-2 rounded shadow transition duration-200 bg-primary-600 text-white hover:bg-primary-700">
                ← Ana Sayfaya Dön
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-8">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Arşivlenmiş Fişler Listesi --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Arşivlenmiş Fişler</h3>
                <p class="text-sm text-gray-600 mt-1">Akşam 5'te otomatik olarak arşivlenen fişler</p>
            </div>

            @if($allArchivedReceipts->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500">Henüz arşivlenmiş fiş bulunmuyor</p>
                </div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($allArchivedReceipts as $date => $receipts)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($date);
                            $today = \Carbon\Carbon::today();
                            $yesterday = \Carbon\Carbon::yesterday();
                            
                            if ($carbonDate->isToday()) {
                                $dateLabel = 'Bugün';
                            } elseif ($carbonDate->isYesterday()) {
                                $dateLabel = 'Dün';
                            } else {
                                $dateLabel = $carbonDate->format('d.m.Y');
                            }
                        @endphp
                        
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-primary-100 rounded-lg">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">{{ $dateLabel }}</h4>
                                        <p class="text-sm text-gray-500">{{ $carbonDate->format('l, d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ count($receipts) }} fiş</p>
                                        <p class="text-xs text-gray-500">arşivlendi</p>
                                    </div>
                                    <a href="{{ route('receipts.archived.by-date', $date) }}" 
                                       class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        Görüntüle
                                    </a>
                                </div>
                            </div>
                            
                            {{-- O günün özet bilgileri --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-gray-600">Toplam Tutar</p>
                                    <p class="font-semibold text-gray-900">{{ number_format(collect($receipts)->sum('total_amount'), 2) }} ₺</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-gray-600">Müşteri Sayısı</p>
                                    <p class="font-semibold text-gray-900">{{ collect($receipts)->unique('customer_id')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 