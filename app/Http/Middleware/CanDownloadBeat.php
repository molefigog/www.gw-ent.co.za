<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Beat;

class CanDownloadBeat
{
    public function handle($request, Closure $next)
    {
        $beatId = $request->route('beat_id');
        $beat = Beat::find($beatId);

        if (!$beat) {
            return redirect()->back()->with('error', 'Beat track not found.');
        }

        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please log in to download beat.');
        }
        $user = Auth::user();
        // Check if the user has purchased the beat
        if (!$user->purchasedBeat->contains('id', $beatId)) {
            return redirect()->back()->with('error', 'You have not purchased this song.');
        }

        return $next($request);
    }
}
