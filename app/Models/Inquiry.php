<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'type',
        'genre',
        'price',
        'phone',
        'paid',
        'paid_amount',
    ];
}
