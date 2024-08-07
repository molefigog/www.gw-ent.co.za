<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RyanChandler\Comments\Concerns\HasComments;

class Beat extends Model
{
    use HasFactory;

    use HasComments;


    protected $fillable = [
        'genre_id',
        'artist',
        'title',
        'amount',
        'image',
        'demo',
        'file',
        'description',
        'duration',
        'size',
        'used',
        'slug',

    ];
    protected static function boot()
    {
        parent::boot();

        // Event listener for creating a new record
        static::creating(function ($beat) {
            $beat->slug = Str::slug(str_replace(' ', '-', $beat->title));
        });

        // Event listener for updating an existing record
        static::updating(function ($beat) {
            $beat->slug = Str::slug(str_replace(' ', '-', $beat->title));
        });
    }




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    public function purchasedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'purchased', 'beat_id', 'user_id')->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'beat_user')->withTimestamps();
    }
    public function markAsUsed()
    {
        $this->used = true;
        $this->save();
    }

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'beats';


	/**
     * The table primary key field
     *
     * @var string
     */
	protected $primaryKey = 'id';


	/**
     * Table fillable fields
     *
     * @var array
     */


	/**
     * Set search query for the model
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $text
     */
	public static function search($query, $text){
		//search table record
		$search_condition = '(
				id LIKE ?  OR
				artist LIKE ?  OR
				title LIKE ?  OR
				amount LIKE ?  OR
				demo LIKE ?  OR
				description LIKE ?  OR
				duration LIKE ?  OR
				size LIKE ?  OR
				slug LIKE ?
		)';
		$search_params = [
			"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
		];
		//setting search conditions
		$query->whereRaw($search_condition, $search_params);
	}


	/**
     * return list page fields of the model.
     *
     * @return array
     */
	public static function listFields(){
		return [
			"id",
			"genre_id",
			"artist",
			"title",
			"amount",
			"image",
			"demo",
			"file",
			"description",
			"duration",
			"size",
			"used",
			"slug",
			"created_at",
			"updated_at"
		];
	}


	/**
     * return exportList page fields of the model.
     *
     * @return array
     */
	public static function exportListFields(){
		return [
			"id",
			"genre_id",
			"artist",
			"title",
			"amount",
			"image",
			"demo",
			"file",
			"description",
			"duration",
			"size",
			"used",
			"slug",
			"created_at",
			"updated_at"
		];
	}


	/**
     * return view page fields of the model.
     *
     * @return array
     */
	public static function viewFields(){
		return [
			"id",
			"genre_id",
			"artist",
			"title",
			"amount",
			"image",
			"demo",
			"file",
			"description",
			"duration",
			"size",
			"used",
			"slug",
			"created_at",
			"updated_at"
		];
	}


	/**
     * return exportView page fields of the model.
     *
     * @return array
     */
	public static function exportViewFields(){
		return [
			"id",
			"genre_id",
			"artist",
			"title",
			"amount",
			"image",
			"demo",
			"file",
			"description",
			"duration",
			"size",
			"used",
			"slug",
			"created_at",
			"updated_at"
		];
	}


	/**
     * return edit page fields of the model.
     *
     * @return array
     */
	public static function editFields(){
		return [
			"genre_id",
			"artist",
			"title",
			"amount",
			"image",
			"demo",
			"file",
			"description",
			"duration",
			"size",
			"slug",
			"used",
			"id"
		];
	}


	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
	public $timestamps = true;
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
}
