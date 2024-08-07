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
        return redirect()->route('gee');
    }
}
