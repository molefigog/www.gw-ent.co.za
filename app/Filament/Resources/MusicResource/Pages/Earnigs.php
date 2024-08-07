<?php

namespace App\Filament\Resources\MusicResource\Pages;

use App\Filament\Resources\MusicResource;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class Earnigs extends Page implements HasTable
{
        use InteractsWithTable;
    
    protected static string $resource = MusicResource::class;

    protected static string $view = 'filament.resources.music-resource.pages.earnigs';
    
    protected static ?string $navigationIcon = 'heroicon-o-musical-note';
    protected static ?string $navigationGroup = 'Products';
    
    public static function getPages(): array
   {
    return [
        // ...
        'earnings' => Pages\Earnings::route('/earnings'),
    ];
    }
}
