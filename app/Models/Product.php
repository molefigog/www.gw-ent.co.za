<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RyanChandler\Comments\Concerns\HasComments;

class Product extends Model
{
    use HasFactory;
    use Searchable;


    protected $fillable = [
        'name',
        'detail',
        'category_name',
        'slug',
    ];
    protected static function boot()
    {
        parent::boot();

        // Event listener for creating a new record
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        // Event listener for updating an existing record
        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }
    protected $searchableFields = ['*'];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_name', 'title');
    }
}
