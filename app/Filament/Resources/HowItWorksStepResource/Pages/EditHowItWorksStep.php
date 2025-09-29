<?php

namespace App\Filament\Resources\HowItWorksStepResource\Pages;

use App\Filament\Resources\HowItWorksStepResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHowItWorksStep extends EditRecord
{
    protected static string $resource = HowItWorksStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
