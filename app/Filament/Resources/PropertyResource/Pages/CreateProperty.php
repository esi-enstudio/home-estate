<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    /**
     * Mutate form data before creating the record.
     *
     * @param  array  $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // স্বয়ংক্রিয়ভাবে লগইন করা ইউজারের আইডি সেট করুন
        $data['user_id'] = auth()->id();

        return $data;
    }
}
