<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{

    public ?User $record = null;

    protected function getStats(): array
    {
        return [
            Stat::make('name',$this->record->name ?? 'No Selected Users'),
            Stat::make('Num Posts',$this->record->posts()->count() ?? 0)
        ];
    }
}
