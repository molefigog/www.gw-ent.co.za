<?php

namespace App\Filament\Widgets;

use App\Models\Music;
use App\Models\User;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Function to get monthly increase data
        $getMonthlyIncrease = function ($model) {
            $monthlyCounts = $model::select(DB::raw('COUNT(*) as count'), DB::raw('MONTH(created_at) as month'))
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month')
                ->toArray();

            $chartData = [];
            $previousCount = 0;

            for ($i = 1; $i <= 12; $i++) {
                $currentCount = $monthlyCounts[$i] ?? 0;
                $increase = $currentCount - $previousCount;
                $chartData[] = $increase;
                $previousCount = $currentCount;
            }

            return $chartData;
        };

        // Get the monthly increase data for each model
        $musicChartData = $getMonthlyIncrease(Music::class);
        $userChartData = $getMonthlyIncrease(User::class);
        $productChartData = $getMonthlyIncrease(Product::class);

        return [
            Stat::make('Total Songs', Music::count())
                ->description('increase in music')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($musicChartData),
            Stat::make('Total Users', User::count())
                ->description('increase in users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($userChartData),
            Stat::make('Total Products', Product::count())
                ->description('increase in products')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart($productChartData),
        ];
    }
}

