<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class staatistik extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {

    // mendefinisikan  nilainya tidak null
        $start = $this->filters['startDate'] ;
        $end = $this->filters['endDate'] ;

        return [
            stat::make(
                'New Users',
                User::
                when($start,
                fn ($query)=> $query->whereDate('created_at','>',$start))

                ->when($end,
                fn ($query)=> $query->whereDate('created_at','<',$end))

                ->count())
                ->description('New Users that have joined')
                ->descriptionIcon('heroicon-m-user-plus', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('success')
        ];
    }
}
