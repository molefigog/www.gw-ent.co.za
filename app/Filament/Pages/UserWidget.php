<?php

namespace App\Filament\Pages;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\MusicResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
class UserWidget extends BaseWidget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::user-widget';
}
