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

class Mmino extends Component
{
    use WithPagination;
    #[Title('Genius Works Ent')]
    protected $paginationTheme = 'bootstrap';

    public $search = "";

    public $music;
    public $products;
    public $downloads;
    public $genres;


    public function incrementLikes(Request $request, $musicId)
    {
        $this->music = Music::findOrFail($musicId);
        $this->music->increment('likes');
        $this->music->save();
    }

    public function render()
    {
        $allMusic = Music::latest()->paginate(18);
        $products = Product::latest()->paginate(10)->withQueryString();



            return view('livewire.mmino', [
                'allMusic' => $allMusic,
                'products' => $products,


            ]);
    }
}
