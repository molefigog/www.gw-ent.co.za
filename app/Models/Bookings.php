<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $fillable = [

        'artist',
        'bank',
        'mpesa',
        'pricing',
        'int_pricing',
        'transport',
        'contact',
        'user_id',
        'image'

    ];

    protected $casts = [
        'bank' => 'json',
        'pricing' => 'json',
        'int_pricing' => 'json',
        'contact' => 'json',
        'transport' => 'json',
    ];
    public function artist()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
