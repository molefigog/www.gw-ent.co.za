<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Beat;
use App\Models\Music;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Genre;
use App\Models\User;
use App\Models\Downloads;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    public function index()
    {
        return view('live-search');
    }
    public function getDownloads()
    {
        return view('otpdownloads');
    }

    public function liveSearch(Request $request)
    {
        $query = $request->input('query');

        $results = Music::where('artist', 'like', '%' . $query . '%')->get();

        return response()->json($results);
    }
    // public function landingPage(Request $request): View
    // {
    //     $allMusic = Music::latest()->paginate(15)->withQueryString();
    //     $products = Product::latest()->paginate(10)->withQueryString();
    //     $downloads = Music::where('downloads', '>', 0)
    //         ->orderBy('downloads', 'desc')
    //         ->take(10)
    //         ->get();
    //     $genres = Genre::withCount('music')
    //         ->latest()
    //         ->paginate(10) // You might want to adjust the pagination as needed
    //         ->withQueryString();


    //     return view('music', compact('allMusic', 'products', 'downloads', 'genres'));
    // }


    public function songsPage(Request $request): View
    {
        $search = $request->get('search', '');

        // Fetch all music with search filter
        $music = Music::latest()->paginate(10)->withQueryString();
        $genres = Genre::withCount('music')->get();
        $genre = Genre::firstOrFail();
        $setting = Setting::firstOrFail();
        $appName = config('app.name');
        $url = config('app.url');

        $title = $setting ? $setting->site : $appName;
        $image = asset("storage/$setting->image");
        $keywords = "GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG";

        $metaTags = [
            'title' => $setting->site,
            'description' => $setting->description,
            'image' =>  $image,
            'keywords' => $keywords,
            'url' =>  $url,
        ];

        return view('songs', compact('music',  'metaTags', 'genres'));
    }
    public function beatsPage(Request $request): View
    {

        $search = $request->get('search', '');
        $beats = Beat::latest()->paginate(15)->withQueryString();
        $products = Product::latest()->paginate(10)->withQueryString();
        $recentlyAddedSongs = Beat::latest()->take(10)->get();
        $downloads = Music::where('downloads', '>', 0)
            ->orderBy('downloads', 'desc')
            ->take(10)
            ->get();
        $genres = Genre::withCount('music')
            ->latest()
            ->paginate(10) // You might want to adjust the pagination as needed
            ->withQueryString();
        $setting = Setting::firstOrFail();
        $appName = config('app.name');
        $url = config('app.url');

        $title = $setting ? $setting->site : $appName;
        $image = asset("storage/$setting->site");
        $keywords = "GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG";

        $metaTags = [
            'title' => $setting->site,
            'description' => $setting->description,
            'image' =>  $image,
            'keywords' => $keywords,
            'url' =>  $url,
        ];


        return view('beatz', compact('beats', 'products', 'downloads', 'metaTags', 'genres'));
    }
    public function songsByArtist($artistName)
    {
        $artist = User::where('name', $artistName)->firstOrFail();

        $musicCollection = DB::table('music_user')
            ->where('user_id', $artist->id)
            ->join('music', 'music_user.music_id', '=', 'music.id')
            ->latest('music.created_at')
            ->paginate(10);
        $downloads = Music::where('downloads', '>', 0)
            ->orderBy('downloads', 'desc')
            ->take(10)
            ->get();
        $genres = Genre::withCount('music')
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $setting = Setting::firstOrFail();
        $appName = config('app.name');
        $url = config('app.url');

        $keywords = "GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG";

        $musicCount = $musicCollection->total();
        $image = null;
        if ($musicCollection->isNotEmpty()) {
            $lastMusic = $musicCollection->last();
            $image = asset('storage/' . $lastMusic->image);
        }
        return view('songs_by_artist', compact('musicCollection', 'artist', 'shareButtons', 'downloads', 'metaTags'));
    }

    public function sitemap()
    {
        $sitemap = Sitemap::create();
        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create("/products/{$product->slug}"));
        });
        $latestAboutPost = Product::where('category_name', 'About')->orderBy('created_at', 'desc')->first();
        if ($latestAboutPost) {
            $sitemap->add(Url::create("/about")->setLastModificationDate($latestAboutPost->updated_at));
        }
        Music::all()->each(function (Music $music) use ($sitemap) {
            $sitemap->add(Url::create("/msingle/{$music->slug}"));
        });
        $sitemap->writeTofile(public_path('sitemap.xml'));
        return redirect()
            ->route('gee')
            ->withSuccess(__('sitemap created!!'));
    }

    public function otpDownloads(Request $request)
    {
        $otp = $request->input('otp');
        $downloads = Downloads::where('otp', $otp)->first();

        if ($downloads) {
            return response()->json(['success' => true, 'otp' => $downloads->title, 'fromNumber' => $downloads->mobile]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getDl(Request $request)
    {
        $otp = $request->input('otp');
        $download = Downloads::where('otp', $otp)->first();

        if ($download) {
            // Assuming 'file' column contains the file path
            $filePath = public_path('storage/' . $download->file);

            if (file_exists($filePath)) {
                return response()->download($filePath);
            } else {
                Log::error('File not found for OTP: ' . $otp);
                return redirect()->back()->with('error', 'File not found.');
            }
        } else {
            Log::error('Invalid OTP: ' . $otp);
            return redirect()->back()->with('error', 'Invalid OTP.');
        }
    }

}
