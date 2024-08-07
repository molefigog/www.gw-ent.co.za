<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MailSetting extends Model
{


	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'mail_settings';


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
	protected $fillable = ["mail_mailer","mail_host","mail_port","mail_username","mail_password","mail_encryption","mail_from_address","mail_from_name"];


	/**
     * Set search query for the model
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $text
     */
	public static function search($query, $text){
		//search table record
		$search_condition = '(
				mail_mailer LIKE ?  OR
				mail_host LIKE ?  OR
				mail_username LIKE ?  OR
				mail_encryption LIKE ?  OR
				mail_from_address LIKE ?  OR
				mail_from_name LIKE ?  OR
				mail_password LIKE ?  OR
				id LIKE ?
		)';
		$search_params = [
			"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
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
			"mail_mailer",
			"mail_host",
			"mail_port",
			"mail_username",
			"mail_encryption",
			"mail_from_address",
			"mail_from_name",
			"mail_password",
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
			"mail_mailer",
			"mail_host",
			"mail_port",
			"mail_username",
			"mail_encryption",
			"mail_from_address",
			"mail_from_name",
			"mail_password",
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
			"mail_mailer",
			"mail_host",
			"mail_port",
			"mail_username",
			"mail_encryption",
			"mail_from_address",
			"mail_from_name",
			"mail_password",
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
			"mail_mailer",
			"mail_host",
			"mail_port",
			"mail_username",
			"mail_encryption",
			"mail_from_address",
			"mail_from_name",
			"mail_password",
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
			"mail_mailer",
			"mail_host",
			"mail_port",
			"mail_username",
			"mail_encryption",
			"mail_from_address",
			"mail_from_name",
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
