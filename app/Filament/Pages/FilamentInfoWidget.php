<?php

namespace App\Filament\Pages;
use Filament\Widgets\Widget as BaseWidget;
class FilamentInfoWidget extends BaseWidget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'user-widget';
}
