<?php

namespace App\Http\Controllers;


use getID3;
use App\Models\Beat;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BeatStoreRequest;
use App\Http\Requests\BeatUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Genre;
use App\Models\User;
use App\Models\Beatz;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use falahati\PHPMP3\MpegAudio;
use App\Mail\BeatDownloadNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;


class BeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $genreFilter = $request->get('genre', '');

        if (Auth::user()->role == 1) {
            $beats = Beat::latest()
                ->paginate(10)
                ->withQueryString();

            $userBeat = collect(); // Define an empty collection for role 2 users
        } else {
            $user = Auth::user();
            $userBeat = $user->beat()
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $beat = collect(); // Define an empty collection for role 1 users
        }

        // Fetch distinct genres from the beat model
        $genres = Beat::distinct('genre')->pluck('genre_id')->toArray();
        // $recentlyAddedSongs = beat::latest()->take(10)->get();
        // $downloads = beat::orderBy('downloads', 'desc')->take(10)->distinct()->get();

        return view('beats.index', compact('beats', 'userBeat', 'search', 'genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $genres = genre::all();

        return view('beats.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BeatStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = $imageFile->getClientOriginalName();
                $validated['image'] = $imageFile->storeAs('public', $imageName);
            }

            $inputFile = $request->file('file');
            $inputFilePath = $inputFile->store('mp3');

            // Trim the MP3 and save it to a temporary file
            $trimmedFilePath = tempnam(sys_get_temp_dir(), 'trimmed_mp3_');
            MpegAudio::fromFile(Storage::path($inputFilePath))
                ->trim(10, 30)
                ->saveFile($trimmedFilePath);

            if ($request->hasFile('file')) {
                $beatFile = $request->file('file');
                $beatName = $beatFile->getClientOriginalName();
                $validated['file'] = $beatFile->storeAs('public', $beatName);

                $getID3 = new GetId3(); // Create an instance of GetId3
                $beatInfo = $getID3->analyze($beatFile->getPathname());
                $duration = $beatInfo['playtime_string'];
                $sizeInBytes = $beatInfo['filesize'];

                // Convert file size to megabytes
                $sizeInMB = round($sizeInBytes / (1024 * 1024), 2);

                // Add duration and size to the validated data
                $validated['duration'] = $duration;
                $validated['size'] = $sizeInMB;
                // Upload trimmed demo using the same beat file
                $demoName = pathinfo($beatName, PATHINFO_FILENAME) . '_demo.mp3';
                $demoFilePath = tempnam(sys_get_temp_dir(), 'demo_mp3_');
                MpegAudio::fromFile($trimmedFilePath)
                    ->saveFile($demoFilePath);

                $demoPath = 'public/' . $demoName;
                Storage::put($demoPath, file_get_contents($demoFilePath));

                // Add demo file path to validated data
                $validated['demo'] = $demoPath;

                // Clean up temporary demo file
                unlink($demoFilePath);
            }

            // Delete the input file after processing
            if (file_exists(Storage::path($inputFilePath))) {
                unlink(Storage::path($inputFilePath));
            }


            // Save the beat
            $user = Auth::user(); // Get the logged-in user
            $beat = new Beat($validated);
            $user->beat()->save($beat);

            // Return a success response
            return response()->json(['status' => 'success', 'msg' => 'MP3 uploaded successfully']);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error uploading MP3 file: ' . $e->getMessage());

            // Return an error response
            return response()->json(['status' => 'error', 'msg' => 'An error occurred while processing the request. Please try again later.'], 500);
        }
    }

    // slug

    public function showBySlug($slug)
    {
        // $title = urldecode($title); // Decode URL-encoded title
        $beat = Beat::where('slug', $slug)->firstOrFail();

        // Get the base URL dynamically
        $artist = $beat->artist;
        $title = $beat->title;
        $image = url($beat->image ? Storage::url($beat->image) : '');
        $description = $beat->description;
        $baseUrl = 'https://www.gw-ent.co.za';
        $url = "{$baseUrl}/beat/{$beat->slug}";

        // Generate social share buttons
        $shareButtons = \Share::page($url, 'Check out this beat: ' . $beat->title)
            ->facebook()
            ->twitter()
            ->whatsapp();

        // Store the intended URL in the session
        $intendedUrl = route('beat.slug', ['slug' => $slug]);
        Session::put('intended_url', $intendedUrl);

        $userId = Auth::check() ? Auth::user()->id : null;

        // "https://www.paypal.com/cgi-bin/webscr" live url
        $Paypal = config('paypal.paypal_url');
        $PAYPAL_ID = config('paypal.paypal_id');
        $currency = config('paypal.paypal_currency');

        $metaTags = [
            'title' => $title,
            'image' => $image,
            'description' => $description,
            'keywords' => $artist . ', ' . $title, // Separate keywords with a comma
            'url' => $url,
        ];


        return view('beat', compact('beat', 'metaTags',  'shareButtons', 'userId', 'Paypal', 'PAYPAL_ID', 'currency'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Beat $beat): View
    {
        $genres = genre::all();

        return view('beats.edit', compact('beat', 'genres'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(
        BeatUpdateRequest $request,
        Beat $beat
    ): RedirectResponse {

        $validated = $request->validated();
        Log::info('Payment API Response: ' . json_encode($request->all()));
        if ($request->hasFile('image')) {
            if ($beat->image) {
                Storage::delete($beat->image);
            }
            $imageFile = $request->file('image');
            $imageName = $imageFile->getClientOriginalName();
            $validated['image'] = $imageFile->storeAs('public', $imageName);
        }
        if ($request->hasFile('demo')) {
            if ($beat->demo) {
                Storage::delete($beat->demo);
            }
            $demoFile = $request->file('demo');
            $demoName = $demoFile->getClientOriginalName();
            $validated['demo'] = $demoFile->storeAs('public', $demoName);
        }
        if ($request->hasFile('file')) {
            if ($beat->file) {
                Storage::delete($beat->file);
            }
            $beatFile = $request->file('file');
            $beatName = $beatFile->getClientOriginalName();
            $validated['file'] = $beatFile->storeAs('public', $beatName);
            $getID3 = new getID3();
            $beatInfo = $getID3->analyze($beatFile->getPathname());
            $duration = $beatInfo['playtime_string'];
            $sizeInBytes = $beatInfo['filesize'];
            // Convert file size to megabytes
            $sizeInMB = round($sizeInBytes / (1024 * 1024), 2);
            // Add duration and size to the validated data
            $validated['duration'] = $duration;
            $validated['size'] = $sizeInMB;
        }
        $beat->update($validated);
        return redirect()
            ->route('beats.index')
            ->withSuccess(__('saved'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Beat $beat): RedirectResponse
    {
        if ($beat->image) {
            Storage::delete($beat->image);
        }
        if ($beat->demo) {
            Storage::delete($beat->demo);
        }
        if ($beat->file) {
            Storage::delete($beat->file);
        }
        $beat->delete();
        return redirect()
            ->route('all-beat.index')
            ->withSuccess(__('removed'));
    }
    public function songsByGenre($genreName)
    {
        $genre = Genre::where('title', $genreName)->firstOrFail();
        $beatCollection = $genre->beat()
            ->latest()
            ->paginate(10);
        $recentlyAddedSongs = Beat::latest()->take(10)->get();
        $setting = Setting::firstOrFail();
        $appName = config('app.name');
        $url = config('app.url');

        $title = $setting ? $setting->site : $appName;
        $image = asset('storage/og-tag.jpg');
        $keywords = "GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG";

        $metaTags = [
            'title' => $setting->site,
            'description' => $setting->description,
            'image' =>  $image,
            'keywords' => $keywords,
            'url' =>  $url,
        ];

        $recipeData = [
            "@context" => "https://schema.org/",
            "@type" => "Recipe",
            "name" => "Mseja Local beat",
            "author" => [
                "@type" => "Person",
                "name" => "Elliot Gog"
            ],
            "datePublished" => "2021-05-01",
            "description" => "Best way to sell digital Items with M-Pesa.",
            "prepTime" => "PT20M"
        ];
        $siteData = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => "Genius Works Ent",
            "alternateName" => "GW-ENT",
            "url" => "https://gw-ent.co.za/"
        ];
        return view('songs_by_genre', compact('beatCollection', 'genre', 'recentlyAddedSongs', 'metaTags', 'recipeData', 'siteData'));
    }
    public function genre(Request $request): View
    {
        $search = $request->get('search', '');
        $beat = Beat::search($search)->latest()->paginate(4)->withQueryString();
        $recentlyAddedSongs = Beat::latest()->take(10)->get();
        $genres = Genre::withCount('beat')
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $firstGenre = $genres->first();

        $metaTags = [
            'title' => $firstGenre->title ?? 'Default Title',
            'description' => 'Browse beat by genre hip hop, Local, etv.... ',
            'image' => $firstGenre->image ? Storage::url($firstGenre->image) : '',
        ];

        return view('songs', compact(
            'beat',
            'genres',
            'search',
            'metaTags',
            'recentlyAddedSongs'
        ));
    }


    public function buyBeat(Request $request)
    {
        // Get the beat ID from the request
        $beatId = $request->input('beat_id');

        // Retrieve the beat using the beat ID
        $beat = Beat::find($beatId);

        if (!$beat) {
            return redirect()->back()->with('error', 'beat track not found.');
        }

        if (!Auth::check()) {
            // Store the intended URL in the session
            $intendedUrl = route('beat.slug', ['slug' => urlencode($beat->slug)]);
            Session::put('intended_url', $intendedUrl);
            return redirect()->route('login')->with('error', 'Please log in to buy beat.');
        }

        $user = Auth::user();
        $beatPrice = floatval($beat->amount);

        if ($user->balance >= $beatPrice) {
            // Deduct the beat price from user's balance
            $user->balance -= $beatPrice;
            $user->save();

            // Log successful balance deduction
            Log::info("Balance deducted successfully. User ID: {$user->id}, Beat ID: {$beat->id}");



            $uploaderId = DB::table('beat_user')
                ->where('beat_id', $beat->id)
                ->value('user_id');

            // Retrieve the uploader
            $uploader = User::find($uploaderId);

            // Notify the uploader about the purchase
            if ($uploader) {
                $buyer = Auth::user(); // Assuming the buyer is the authenticated user
                $uploader->notify(new BeatDownloadNotification($beat, $buyer));
            }
            $beatz = new Beatz([
                'user_id' => $user->id,
                'beat_id' => $beat->id,
                'uploader_id' => $uploaderId,
                'artist' => $beat->artist,
                'title' => $beat->title,
                'image' => $beat->image,
                'file' => $beat->file,
                'description' => $beat->description,
                'duration' => $beat->duration,
                'size' => $beat->size,
                'used' => 0,
                // Other fields...
            ]);

            $beatz->save();
            // Generate the download link
            $downloadLink = route('download-beat', ['beat_id' => $beat->id]);

            // Store the download link in a session variable
            session(['downloadLink' => $downloadLink]);

            // Redirect to purchased-beats route after successful purchase
            return redirect()->route('purchased-beatz')->with('success', 'beat track purchased.');
        } else {
            Log::error("Insufficient funds for user ID: {$user->id}, Beat ID: {$beat->id}");

            return redirect()->back()->with('error', 'Insufficient funds.');
        }
    }


    public function downloadbeat($beat_id)
    {
        try {
            $user = Auth::user();
            $beat = Beatz::find($beat_id);


            if (!$beat) {
                return redirect()->back()->with('error', 'Beat track not found.');
            }

            // Check if the user has purchased the beat
            if (!$user->purchasedbeat->contains('id', $beat->id)) {
                return redirect()->back()->with('error', 'You have not purchased this Beat.');
            }

            // Increment the 'used' field
            $beatz = Beat::firstOrFail(); // Corrected the method call
            if ($beatz->used !== 1) {
                $beatz->used += 1;
                $beatz->save();
            }

            $beatFilePath = $beat->file;

            // Check if the beat file exists
            if (!Storage::exists($beatFilePath)) {
                return redirect()->back()->with('error', 'Beat file not found.');
            }

            // Format the file name
            $fileName = Str::slug($beat->title . '-' . $beat->artist) . '.mp3';

            // Download the beat file
            return Storage::download($beatFilePath, $fileName);
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error in downloadbeat method: ' . $e->getMessage());

            // You can redirect the user to an error page or handle it in another way
            return redirect()->route('error-page')->with('error', 'An error occurred.');
        }
    }



    public function clearDownloadLink(Request $request)
    {
        $request->session()->forget('downloadLink');
    }

    public function purchasedBeats(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $user = Auth::user();

            // Retrieve purchased beats
            $purchasedbeats = $user->purchasedBeat()
                ->orderByDesc('created_at')
                ->paginate(10);

            // Retrieve setting
            $setting = Setting::firstOrFail();

            // Meta tags
            $image = asset('storage/og-tag.jpg');
            $keywords = "GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG";

            $metaTags = [
                'title' => $setting->site,
                'description' => $setting->description,
                'image' => $image,
                'keywords' => $keywords,
                'url' => config('app.url'),
            ];

            return view('purchased-beatz', compact('purchasedbeats', 'metaTags', 'user', 'search'));
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error in purchasedBeats method: ' . $e->getMessage());

            // You can redirect the user to an error page or handle it in another way
            return redirect()->route('error-page')->with('error', 'An error occurred.');
        }
    }



    public function showPurchasedbeat()
    {
        try {
            $user = Auth::user();
            $purchasedbeat = $user->purchasedBeat()
                ->orderByDesc('created_at') // Order by creation date in descending order
                ->paginate(10); // Paginate with 10 items per page

            $beat = Beat::firstOrFail();

            $metaTags = $this->generateMetaTags($beat);

            return view('purchased-beatz', compact('purchasedbeat', 'metaTags'));
        } catch (\Exception $e) {
            // Log or display the error for debugging
            return "An error occurred: " . $e->getMessage();
        }
    }




    public function search(Request $request)
    {
        $searchText = $request->input('search_text');

        if ($searchText === null) {
            $results = [];
        } else {
            $results = ($searchText === '')
                ? [] // Handle the case when the search input is empty
                : Beat::searchLatestPaginated($searchText)->get();
        }

        return view('search_results', ['results' => $results]);
    }

    public function downloadDemo($beatId)
    {
        $beat = Beat::find($beatId);

        if (!$beat) {
            return redirect()->back()->with('error', 'beat track not found.');
        }

        $beatFilePath = $beat->demo;

        if (!Storage::exists($beatFilePath)) {
            return redirect()->back()->with('error', 'beat file not found.');
        }

        return Storage::download($beatFilePath, $beat->title . '.mp3');
    }
}
