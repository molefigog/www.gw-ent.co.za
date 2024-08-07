<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeatsAddRequest;
use App\Http\Requests\BeatsEditRequest;
use App\Models\Beat;
use Illuminate\Http\Request;
use Exception;
use Owenoj\LaravelGetId3\GetId3;
use falahati\PHPMP3\MpegAudio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BeatsController extends Controller
{


	/**
     * List table records
	 * @param  \Illuminate\Http\Request
     * @param string $fieldname //filter records by a table field
     * @param string $fieldvalue //filter value
     * @return \Illuminate\View\View
     */
	// function index(Request $request, $fieldname = null , $fieldvalue = null){
	// 	$query = Beat::query();
	// 	if($request->search){
	// 		$search = trim($request->search);
	// 		Beat::search($query, $search);
	// 	}
	// 	$orderby = $request->orderby ?? "beats.id";
	// 	$ordertype = $request->ordertype ?? "desc";
	// 	$query->orderBy($orderby, $ordertype);
	// 	if($fieldname){
	// 		$query->where($fieldname , $fieldvalue); //filter by a single field name
	// 	}
	// 	$records = $this->paginate($query, Beat::listFields());
	// 	return $this->respond($records);
	// }
    function index(Request $request, $fieldname = null, $fieldvalue = null){
        // Get the authenticated user
        $user = auth()->user();

        // Initialize the query
        $query = Beat::query();

        // Apply search if provided
        if($request->search){
            $search = trim($request->search);
            Beat::search($query, $search);
        }

        // Apply ordering
        $orderby = $request->orderby ?? "beats.id";
        $ordertype = $request->ordertype ?? "desc";
        $query->orderBy($orderby, $ordertype);

        // Apply fieldname filter if provided
        if($fieldname){
            $query->where($fieldname, $fieldvalue);
        }

        // Check user's role and apply additional filters if needed
        if($user->role != 1){
            // Join with the pivot table and filter to only include beat records associated with the authenticated user
            $query->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Paginate and respond
        $records = $this->paginate($query, Beat::listFields());
        return $this->respond($records);
    }


	/**
     * Select table record by ID
	 * @param string $rec_id
     * @return \Illuminate\View\View
     */
	function view($rec_id = null){
		$query = Beat::query();
		$record = $query->findOrFail($rec_id, Beat::viewFields());
		return $this->respond($record);
	}


	/**
     * Save form record to the table
     * @return \Illuminate\Http\Response
     */
	function add(BeatsAddRequest $request){
		$modeldata = $request->validated();

		if( array_key_exists("image", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
			$modeldata['image'] = $fileInfo['filepath'];
		}

		if( array_key_exists("file", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['file'], "file");
			$modeldata['file'] = $fileInfo['filepath'];
		}

		//save Beats record
		$record = Beat::create($modeldata);
		$rec_id = $record->id;
		$this->afterAdd($record);
		return $this->respond($record);
	}
    /**
     * After new record created
     * @param array $record // newly created record
     */
    private function afterAdd($record){
        $filePath = public_path($record->file);
        $track = new GetId3($filePath);
        $track->extractInfo();
        $duration = $track->getPlaytime();
        $filesizeInBytes = filesize($filePath);
        $filesizeInMB = round($filesizeInBytes / (1024 * 1024), 2);
        $filenameWithoutExtension = pathinfo($record->file, PATHINFO_FILENAME);
        $demoFilename = str_replace(' ', '-', $filenameWithoutExtension) . '-demo.mp3';
        MpegAudio::fromFile($filePath)
            ->trim(10, 30)
            ->saveFile(public_path('storage/demos/' . $demoFilename));
        try {
            $record->update([
                'duration' => $duration,
                'size' => $filesizeInMB,
                'demo' => $demoFilename,
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
	function edit(BeatsEditRequest $request, $rec_id = null){
		$query = Beat::query();
		$record = $query->findOrFail($rec_id, Beat::editFields());
		if ($request->isMethod('post')) {
			$modeldata = $request->validated();

		if( array_key_exists("image", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
			$modeldata['image'] = $fileInfo['filepath'];
		}

		if( array_key_exists("file", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['file'], "file");
			$modeldata['file'] = $fileInfo['filepath'];
		}
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
		$arr_id = explode(",", $rec_id);
		$query = Beat::query();
		$query->whereIn("id", $arr_id);
		$records = $query->get(['file']); //get records files to be deleted before delete
		$query->delete();
		foreach($records as $record){
			$this->deleteRecordFiles($record['file'], "file"); //delete file after record delete
		}
		return $this->respond($arr_id);
	}
}
