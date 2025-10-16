<?php

namespace App\Filament\Resources\UserVerificationResource\Pages;

use App\Filament\Resources\UserVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserVerifications extends ListRecords
{
    protected static string $resource = UserVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
