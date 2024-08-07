<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\MusicResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
class LatestMusic extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(MusicResource::getEloquentQuery())
              ->defaultPaginationPageOption(5)
              ->defaultSort('created_at', 'desc')
            ->columns([
                
                 ImageColumn::make('image')->size(30),
                TextColumn::make('title'),
                TextColumn::make('md'),
                TextColumn::make('amount'),
            ]);
    }
    
}
