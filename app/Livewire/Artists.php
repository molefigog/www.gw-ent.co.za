<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Beat;
use App\Models\Music;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Genre;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Livewire\Component;
use Livewire\WithPagination;

class Artists extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $artistName;
    public $artist;
    public $genres;
    public $setting;
    public $appName;
    public $url;
    public $keywords;
    public $musicCount;
    public $image;
    public $music;
    public $musicCollection;
    public $search = '';  // Add search property

    public function mount($artistName)
    {
        $this->artistName = $artistName;
        $this->loadData($artistName);
    }



    public function loadData($artistName)
    {// Convert hyphens back to spaces
    $decodedArtistName = str_replace('_', ' ', $artistName);

    // Log::info('Decoded Artist Name: ' . $decodedArtistName); // Log the decoded artist name

    // Perform the query with the decoded artist name
    $this->artist = User::where('name', $decodedArtistName)->firstOrFail();

    if (!$this->artist) {
        Log::error('No artist found with name: ' . $decodedArtistName);
        abort(404, 'Artist not found.');
    }
        $query = DB::table('music_user')
            ->where('user_id', $this->artist->id)
            ->join('music', 'music_user.music_id', '=', 'music.id');


        $musicCollection = $query->latest('music.created_at')
            ->paginate(10);

        $this->genres = Genre::withCount('music')
            ->latest()
            ->take(10)
            ->get();

        $this->setting = Setting::firstOrFail();
        $this->appName = config('app.name');
        $this->url = config('app.url');

        $this->keywords = "GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG";

        $this->musicCount = $musicCollection->total();

        if ($musicCollection->isNotEmpty()) {
            $lastMusic = $musicCollection->last();
            $this->image = asset('storage/' . $lastMusic->image);
        }

        $this->musicCollection = $musicCollection->items(); // Convert to array
    }

    public function incrementLikes(Request $request, $musicId)
    {
        $this->music = Music::findOrFail($musicId);
        $this->music->increment('likes');
        $this->music->save();
    }

    public function render()
    {


        return view('livewire.artists', [
            'musicCollection' => $this->musicCollection,
            'artist' => $this->artist,
            'genres' => $this->genres,
            'metaTags' => $this->keywords

        ]);
    }

}
