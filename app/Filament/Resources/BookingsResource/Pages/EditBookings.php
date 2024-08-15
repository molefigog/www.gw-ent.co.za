<?php

namespace App\Filament\Resources\BookingsResource\Pages;

use App\Filament\Resources\BookingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookings extends EditRecord
{
    protected static string $resource = BookingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
