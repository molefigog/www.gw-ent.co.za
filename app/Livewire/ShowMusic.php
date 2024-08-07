<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Music;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

class ShowMusic extends Component
{
    public $slug;
    public $music;
    public $metaTags = [];
    public $shareButtons;
    public $userId;
    public $Paypal;
    public $PAYPAL_ID;
    public $currency;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadMusicData();
    }

    #[Title('title')]
    public function loadMusicData()
    {
        $music = Music::where('slug', $this->slug)->firstOrFail();
        $music->increment('views');
        $this->music = $music;

        $artist = $music->artist;
        $title = $music->title;
        $image = url($music->image ? Storage::url($music->image) : '');
        $description = $music->description;
        $baseUrl = 'https://www.gw-ent.co.za';
        $url = "{$baseUrl}/msingle/" . urlencode($music->slug);

        $this->shareButtons = \Share::page($url, 'Check out this music: ' . $music->title)
            ->facebook()
            ->twitter()
            ->whatsapp();

        $intendedUrl = route('msingle.slug', ['slug' => urlencode($this->slug)]);
        Session::put('intended_url', $intendedUrl);

        $this->userId = Auth::check() ? Auth::user()->id : null;
        $this->Paypal = config('paypal.paypal_url');
        $this->PAYPAL_ID = config('paypal.paypal_id');
        $this->currency = config('paypal.paypal_currency');

        $this->metaTags = [
            'title' => $title,
            'image' => $image,
            'description' => $description,
            'keywords' => $artist . ', ' . $title,
            'url' => $url,
        ];

        // $this->dispatchBrowserEvent('title-updated', ['title' => $title]);
    }

    public function render()
    {
        return view('livewire.show-music');
    }
}
