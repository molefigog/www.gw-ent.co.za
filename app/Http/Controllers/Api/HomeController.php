<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



/**
 * Home Controller
 * @category  Controller
 */
class HomeController extends Controller{
	/**
     * Index Action
     * @return View
     */
	function index(){
		return view("about");
	}

}
