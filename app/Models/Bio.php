<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bio extends Model
{
    use HasFactory;
     protected $table = 'bios';

    protected $fillable = [

        'artist',
        'position',
        'bio',
        'achievements',
        'contact',
        'user_id',
        'image'

    ];
    protected $casts = [
        'achievements' => 'json',
        'contact' => 'json',
    ];
}
