<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'description', 'daily_reset', 'archived_at', 'payment_method'];
    
    protected $casts = [
        'daily_reset' => 'boolean',
        'archived_at' => 'datetime',
    ];

    // Ara toplam hesapla (komisyonsuz)
    public function calculateSubtotal()
    {
        $subtotal = 0;
        
        // Items relationship'i yüklenmiş mi kontrol et
        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }
        
        foreach ($this->items as $item) {
            $subtotal += $item->quantity * $item->price;
        }
        return $subtotal;
    }
    
    // Komisyon tutarı hesapla
    public function calculateCommission()
    {
        if ($this->payment_method === 'credit_card') {
            return $this->calculateSubtotal() * 0.01;
        }
        return 0;
    }
    
    // Toplam tutarı hesapla (kredi kartı komisyonu dahil)
    public function calculateTotalAmount()
    {
        $subtotal = $this->calculateSubtotal();
        
        // Kredi kartı ise %1 komisyon ekle
        if ($this->payment_method === 'credit_card') {
            return $subtotal + $this->calculateCommission();
        }
        
        return $subtotal;
    }
    
    // Accessor'lar (geriye uyumluluk için)
    public function getSubtotalAttribute()
    {
        return $this->calculateSubtotal();
    }
    
    public function getCommissionAttribute()
    {
        return $this->calculateCommission();
    }
    
    public function getTotalAmountAttribute()
    {
        return $this->calculateTotalAmount();
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
