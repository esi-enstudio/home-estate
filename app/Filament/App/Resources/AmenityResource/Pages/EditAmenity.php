<?php

namespace App\Filament\App\Resources\AmenityResource\Pages;

use App\Filament\App\Resources\AmenityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAmenity extends EditRecord
{
    protected static string $resource = AmenityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
