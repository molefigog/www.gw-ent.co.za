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
use Livewire\Component;
use Livewire\WithPagination;

class MusicSection extends Component
{
    use WithPagination;

protected $paginationTheme = 'bootstrap';

    public $search;

    public $music;


    public function incrementLikes(Request $request, $musicId)
    {
        $this->music = Music::findOrFail($musicId);
        $this->music->increment('likes');
        $this->music->save();
    }

    public function render()
    {

        $allMusic = Music::latest()->where('artist', 'like', "%{$this->search}%")->paginate(15);
        $products = Product::latest()->paginate(10)->withQueryString();

        $downloads = Music::where('downloads', '>', 0)
            ->orderBy('downloads', 'desc')
            ->take(6)
            ->get();
        $genres = Genre::withCount('music')
            ->latest()
            ->paginate(10) // You might want to adjust the pagination as needed
            ->withQueryString();

            return view('livewire.music-section', [
                'allMusic' => $allMusic,
                'products' => $products,
                'downloads' => $downloads,
                'genres' => $genres
            ]);
    }
}
