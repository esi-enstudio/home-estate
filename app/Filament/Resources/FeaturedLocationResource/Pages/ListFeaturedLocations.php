<?php

namespace App\Filament\Resources\FeaturedLocationResource\Pages;

use App\Filament\Resources\FeaturedLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeaturedLocations extends ListRecords
{
    protected static string $resource = FeaturedLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
