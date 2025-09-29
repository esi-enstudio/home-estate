<?php

namespace App\Filament\Resources\FeaturedLocationResource\Pages;

use App\Filament\Resources\FeaturedLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeaturedLocation extends EditRecord
{
    protected static string $resource = FeaturedLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
