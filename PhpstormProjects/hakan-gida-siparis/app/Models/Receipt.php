<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'description', 'daily_reset', 'archived_at', 'payment_method'];
    
    protected $casts = [
        'daily_reset' => 'boolean',
        'archived_at' => 'datetime',
    ];

    // Toplam tutarı hesapla
    public function getTotalAmountAttribute()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->price;
        });
    }

    // Ödeme durumunu kontrol et
    public function getIsPaidAttribute()
    {
        return in_array($this->payment_method, ['cash', 'credit_card']);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(\App\Models\ReceiptItem::class);
    }
}
