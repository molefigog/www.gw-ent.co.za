<?php

namespace App\Filament\Resources\BeatResource\Pages;

use App\Filament\Resources\BeatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeat extends ListRecords
{
    protected static string $resource = BeatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
