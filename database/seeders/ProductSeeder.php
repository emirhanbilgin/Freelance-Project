<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Önce mevcut ürünleri temizle
        Product::truncate();

        $products = [
            'Patates', // Yemeklik patates
            'Kızartmalık Patates',
            'Patates 2 Numara', // Haşlamalık patates
            'Kızartmalık 2 Numara',
            'Soğan', // Beyaz soğan
            'Soğan Kavurmalık', // Kavurmalık soğan
            'Mor Soğan',
            'Sarımsak',
            'Mor Paket',
            'Mor Soğan Kavurmalık',
            'Kırmızı Soğan',
            'Limon',
            'Arpacık Paket',
            'Soyulmuş Sarımsak',
            'Soyulmuş Arpacık',
            'Zencefil',
            'Tatlı Patates',
            'Bal Kabağı',
        ];

        foreach ($products as $name) {
            Product::create(['name' => $name]);
        }
    }
}
