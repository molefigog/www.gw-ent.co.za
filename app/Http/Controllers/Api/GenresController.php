<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenresAddRequest;
use App\Http\Requests\GenresEditRequest;
use App\Models\Genre;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
class GenresController extends Controller
{


	/**
     * List table records
	 * @param  \Illuminate\Http\Request
     * @param string $fieldname //filter records by a table field
     * @param string $fieldvalue //filter value
     * @return \Illuminate\View\View
     */
	function index(Request $request, $fieldname = null , $fieldvalue = null){

		$query = Genre::query();
		if($request->search){
			$search = trim($request->search);
			Genre::search($query, $search);
		}
		$orderby = $request->orderby ?? "genres.id";
		$ordertype = $request->ordertype ?? "desc";
		$query->orderBy($orderby, $ordertype);
		if($fieldname){
			$query->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$records = $this->paginate($query, Genre::listFields());
		return $this->respond($records);
	}


	/**
     * Select table record by ID
	 * @param string $rec_id
     * @return \Illuminate\View\View
     */
	function view($rec_id = null){
		$query = Genre::query();
		$record = $query->findOrFail($rec_id, Genre::viewFields());
		return $this->respond($record);
	}


	/**
     * Save form record to the table
     * @return \Illuminate\Http\Response
     */
	function add(GenresAddRequest $request){
		$modeldata = $request->validated();

		if( array_key_exists("image", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
			$modeldata['image'] = $fileInfo['filepath'];
		}

		//save Genres record
		$record = Genre::create($modeldata);
		$rec_id = $record->id;
		$this->afterAdd($record);
		return $this->respond($record);
	}
    /**
     * After new record created
     * @param array $record // newly created record
     */
    private function afterAdd($record){
        //enter statement here
         $imageFilename2 = basename($record->image);
         $imaglocation2  = 'images/' . $imageFilename2;
        try {
            $record->update([
            'image' => $imaglocation2,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update record: ' . $e->getMessage());
        }
    }


	/**
     * Update table record with form data
	 * @param string $rec_id //select record by table primary key
     * @return \Illuminate\View\View;
     */
	function edit(GenresEditRequest $request, $rec_id = null){
        $user = auth()->user();

        // Check if the user has role 1
        if ($user->role != 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
		$query = Genre::query();
		$record = $query->findOrFail($rec_id, Genre::editFields());
		if ($request->isMethod('post')) {
			$modeldata = $request->validated();

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
        $imageFilename2 = basename($record->favicon);
         $imaglocation2  = 'images/' . $imageFilename2;
        try {
            $record->update([
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
		$query = Genre::query();
		$query->whereIn("id", $arr_id);
		$records = $query->get(['image']); //get records files to be deleted before delete
		$query->delete();
		foreach($records as $record){
			$this->deleteRecordFiles($record['image'], "image"); //delete file after record delete
		}
		return $this->respond($arr_id);
	}
}
