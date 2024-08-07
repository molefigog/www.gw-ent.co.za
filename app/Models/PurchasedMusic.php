<?php

namespace App\Models;

use App\Models\Music;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PurchasedMusic extends Model
{
    protected $table = 'purchased'; // Name of the pivot table

    protected $fillable = [
        'user_id',
        'music_id',
        // Other fields...
    ];

    // Define relationships with User and Music models
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function music()
    {
        return $this->belongsTo(Music::class);
    }
}
