<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Owenoj\LaravelGetId3\GetId3;
use falahati\PHPMP3\MpegAudio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MusicAddRequest;
use App\Http\Requests\MusicEditRequest;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\Music;
use App\Models\Genre;
use App\Models\Downloads;
use Illuminate\Http\Request;
use Exception;
use App\Http\Resources\MusicCollection;
use App\Http\Resources\MusicResource;

class MusicController extends Controller
{


    public function musicIndex(Request $request): MusicCollection
{
    try {

        $tracks = Music::orderBy('created_at', 'desc')->get();
        return new MusicCollection($tracks);
    } catch (\Exception $e) {

        Log::error('Error fetching tracks: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch tracks. Please try again later.'], 500);
    }
}
public function genresIndex($genre)
    {
        try {
            // Fetch music tracks for the given genre
            $tracks = Music::whereHas('genre', function ($query) use ($genre) {
                $query->where('title', $genre);
            })->get();

            return response()->json([
                'data' => $tracks
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tracks.'], 500);
        }
    }
public function genresList(Request $request)
{
    try {
        // Fetch distinct genres from the Music table (assuming 'genre_title' is the column storing genres)
        $genre = Genre::select('title')

                        ->orderBy('title')
                        ->get();

        return response()->json($genre);
    } catch (\Exception $e) {
        Log::error('Error fetching genres: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch genres. Please try again later.'], 500);
    }
}


    public function Trackview(Request $request, Music $track): MusicResource
    {
        Log::info('Trackview method accessed');

        return new MusicResource($track);
    }

    public function showBySlug($slug)
    {
        $track = Music::where('slug', $slug)->firstOrFail();

        if (!$track) {
            Log::warning('Track not found for slug: ' . $slug);
            return response()->json(['error' => 'Music not found'], 404);
        }

        return response()->json([
            'track' => $track,
            'buttonType' => $track->getButtonType(),
        ]);
    }


    function index(Request $request, $fieldname = null, $fieldvalue = null)
    {
        // Get the authenticated user
        $user = Auth::user();
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

    function add(MusicAddRequest $request)
    {
        // Log the validated input data
        Log::info('MusicAddRequest Data:', $request->all());

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
        $user = Auth::user();
        $record = $user->musics()->create($modeldata);

        // Log the created record data
        Log::info('Created Music Record:', $record->toArray());

        $this->afterAdd($record);
        return $this->respond($record);
    }

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
            ->trim(10, 50)
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
    // public function downloadMp3($trackId)
    // {
    //     $track = Music::find($trackId);

    //     if (!$track) {
    //         return response()->json(['error' => 'Music track not found.'], 404);
    //     }

    //     if ($track->beat) {
    //         $track->sold = true;
    //     } else {
    //         $track->downloads++;
    //     }
    //     $track->md++;
    //     $track->save();
    //     $trackFilePath = $track->file;

    //     if (!Storage::exists($trackFilePath)) {
    //         return response()->json(['error' => 'Music file not found.'], 404);
    //     }
    //     return Storage::download($trackFilePath, $track->artist . '-' . $track->title . '.mp3');
    // }
    public function downloadMp3($trackId)
    {
        $track = Music::find($trackId);

        if (!$track) {
            return response()->json(['error' => 'Music track not found.'], 404);
        }

        if ($track->beat) {
            $track->sold = true;
        } else {
            $track->downloads++;
        }
        $track->md++;
        $track->save();

        $trackFilePath = $track->file;

        if (!Storage::exists($trackFilePath)) {
            return response()->json(['error' => 'Music file not found.'], 404);
        }

        $filePath = Storage::path($trackFilePath);

        return response()->file($filePath, [
            'Content-Type' => 'audio/mpeg',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ]);
    }

    public function getMusicData(Request $request)
    {
        $track = Music::find($request->id);
        if ($track) {
            return response()->json([
                'demo' => asset('storage/demos/' . $track->demo),
                'image' => asset('storage/' . $track->image),
                'title' => $track->title ?? '-',
                'artist' => $track->artist ?? '-',
            ]);
        }

        return response()->json(['error' => 'Music not found'], 404);
    }

    public function checkFile(Request $request)
    {
        $track = Music::find($request->musicId);

        if ($track) {
            $filePath = $track->file;
            if (Storage::exists($filePath)) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Music file does not exist on storage']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Music record not found']);
        }
    }

    public function pay(Request $request)
    {
        $request->validate([
            'mpesaNumber' => 'required|string',
            'trackId' => 'required|integer|exists:music,id',
        ]);

        try {

            $music = Music::findOrFail($request->trackId);
            $track = Music::findOrFail($request->trackId);

            $baseUrl = 'https://api.paylesotho.co.ls';
            $merchantid = config('payments.mpesa_sc');
            $merchantname = config('payments.merchant_name');
            $token = config('payments.token');

            $client = new Client();
            $paymentApiUrl = $baseUrl . '/api/v1/vcl/payment';
            $paymentApiData = [
                'merchantid' => $merchantid,
                'amount' => $music->amount,
                'mobileNumber' => $request->mpesaNumber,
                'merchantname' => $merchantname,
                'client_reference' => uniqid('pay_', true),
            ];

            Log::info('Payment API Request: ' . json_encode([
                'url' => $paymentApiUrl,
                'data' => $paymentApiData,
            ]));

            $response = $client->post($paymentApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $paymentApiData,
            ]);

            $responseData = json_decode($response->getBody(), true);
            Log::info('Payment API Response: ' . json_encode($responseData));

            if (isset($responseData['status_code']) && $responseData['status_code'] === 'INS-0') {
                $payRef = $responseData['reference'];
                $verificationUrl = $baseUrl . '/api/v1/vcl/verify/' . $payRef . '/62915';

                $verificationResponse = $client->get($verificationUrl, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                ]);

                $verificationData = json_decode($verificationResponse->getBody(), true);
                Log::info('Confirmation API Response: ' . json_encode($verificationData));

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Error decoding verification response: ' . json_last_error_msg());
                    return response()->json(['error' => 'Failed to verify the transaction'], 500);
                }

                if (isset($verificationData['status_code']) && $verificationData['status_code'] === 'INS-0') {
                    $this->updateUserBalance($music->id, $music->amount);
                    $this->userMusic($music->id, $request->mpesaNumber);

                    return response()->json([
                        'success' => true,
                        'trackId' => $track->id, // Construct the URL manually
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Transaction verification failed',
                    ], 500);
                }

            } else {
                return response()->json(['error' => $responseData['message'] ?? 'Payment API request failed'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Guzzle Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to make the API request'], 500);
        }
    }
    private function updateUserBalance($musicId, $amount)
    {
        $music = Music::find($musicId);
        if (!$music) {
            Log::error('Music track not found for ID: ' . $musicId);
            return;
        }
        $pivot = $music->users()->wherePivot('music_id', $musicId)->first()->pivot;
        if (!$pivot) {
            Log::error('Pivot record not found for music ID: ' . $musicId);
            return;
        }
        $uploaderId = $pivot->user_id;
        $user = User::find($uploaderId);
        if (!$user) {
            Log::error('Uploader user not found for ID: ' . $uploaderId);
            return;
        }
        $user->balance += $amount;
        $user->save();
        Log::info('User balance updated successfully for User ID: ' . $user->id);
    }

    public function sendSMS2($message, $mobileNumber)
    {
        $apiKey = config('sms.api_key');
        $apiSecret = config('sms.api_secret');
        $to = '+266' . $mobileNumber;
        $accountApiCredentials = $apiKey . ':' . $apiSecret;
        $base64Credentials = base64_encode($accountApiCredentials);
        $authHeader = 'Authorization: Basic ' . $base64Credentials;
        $sendData = json_encode([
            'messages' => [
                [
                    'content' => $message,
                    'destination' => $to,
                ],
            ],
        ]);
        Log::info('SMS Sending Data: ' . $sendData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://rest.mymobileapi.com/bulkmessages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            $authHeader
        ]);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            Log::info('SMS sent successfully');
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            Log::error('SMS sending failed. Status: ' . $status);
            return response()->json(['message' => 'SMS sending failed'], 500);
        }
    }
    public function userMusic($musicId, $mobileNumber)
    {
        $music = Music::find($musicId);
        if ($music) {
            $otp = 'GW' . rand(1000, 9999);
            $url = config('app.url');
            $fullurl = $url . '/getdownloads';
            $downloads = new Downloads([
                'artist' => $music->artist,
                'title' => $music->title,
                'mobile' => $mobileNumber, // Use the mobile number passed as argument
                'file' => $music->file,
                'otp' => $otp,
            ]);
            $downloads->save();
            $message = 'Use :' . $otp . ' on ' . $fullurl . ' if download did not start';
            $this->sendSMS2($message, $mobileNumber); // Use the mobile number passed as argument
        } else {
            Log::error('Music track not found for ID: ' . $musicId);
        }
    }

    public function downloadSong($musicId)
    {
        $music = Music::find($musicId);

        if (!$music) {
            return response()->json(['error' => 'Music track not found.'], 404);
        }

        $musicFilePath = $music->file;
        $music->md++;
        $music->downloads++;
        $music->save();

        if (!Storage::exists($musicFilePath)) {
            return response()->json(['error' => 'Music file not found.'], 404);
        }

        return Storage::download($musicFilePath, $music->artist . '-' . $music->title . '.mp3');
    }
}
