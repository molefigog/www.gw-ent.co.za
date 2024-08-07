<?php

namespace App\Models;

use App\Models\Beat;
use App\Models\Music;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'image'];

    protected $searchableFields = ['*'];

    public function music()
    {
        return $this->hasMany(Music::class);
    }
    public function beat()
    {
        return $this->hasMany(Beat::class);
    }

	/**
     * Set search query for the model
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $text
     */
	public static function search($query, $text){
		//search table record
		$search_condition = '(
				title LIKE ?  OR
				id LIKE ?
		)';
		$search_params = [
			"%$text%","%$text%"
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
			"title",
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
			"title",
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
			"title",
			"image",
			"id"
		];
	}


	/**
     * return exportView page fields of the model.
     *
     * @return array
     */
	public static function exportViewFields(){
		return [
			"title",
			"image",
			"id"
		];
	}


	/**
     * return edit page fields of the model.
     *
     * @return array
     */
	public static function editFields(){
		return [
			"title",
			"image",
			"id"
		];
	}


}
