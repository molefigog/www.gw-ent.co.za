<?php
namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\RegistrationResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class RegistrationResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        Log::info('RegistrationResponse toResponse called');
        $intendedUrl = session('intended_url');

        if ($intendedUrl) {
            // Clear the intended URL from the session
            session()->forget('intended_url');
            return redirect()->to($intendedUrl); // Ensure this returns a RedirectResponse
        }

        return redirect()->intended(url('/')); // This should already be correct
    }
}
