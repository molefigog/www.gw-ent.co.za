<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profiles.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profiles.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function temporaryUpload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        // Ensure the directory exists or create it
        Storage::disk('public')->makeDirectory('temp');

        // Store the file in the temporary directory
        $path = $request->avatar->store('temp', 'public');
        $fullPath = Storage::disk('public')->path($path);

        Log::info("File stored at: {$fullPath}");

        return response()->json([
            'path' => $path
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|string']);

        $user = Auth::user();
        $temporaryFilePath = $request->input('avatar');

        // Decode JSON if the path is being sent as a JSON string
        $decodedPath = json_decode($temporaryFilePath, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decodedPath['path'])) {
            $temporaryFilePath = $decodedPath['path'];
        }

        Log::info("Normalized and extracted path: {$temporaryFilePath}");

        if (Storage::disk('public')->exists($temporaryFilePath)) {
            $finalPath = 'avatars/' . basename($temporaryFilePath);
            Storage::disk('public')->move($temporaryFilePath, $finalPath);

            $user->avatar = $finalPath;
            $user->save();

            Log::info("Avatar updated and moved to: {$finalPath}");

            return redirect()->back()->with('success', 'Avatar updated successfully.');
        } else {
            Log::error("Failed to find the temporary file at: {$temporaryFilePath}");
            return redirect()->back()->with('error', 'Failed to find the temporary file.');
        }
    }


}
