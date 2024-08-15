<?php

namespace App\Filament\Resources\BookingsResource\Pages;

use App\Filament\Resources\BookingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Bookings;
class ListBookings extends ListRecords
{
    protected static string $resource = BookingsResource::class;

    protected function getHeaderActions(): array
    {
        $userId = auth()->id();

        // Check if the user already has a record
        $hasRecord = Bookings::where('user_id', $userId)->exists();

        // Only allow creating a new record if the user doesn't have one
        return $hasRecord ? [] : [
            Actions\CreateAction::make(),
        ];
    }
}
