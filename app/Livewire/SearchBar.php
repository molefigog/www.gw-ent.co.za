<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Music;
class SearchBar extends Component
{

    public $search = "";

    public function render()
    {
        $results = [];
        if(strlen($this->search) >= 1)
        {
            $results = Music::where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('artist', 'like', '%' . $this->search . '%')
                            ->limit(16)
                            ->get();
        }
        return view('livewire.search-bar',[
            'songs' => $results

        ]);
    }
}
