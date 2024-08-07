<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'site',
        'description',
        'tagline',
        'logo',
        'favicon',
        'image',
    ];

    protected $searchableFields = ['*'];

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'settings';

    public function getLogoUrlAttribute()
    {
        return asset("storage/{$this->logo}");
    }
    public function getFaviconUrlAttribute()
    {
        return asset("storage/{$this->favicon}");
    }
    public function getImageUrlAttribute()
    {
        return asset("storage/{$this->image}");
    }
    protected $appends = [
        'logo_url',
        'favicon_url',
        'image_url',
    ];
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
				site LIKE ?  OR
				tagline LIKE ?  OR
				logo LIKE ?  OR
				favicon LIKE ?  OR
				id LIKE ?  OR
				description LIKE ?
		)';
		$search_params = [
			"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
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
			"site",
			"tagline",
			"logo",
			"favicon",
			"image",
			"id"
		];
	}


	/**
     * return exportList page fields of the model.
     *
     * @return array
     */
	public static function exportListFields(){
		return [
			"site",
			"tagline",
			"logo",
			"favicon",
			"image",
			"id"
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
			"site",
			"description",
			"tagline",
			"logo",
			"favicon",
			"image"
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
			"site",
			"description",
			"tagline",
			"logo",
			"favicon",
			"image"
		];
	}


	/**
     * return edit page fields of the model.
     *
     * @return array
     */
	public static function editFields(){
		return [
			"site",
			"description",
			"tagline",
			"logo",
			"favicon",
			"image",
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
