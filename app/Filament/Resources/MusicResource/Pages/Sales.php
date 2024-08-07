<?php

namespace App\Filament\Resources\MusicResource\Pages;

use App\Filament\Resources\MusicResource;
use Filament\Resources\Pages\Page;

class Sales extends Page
{
    protected static string $resource = MusicResource::class;

    protected static string $view = 'filament.resources.music-resource.pages.sales';
    
    public static function getPages(): array
{
    return [
        // ...
        'sort' => Pages\Sales::route('/sale'),
    ];
}
}
