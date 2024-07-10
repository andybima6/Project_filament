<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class staatistik extends BaseWidget
{
    protected function getStats(): array
    {
        return [
             stat::make('New Users',User::count())
             ->description('New Users that have joined')
             ->descriptionIcon('heroicon-m-user-plus',IconPosition::Before)
             ->chart([1,3,5,10,20,40])
             ->color('success')
        ];
    }
}
