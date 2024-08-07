<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Music;
use App\Models\User;
use App\Models\Scopes\Searchable;

class Items extends Model
{
    use HasFactory;
    use Searchable;
    protected $table = 'items'; // Name of the items table

    protected $fillable = [
        'user_id',
        'music_id',
        'uploader_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function music()
    {
        return $this->belongsTo(Music::class);
    }

}
