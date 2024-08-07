<?php

namespace App\Models;

use App\Models\Beat;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Beatz extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'beatz'; // Name of the items table

    protected $fillable = [
        'user_id',
        'beat_id',
        'uploader_id',
        'artist',
        'title',
        'image',
        'file',
        'description',
        'duration',
        'size',
        'used',
        // Other fields...
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beat()
    {
        return $this->belongsTo(Beat::class);
    }

}
