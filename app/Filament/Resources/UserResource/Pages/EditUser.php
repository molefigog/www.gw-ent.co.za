<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->visible(auth()->user()->role == 1),
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);

        // Check if the authenticated user has role 1
        if (auth()->user()->role != 1) {
            // Redirect or throw an unauthorized exception
            abort(403, 'Unauthorized');
        }
    }
}

