<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptItem extends Model
{
    protected $fillable = ['receipt_id', 'product_id', 'quantity', 'price', 'note'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function receipt()
    {
        return $this->belongsTo(\App\Models\Receipt::class);
    }

    // Product ilişkisinin güvenli kontrolü
    public function getProductNameAttribute()
    {
        return $this->product ? $this->product->name : 'Ürün Bulunamadı';
    }
}
