<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRegisterRequest;
use App\Http\Requests\UsersAccountEditRequest;
use App\Http\Requests\UsersAddRequest;
use App\Http\Requests\UsersEditRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
class UsersController extends Controller
{


	/**
     * List table records
	 * @param  \Illuminate\Http\Request
     * @param string $fieldname //filter records by a table field
     * @param string $fieldvalue //filter value
     * @return \Illuminate\View\View
     */
	function index(Request $request, $fieldname = null , $fieldvalue = null){
        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$query = User::query();
		if($request->search){
			$search = trim($request->search);
			User::search($query, $search);
		}
		$orderby = $request->orderby ?? "users.id";
		$ordertype = $request->ordertype ?? "desc";
		$query->orderBy($orderby, $ordertype);
		if($fieldname){
			$query->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$records = $this->paginate($query, User::listFields());
		return $this->respond($records);
	}


	/**
     * Select table record by ID
	 * @param string $rec_id
     * @return \Illuminate\View\View
     */
	function view($rec_id = null){
		$query = User::query();
		$record = $query->findOrFail($rec_id, User::viewFields());
		return $this->respond($record);
	}


	/**
     * Save form record to the table
     * @return \Illuminate\Http\Response
     */
	function add(UsersAddRequest $request){
        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$modeldata = $request->validated();

		if( array_key_exists("avatar", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['avatar'], "avatar");
			$modeldata['avatar'] = $fileInfo['filepath'];
		}
		$modeldata['password'] = bcrypt($modeldata['password']);

		//save Users record
		$record = User::create($modeldata);
		$rec_id = $record->id;
		return $this->respond($record);
	}


	/**
     * Update table record with form data
	 * @param string $rec_id //select record by table primary key
     * @return \Illuminate\View\View;
     */
	// function edit(UsersEditRequest $request, $rec_id = null){
	// 	$query = User::query();
	// 	$record = $query->findOrFail($rec_id, User::editFields());
	// 	if ($request->isMethod('post')) {
	// 		$modeldata = $request->validated();

	// 	if( array_key_exists("avatar", $modeldata) ){
	// 		//move uploaded file from temp directory to destination directory
	// 		$fileInfo = $this->moveUploadedFiles($modeldata['avatar'], "avatar");
	// 		$modeldata['avatar'] = $fileInfo['filepath'];
	// 	}
	// 		$record->update($modeldata);
	// 	}
	// 	return $this->respond($record);
	// }
    function edit(UsersEditRequest $request, $rec_id = null){
        // Get the authenticated user
        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Initialize the query
        $query = User::query();
        $record = $query->findOrFail($rec_id, User::editFields());

        if ($request->isMethod('post')) {
            // Validate and get the model data
            $modeldata = $request->validated();

            // Handle avatar upload if present
            if (array_key_exists("avatar", $modeldata)) {
                // Move uploaded file from temp directory to destination directory
                $fileInfo = $this->moveUploadedFiles($modeldata['avatar'], "avatar");
                $modeldata['avatar'] = $fileInfo['filepath'];
            }

            // Update the record
            $record->update($modeldata);
        }

        return $this->respond($record);
    }


	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
	 * @param  \Illuminate\Http\Request
	 * @param string $rec_id //can be separated by comma
     * @return \Illuminate\Http\Response
     */
	function delete(Request $request, $rec_id = null){

        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$arr_id = explode(",", $rec_id);
		$query = User::query();
		$query->whereIn("id", $arr_id);
		$records = $query->get(['avatar']); //get records files to be deleted before delete
		$query->delete();
		foreach($records as $record){
			$this->deleteRecordFiles($record['avatar'], "avatar"); //delete file after record delete
		}
		return $this->respond($arr_id);
	}
}
