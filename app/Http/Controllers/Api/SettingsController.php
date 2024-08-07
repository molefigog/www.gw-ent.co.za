<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsAddRequest;
use App\Http\Requests\SettingsEditRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
class SettingsController extends Controller
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
		$query = Setting::query();
		if($request->search){
			$search = trim($request->search);
			Setting::search($query, $search);
		}
		$orderby = $request->orderby ?? "settings.id";
		$ordertype = $request->ordertype ?? "desc";
		$query->orderBy($orderby, $ordertype);
		if($fieldname){
			$query->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$records = $this->paginate($query, Setting::listFields());
		return $this->respond($records);
	}


	/**
     * Select table record by ID
	 * @param string $rec_id
     * @return \Illuminate\View\View
     */
	function view($rec_id = null){
        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$query = Setting::query();
		$record = $query->findOrFail($rec_id, Setting::viewFields());
		return $this->respond($record);
	}


	/**
     * Save form record to the table
     * @return \Illuminate\Http\Response
     */
	function add(SettingsAddRequest $request){
        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$modeldata = $request->validated();

		if( array_key_exists("logo", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['logo'], "logo");
			$modeldata['logo'] = $fileInfo['filepath'];
		}

		if( array_key_exists("favicon", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['favicon'], "favicon");
			$modeldata['favicon'] = $fileInfo['filepath'];
		}

		if( array_key_exists("image", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
			$modeldata['image'] = $fileInfo['filepath'];
		}

		//save Settings record
		$record = Setting::create($modeldata);
		$rec_id = $record->id;
		return $this->respond($record);
	}


	/**
     * Update table record with form data
	 * @param string $rec_id //select record by table primary key
     * @return \Illuminate\View\View;
     */
	function edit(SettingsEditRequest $request, $rec_id = null){

        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$query = Setting::query();
		$record = $query->findOrFail($rec_id, Setting::editFields());
		if ($request->isMethod('post')) {
			$modeldata = $request->validated();

		if( array_key_exists("logo", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['logo'], "logo");
			$modeldata['logo'] = $fileInfo['filepath'];
		}

		if( array_key_exists("favicon", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['favicon'], "favicon");
			$modeldata['favicon'] = $fileInfo['filepath'];
		}

		if( array_key_exists("image", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
			$modeldata['image'] = $fileInfo['filepath'];
		}
			$record->update($modeldata);
			$this->afterEdit($rec_id, $record);
		}
		return $this->respond($record);
	}
    /**
     * After page record updated
     * @param string $rec_id // updated record id
     * @param array $record // updated page record
     */
    private function afterEdit($rec_id, $record){
        //enter statement here
         $imageFilename = basename($record->logo);
         $imaglocation  = 'images/' . $imageFilename;
         $imageFilename1 = basename($record->favicon);
         $imaglocation1  = 'images/' . $imageFilename1;
         $imageFilename2 = basename($record->favicon);
         $imaglocation2  = 'images/' . $imageFilename2;
        try {
            $record->update([
            'logo' => $imaglocation,
            'favicon' => $imaglocation1,
            'image' => $imaglocation2,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update record: ' . $e->getMessage());
        }
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
		$query = Setting::query();
		$query->whereIn("id", $arr_id);
		$records = $query->get(['logo','favicon','image']); //get records files to be deleted before delete
		$query->delete();
		foreach($records as $record){
			$this->deleteRecordFiles($record['logo'], "logo"); //delete file after record delete
			$this->deleteRecordFiles($record['favicon'], "favicon"); //delete file after record delete
			$this->deleteRecordFiles($record['image'], "image"); //delete file after record delete
		}
		return $this->respond($arr_id);
	}
}
