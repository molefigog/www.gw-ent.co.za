<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title'];

    protected $searchableFields = ['*'];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
