<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidget;
use App\Filament\Widgets\staatistik;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            staatistik:: class,
          UserStatsWidget::class
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            staatistik:: class
        ];
    }
}
