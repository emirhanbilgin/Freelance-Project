<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name'];
    public function receipts()
    {
        return $this->hasMany(\App\Models\Receipt::class);
    }

}
