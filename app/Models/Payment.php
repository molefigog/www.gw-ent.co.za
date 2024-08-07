<?php

namespace App\Models;

use App\Models\Music;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'music_id',
        'item_number',
        'txn_id',
        'payment_gross',
        'currency_code',
        'payment_status',
    ];

     // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Music model
    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id');
    }
}
