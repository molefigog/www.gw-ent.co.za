<?php

namespace App\Http\Controllers;


use getID3;
use App\Models\Music;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MusicStoreRequest;
use App\Http\Requests\MusicUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Genre;
use App\Models\User;
use App\Models\Items;
use App\Models\Setting;
use Illuminate\Support\Str;
use falahati\PHPMP3\MpegAudio;
use App\Mail\MusicDownloadNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;


class MusicController extends Controller
{

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $genreFilter = $request->get('genre', '');

        if (Auth::user()->role == 1) {
            $allMusic = Music::latest()
                ->paginate(10)
                ->withQueryString();
            $userMusic = collect();
        } else {
            $user = Auth::user();
            $userMusic = $user->music()
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $allMusic = collect();
        }
        // Fetch distinct genres from the Music model
        $genres = Music::distinct('genre')->pluck('genre_id')->toArray();
        // $recentlyAddedSongs = Music::latest()->take(10)->get();
        // $downloads = Music::orderBy('downloads', 'desc')->take(10)->distinct()->get();
        return view('all_music.index', compact('allMusic', 'userMusic', 'search', 'genres'));
    }

    public function create(Request $request): View
    {
        $genres = genre::all();
        return view('all_music.create', compact('genres'));
    }

    public function store(MusicStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = rand(100, 9999) . $imageFile->getClientOriginalName();
                $validated['image'] = $imageFile->storeAs('public', $imageName);
            }

            $inputFile = $request->file('file');
            $inputFilePath = $inputFile->store('mp3');

            $trimmedFilePath = tempnam(sys_get_temp_dir(), 'trimmed_mp3_');
            MpegAudio::fromFile(Storage::path($inputFilePath))
                ->trim(13, 29)
                ->saveFile($trimmedFilePath);

            if ($request->hasFile('file')) {
                $musicFile = $request->file('file');
                $musicName = $musicFile->getClientOriginalName();
                $validated['file'] = $musicFile->storeAs('public', $musicName);

                $getID3 = new GetId3();
                $musicInfo = $getID3->analyze($musicFile->getPathname());
                $duration = $musicInfo['playtime_string'];
                $sizeInBytes = $musicInfo['filesize'];

                $sizeInMB = round($sizeInBytes / (1024 * 1024), 2);
                // Add duration and size to the validated data
                $validated['duration'] = $duration;
                $validated['size'] = $sizeInMB;
                // Upload trimmed demo using the same music file
                $demoName = pathinfo($musicName, PATHINFO_FILENAME) . '_demo.mp3';
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
            // Save the music
            $user = Auth::user(); // Get the logged-in user
            $music = new Music($validated);
            $user->music()->save($music);
            // Return a success response
            return response()->json(['status' => 'success', 'msg' => 'MP3 uploaded successfully']);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error uploading MP3 file: ' . $e->getMessage());

            // Return an error response
            return response()->json(['status' => 'error', 'msg' => 'An error occurred while processing the request. Please try again later.'], 500);
        }
    }

    public function showBySlug($slug)
    {
        // $title = urldecode($title); // Decode URL-encoded title
        $music = Music::where('slug', $slug)->firstOrFail();
        $music->increment('views');
        // Get the base URL dynamically
        $artist = $music->artist;
        $title = $music->title;
        $image = url($music->image ? Storage::url($music->image) : '');
        $description = $music->description;
        $baseUrl = config('app.url');
        $url1 = "{$baseUrl}/msingle/{$music->slug}";
        $shareButtons = \Share::page($url1, 'Check out this music: ' . $music->title)
            ->facebook()
            ->twitter()
            ->whatsapp();

        $intendedUrl = route('msingle.slug', ['slug' => $slug]);
        Session::put('intended_url', $intendedUrl);

        $userId = Auth::check() ? Auth::user()->id : null;

        // "https://www.paypal.com/cgi-bin/webscr" live url
        $Paypal = config('paypal.paypal_url');
        $PAYPAL_ID = config('paypal.paypal_id');
        $currency = config('paypal.paypal_currency');
        // $metaTags = $this->generateMetaTags($music);
        $metaTags = [
            'title' => $title,
            'image' => $image,
            'description' => $description,
            'keywords' => $artist . ', ' . $title,
            'url' => $url1,
        ];
        return view('msingle', compact('music', 'metaTags',  'shareButtons', 'userId', 'Paypal', 'PAYPAL_ID', 'currency', 'url1'));
    }

    private function generateMetaTags(Music $music)
    {
        $title = $music->title;
        $image = url($music->image ? Storage::url($music->image) : '');
        $description = $music->description;

        return [
            'title' => $title,
            'image' => $image,
            'description' => $description,
        ];
    }

    public function edit(Request $request, Music $music): View
    {
        $genres = genre::all();

        return view('all_music.edit', compact('music', 'genres'));
    }

    public function update(
        MusicUpdateRequest $request,
        Music $music
    ): RedirectResponse {

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($music->image) {
                Storage::delete($music->image);
            }

            $imageFile = $request->file('image');
            $imageName = $imageFile->getClientOriginalName();
            $validated['image'] = $imageFile->storeAs('public', $imageName);
        }

        if ($request->hasFile('demo')) {
            if ($music->demo) {
                Storage::delete($music->demo);
            }

            $demoFile = $request->file('demo');
            $demoName = $demoFile->getClientOriginalName();
            $validated['demo'] = $demoFile->storeAs('public', $demoName);
        }

        if ($request->hasFile('file')) {
            if ($music->file) {
                Storage::delete($music->file);
            }

            $musicFile = $request->file('file');
            $musicName = $musicFile->getClientOriginalName();
            $validated['file'] = $musicFile->storeAs('public', $musicName);

            $getID3 = new getID3();
            $musicInfo = $getID3->analyze($musicFile->getPathname());
            $duration = $musicInfo['playtime_string'];
            $sizeInBytes = $musicInfo['filesize'];

            $sizeInMB = round($sizeInBytes / (1024 * 1024), 2);
            $validated['duration'] = $duration;
            $validated['size'] = $sizeInMB;
        }
        $music->update($validated);

        return redirect()
            ->route('all-music.edit', $music)
            ->withSuccess(__('saved'));
    }

    public function destroy(Request $request, Music $music): RedirectResponse
    {


        if ($music->image) {
            Storage::delete($music->image);
        }

        if ($music->demo) {
            Storage::delete($music->demo);
        }

        if ($music->file) {
            Storage::delete($music->file);
        }

        $music->delete();

        return redirect()
            ->route('all-music.index')
            ->withSuccess(__('removed'));
    }

    public function songsByGenre($genreName)
    {
        $genre = Genre::where('title', $genreName)->firstOrFail();
        $musicCollection = $genre->music()
            ->latest()
            ->paginate(10);
        $recentlyAddedSongs = Music::latest()->take(10)->get();
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
            "name" => "Mseja Local Music",
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
        return view('songs_by_genre', compact('musicCollection', 'genre', 'recentlyAddedSongs', 'metaTags', 'recipeData', 'siteData'));
    }

    public function genre(Request $request): View
    {
        $search = $request->get('search', '');

        // Fetch all music with search filter
        $allMusic = Music::search($search)->latest()->paginate(4)->withQueryString();

        // Fetch recently added songs
        $recentlyAddedSongs = Music::latest()->take(10)->get();

        // Fetch genres with music counts
        $genres = Genre::withCount('music')
            ->latest()
            ->paginate(10) // You might want to adjust the pagination as needed
            ->withQueryString();

        // Assuming you want to display meta information based on the first genre
        $firstGenre = $genres->first();

        $metaTags = [
            'title' => $firstGenre->title ?? 'Default Title',
            'description' => 'Browse music by genre hip hop, Local, etv.... ',
            'image' => $firstGenre->image ? Storage::url($firstGenre->image) : '',
        ];

        return view('songs', compact(
            'allMusic',
            'genres',
            'search',
            'metaTags',
            'recentlyAddedSongs'
        ));
    }

    public function buyMusic(Request $request)
    {
        // Get the music ID from the request
        $musicId = $request->input('music_id');

        // Retrieve the music using the music ID
        $music = Music::find($musicId);

        if (!$music) {
            return redirect()->back()->with('error', 'Music track not found.');
        }

        if (!Auth::check()) {
            // Store the intended URL in the session
            $intendedUrl = route('msingle.slug', ['slug' => urlencode($music->slug)]);
            Session::put('intended_url', $intendedUrl);
            return redirect()->route('login')->with('error', 'Please log in to buy music.');
        }

        $user = Auth::user();
        $musicPrice = floatval($music->amount);

        if ($user->balance >= $musicPrice) {
            // Deduct the music price from user's balance
            $user->balance -= $musicPrice;
            $user->save();

            // Increment downloads count for the music
            $music->md++;
            $music->downloads++;
            $music->save();

            // Send the download notification email to the uploader


            $uploaderId = DB::table('music_user')
                ->where('music_id', $music->id)
                ->value('user_id');

            // Retrieve the uploader
            $uploader = User::find($uploaderId);

            // Notify the uploader about the purchase
            if ($uploader) {
                $buyer = Auth::user(); // Assuming the buyer is the authenticated user
                $uploader->notify(new MusicDownloadNotification($music, $buyer));
            }
            // Insert purchased music details into purchaseditems table
            $Items = new Items([
                'user_id' => $user->id,
                'music_id' => $music->id,
                'uploader_id' => $uploaderId,
                'artist' => $music->artist,
                'title' => $music->title,
                'image' => $music->image,
                'file' => $music->file,
                'description' => $music->description,
                'duration' => $music->duration,
                'size' => $music->size,
                'downloads' => $music->downloads,
                // Other fields...
            ]);

            $Items->save();
            // Generate the download link
            $downloadLink = route('download-music', ['music_id' => $music->id]);

            // Store the download link in a session variable
            session(['downloadLink' => $downloadLink]);

            // Redirect to purchased-musics route after successful purchase
            return redirect()->route('purchased-musics')->with('success', 'Music track purchased.');
        } else {
            return redirect()->back()->with('error', 'Insufficient funds.');
        }
    }

    public function downloadMusic($musicId)
    {
        $user = Auth::user();
        $music = Music::find($musicId);

        if (!$music) {
            return redirect()->back()->with('error', 'Music track not found.');
        }

        // Check if the user has purchased the music
        if (!$user->purchasedMusic->contains('id', $music->id)) {
            return redirect()->back()->with('error', 'You have not purchased this song.');
        }

        $musicFilePath = $music->file; // Set the value of $musicFilePath

        if (!Storage::exists($musicFilePath)) {
            return redirect()->back()->with('error', 'Music file not found.');
        }

        return Storage::download($musicFilePath, $music->title . '.mp3');
    }

    public function clearDownloadLink(Request $request)
    {
        $request->session()->forget('downloadLink');
    }

    public function purchasedMusics(Request $request)
    {

        $search = $request->get('search', '');
        $user = Auth::user();

        // Assuming 'purchasedMusic' is the correct relationship on the User model
        $purchasedMusics = $user->purchasedMusic()
            ->orderByDesc('created_at')
            ->paginate(10);

        //$metaTags = $this->generateMetaTags($purchasedMusics->first());  Assuming you want metadata for the first purchased music
        return view('purchased-musics', compact('purchasedMusics','user', 'search'));
    }

    public function showPurchasedMusic()
    {
        try {
            $user = Auth::user();
            $purchasedMusic = $user->purchasedMusic()
                ->orderByDesc('created_at') // Order by creation date in descending order
                ->paginate(10); // Paginate with 10 items per page

            $music = Music::firstOrFail();

            $metaTags = $this->generateMetaTags($music);

            return view('purchased-musics', compact('purchasedMusic', 'metaTags'));
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
                : Music::searchLatestPaginated($searchText)->get();
        }

        return view('search_results', ['results' => $results]);
    }

    public function downloadMp3($musicId)
    {
        $music = Music::find($musicId);
        if (!$music) {
            return redirect()->back()->with('error', 'Music track not found.');
        }
        $musicFilePath = $music->file;
        $music->md++;
        $music->downloads++;
        $music->save();
        if (!Storage::exists($musicFilePath)) {
            return redirect()->back()->with('error', 'Music file not found.');
        }
        return Storage::download($musicFilePath, $music->artist . '-' . $music->title . '.mp3');
    }
}
