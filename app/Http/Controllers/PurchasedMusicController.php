<?php

namespace App\Http\Controllers;
// app/Http/Controllers/PurchasedMusicController.php

use App\Models\User;
use App\models\Items;
use App\Models\Music;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class PurchasedMusicController extends Controller
{

    public function index()
    {
        // Retrieve the authenticated user
        $user = auth()->user();

        // Retrieve songs for the authenticated user
        $songs = $user->music;

        return view('sales', compact('songs'));
    }
} 
