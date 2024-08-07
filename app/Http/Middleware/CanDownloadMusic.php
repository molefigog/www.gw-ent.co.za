<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Music;

class CanDownloadMusic
{
    public function handle($request, Closure $next)
    {
        $musicId = $request->route('music_id');
        $music = Music::find($musicId);

        if (!$music) {
            return redirect()->back()->with('error', 'Music track not found.');
        }

        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please log in to download music.');
        }
        $user = Auth::user();
        // Check if the user has purchased the music
        if (!$user->purchasedMusic->contains('id', $musicId)) {
            return redirect()->back()->with('error', 'You have not purchased this song.');
        }

        return $next($request);
    }
}
