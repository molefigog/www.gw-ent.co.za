<?php

namespace App\Filament\Resources\MusicResource\Pages;

use App\Filament\Resources\MusicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Music;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Select;

class EditMusic extends EditRecord
{


    protected static string $resource = MusicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
