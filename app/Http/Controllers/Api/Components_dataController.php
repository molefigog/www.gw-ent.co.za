<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * Components Data Contoller
 * Use for getting values from the database for page components
 * Support raw query builder
 * @category Controller
 */
class Components_dataController extends Controller{
	public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['users_name_exist','users_email_exist','users_password_exist','users_mobile_number_exist']]);
    }
	/**
     * genre_id_option_list Model Action
     * @return array
     */
	function genre_id_option_list(Request $request){
		$sqltext = "SELECT id AS value,title AS label,title AS caption,image AS image FROM genres ORDER BY id ASC";
		$query_params = [];
		$arr = DB::select($sqltext, $query_params);
		return $arr;
	}
	/**
     * genre_id_option_list_2 Model Action
     * @return array
     */
	function genre_id_option_list_2(Request $request){
		$sqltext = "SELECT id AS value,title AS label,title AS caption,image AS image FROM genres ORDER BY id";
		$query_params = [];
		$arr = DB::select($sqltext, $query_params);
		return $arr;
	}
	/**
     * check if name value already exist in Users
	 * @param string $value
     * @return bool
     */
	function users_name_exist(Request $request, $value){
		$exist = DB::table('users')->where('name', $value)->value('name');
		if($exist){
			return "true";
		}
		return "false";
	}
	/**
     * check if email value already exist in Users
	 * @param string $value
     * @return bool
     */
	function users_email_exist(Request $request, $value){
		$exist = DB::table('users')->where('email', $value)->value('email');
		if($exist){
			return "true";
		}
		return "false";
	}
	/**
     * check if password value already exist in Users
	 * @param string $value
     * @return bool
     */
	function users_password_exist(Request $request, $value){
		$exist = DB::table('users')->where('password', $value)->value('password');
		if($exist){
			return "true";
		}
		return "false";
	}
	/**
     * check if mobile_number value already exist in Users
	 * @param string $value
     * @return bool
     */
	function users_mobile_number_exist(Request $request, $value){
		$exist = DB::table('users')->where('mobile_number', $value)->value('mobile_number');
		if($exist){
			return "true";
		}
		return "false";
	}
	/**
     * getcount_music Model Action
     * @return Value
     */
	function getcount_music(Request $request){
		$sqltext = "SELECT COUNT(*) AS num FROM music";
		$query_params = [];
		$val = DB::select($sqltext, $query_params);
		return $val[0]->num;
	}
	/**
     * getcount_users Model Action
     * @return Value
     */
	function getcount_users(Request $request){
		$sqltext = "SELECT COUNT(*) AS num FROM users";
		$query_params = [];
		$val = DB::select($sqltext, $query_params);
		return $val[0]->num;
	}
	/**
     * getcount_beats Model Action
     * @return Value
     */
	function getcount_beats(Request $request){
		$sqltext = "SELECT COUNT(*) AS num FROM beats";
		$query_params = [];
		$val = DB::select($sqltext, $query_params);
		return $val[0]->num;
	}
	/**
     * getcount_genres Model Action
     * @return Value
     */
	function getcount_genres(Request $request){
		$sqltext = "SELECT COUNT(*) AS num FROM genres";
		$query_params = [];
		$val = DB::select($sqltext, $query_params);
		return $val[0]->num;
	}
}
