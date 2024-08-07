<?php

namespace App\Filament\Resources\BeatResource\Pages;

use App\Filament\Resources\BeatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeat extends EditRecord
{
    protected static string $resource = BeatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
