<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class PurchasedItems extends Model
{
    protected $table = 'purchaseditems'; // Name of the purchaseditems table

    protected $fillable = [
        'user_id',
        'music_id',
        'artist',
        'title',
        'image',
        'file',
        'description',
        'duration',
        'size',
        'downloads',
        // Other fields...
    ];
}
