<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Owenoj\LaravelGetId3\GetId3;
use falahati\PHPMP3\MpegAudio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MusicAddRequest;
use App\Http\Requests\MusicEditRequest;
use App\Models\Music;
use Illuminate\Http\Request;
use Exception;

class MusicController extends Controller
{
    function index(Request $request, $fieldname = null, $fieldvalue = null)
    {
        // Get the authenticated user
        $user = auth()->user();
        // Initialize the query
        $query = Music::query();
        // Apply search if provided
        if ($request->search) {
            $search = trim($request->search);
            Music::search($query, $search);
        }
        // Apply ordering
        $orderby = $request->orderby ?? "music.id";
        $ordertype = $request->ordertype ?? "desc";
        $query->orderBy($orderby, $ordertype);
        // Apply fieldname filter if provided
        if ($fieldname) {
            $query->where($fieldname, $fieldvalue);
        }
        // Check user's role and apply additional filters if needed
        if ($user->role != 1) {
            // Join with the pivot table and filter to only include music records associated with the authenticated user
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        // Paginate and respond
        $records = $this->paginate($query, Music::listFields());
        return $this->respond($records);
    }
    /**
     * Select table record by ID
     * @param string $rec_id
     * @return \Illuminate\View\View
     */
    function view($rec_id = null)
    {
        $query = Music::query();
        $record = $query->findOrFail($rec_id, Music::viewFields());
        return $this->respond($record);
    }

    /**
     * Save form record to the table
     * @return \Illuminate\Http\Response
     */
    function add(MusicAddRequest $request)
    {
        $modeldata = $request->validated();

        if (array_key_exists("image", $modeldata)) {
            // Move uploaded file from temp directory to destination directory
            $fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
            $modeldata['image'] = $fileInfo['filepath'];
        }

        if (array_key_exists("file", $modeldata)) {
            // Move uploaded file from temp directory to destination directory
            $fileInfo = $this->moveUploadedFiles($modeldata['file'], "file");
            $modeldata['file'] = $fileInfo['filepath'];
        }
        // Save Music record
        $user = auth()->user();
        $record = $user->musics()->create($modeldata);
        $this->afterAdd($record);
        return $this->respond($record);
    }
    /**
     * After new record created
     * @param array $record // newly created record
     */
    private function afterAdd($record)
    {
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

        // Extract filename without path
        $filename = basename($record->file);
        $imageFilename = basename($record->image);
        $imaglocation = 'images/' . $imageFilename;

        try {
            $record->update([
                'file' => $filename,
                'image' => $imaglocation,
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
    function edit(MusicEditRequest $request, $rec_id = null)
    {
        $query = Music::query();
        $record = $query->findOrFail($rec_id, Music::editFields());
        if ($request->isMethod('post')) {
            $modeldata = $request->validated();

            if (array_key_exists("image", $modeldata)) {
                //move uploaded file from temp directory to destination directory
                $fileInfo = $this->moveUploadedFiles($modeldata['image'], "image");
                $modeldata['image'] = $fileInfo['filepath'];
            }

            if (array_key_exists("file", $modeldata)) {
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
    function delete(Request $request, $rec_id = null)
    {
        $arr_id = explode(",", $rec_id);
        $query = Music::query();
        $query->whereIn("id", $arr_id);
        $records = $query->get(['image', 'file']); //get records files to be deleted before delete
        $query->delete();
        foreach ($records as $record) {
            $this->deleteRecordFiles($record['image'], "image"); //delete file after record delete
            $this->deleteRecordFiles($record['file'], "file"); //delete file after record delete
        }
        return $this->respond($arr_id);
    }

    public function getMusicData(Request $request)
    {
        $music = Music::find($request->id);
        if ($music) {
            return response()->json([
                'demo' => asset('storage/demos/' . $music->demo),
                'image' => asset('storage/' . $music->image),
                'title' => $music->title ?? '-',
                'artist' => $music->artist ?? '-',
            ]);
        }

        return response()->json(['error' => 'Music not found'], 404);
    }

    public function checkFile(Request $request)
    {
        $music = Music::find($request->musicId);

        if ($music) {
            $filePath = $music->file; // Ensure this is the correct attribute for the file path
            if (Storage::exists($filePath)) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Music file does not exist on storage']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Music record not found']);
        }
    }
}
