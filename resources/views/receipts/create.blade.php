<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Yeni Fiş Oluştur') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('receipts.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="customer_name" class="block font-semibold mb-1">Müşteri Adı</label>
                        <input type="text" name="customer_name" id="customer_name" required class="w-full border px-3 py-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label for="product_id" class="block font-semibold mb-1">Ürün Seç</label>
                        <select name="product_id" id="product_id" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Ürün Seçiniz --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block font-semibold mb-1">Fiyat (₺)</label>
                        <input type="number" name="price" id="price" step="0.01" min="0" required class="w-full border px-3 py-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-semibold mb-1">Açıklama</label>
                        <textarea name="description" id="description" rows="3" class="w-full border px-3 py-2 rounded"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Fişi Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
