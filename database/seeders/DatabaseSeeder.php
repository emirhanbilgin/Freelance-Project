<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ürünleri ekle
        $this->call(ProductSeeder::class);

        // Sabit kullanıcıyı oluştur/güncelle
        $user = User::updateOrCreate(
            ['email' => 'bilginemirhan50@gmail.com'],
            [
                'name' => 'Hakan Gıda Kullanıcısı',
                'password' => Hash::make('Hakan3450!'),
                'email_verified_at' => now(),
            ]
        );

        // Müşteri oluştur
        $customer = Customer::firstOrCreate([
            'name' => 'Test Müşteri',
        ]);

        // Fiş oluştur
        $receipt = Receipt::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'description' => 'Test açıklama',
        ]);

        // Ürün varsa fişe 1 ürün ekle
        $product = \App\Models\Product::first();
        if ($product) {
            ReceiptItem::create([
                'receipt_id' => $receipt->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 15.5,
            ]);
        }
    }
}
