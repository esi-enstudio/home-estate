<?php

namespace App\Filament\App\Resources\PropertyResource\Pages;

use App\Filament\App\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    /**
     * This method is called after form validation but before the data is saved.
     * আমরা এখানে স্বয়ংক্রিয়ভাবে লগইন করা ইউজারের আইডি যোগ করে দেব।
     *
     * @param array $data The validated form data.
     * @return array The mutated form data.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ফর্মের ডেটার সাথে 'user_id' হিসেবে লগইন করা ইউজারের আইডি যুক্ত করা হচ্ছে
        $data['user_id'] = Auth::id();

        return $data;
    }
}
