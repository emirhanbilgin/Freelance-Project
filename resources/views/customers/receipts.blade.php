@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">{{ $customer->name }} adlı müşterinin fişleri</h2>

        <table class="w-full table-auto border">
            <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2 text-left">Fiş ID</th>
                <th class="border px-4 py-2 text-left">Tarih</th>
                <th class="border px-4 py-2 text-left">Durum</th>
                <th class="border px-4 py-2 text-left">İşlem</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($customer->receipts as $receipt)
                @php
                    $isCredit = is_null($receipt->payment_method) || $receipt->payment_method === '';
                @endphp
                <tr class="{{ $isCredit ? 'bg-red-50' : '' }}">
                    <td class="border px-4 py-2">{{ $receipt->id }}</td>
                    <td class="border px-4 py-2">{{ $receipt->created_at->format('d.m.Y H:i') }}</td>
                    <td class="border px-4 py-2">
                        @if ($isCredit)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-700">Veresiye</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">Ödendi ({{ $receipt->payment_method === 'cash' ? 'Nakit' : 'Kart' }})</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('receipts.show', $receipt->id) }}" class="text-blue-600 hover:underline">Detay</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
