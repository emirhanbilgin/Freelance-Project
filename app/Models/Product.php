<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'default_price'];

    public function receiptItems()
    {
        return $this->hasMany(\App\Models\ReceiptItem::class);
    }

}
