<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Table Tabs
    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Published' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('published', true);
            }),
            'Unpublished' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('published', false);
            }),
        ];
    }
}
