<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function receiptItems()
    {
        return $this->hasMany(\App\Models\ReceiptItem::class);
    }

}
