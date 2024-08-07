<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(auth()->user()->role == 1 ? true : false),
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->role == 1 ? true : false;
    }
}
