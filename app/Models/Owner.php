<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['email', 'whatsapp', 'facebook', 'address'];

    protected $searchableFields = ['*'];
}
