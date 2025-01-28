<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Mmino extends Component
{
    use WithPagination;
    #[Title('Genius Works Ent')]
    protected $paginationTheme = 'bootstrap';

    public $search = "";

    public $tab = 'music'; // Default tab
    public $music;
    public $downloads;
    public $genres;
    public $page;
    public $url;

    public function incrementLikes(Request $request, $musicId)
    {
        $this->music = Music::findOrFail($musicId);
        $this->music->increment('likes');
        $this->music->save();
    }
    public function switchTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage(); // Reset pagination when switching tabs
    }


    public function render()
    {
        // Fetch all music excluding beats
        $allMusic = Music::where('publish', true)
            ->where('beat', false)
            ->latest()
            ->paginate(18);

        // Fetch beats only
        $beats = Music::where('publish', true)
            ->where('beat', true)
            // ->where('sold', false)
            ->latest()
            ->paginate(18);


        return view('livewire.mmino', [
            'allMusic' => $allMusic,
            'beats' => $beats,
            'page' => $this->page, // Pass the active tab to the view
        ]);
    }
}
