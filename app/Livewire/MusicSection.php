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
use Livewire\Attributes\Title;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class MusicSection extends Component
{
    use WithPagination;
    #[Title('Beats')]
    protected $paginationTheme = 'bootstrap';

    public $search = "";

    public $music;
    public $products;
    public $downloads;
    public $genres;
    public $page;

    public function incrementLikes(Request $request, $musicId)
    {
        $this->music = Beat::findOrFail($musicId);
        $this->music->increment('likes');
        $this->music->save();
    }

    public function render()
    {
        $beats = Beat::latest()->paginate(18);
        $products = Product::latest()->paginate(10)->withQueryString();
        $page = 'beats';


            return view('livewire.music-section', [
                'beats' => $beats,
                'products' => $products,
                'page' => $page,

            ]);
    }
}
