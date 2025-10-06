<?php

namespace App\Filament\App\Resources\EnquiryResource\Pages;

use App\Filament\App\Resources\EnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnquiry extends EditRecord
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
