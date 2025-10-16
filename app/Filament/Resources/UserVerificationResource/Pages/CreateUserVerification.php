<?php

namespace App\Filament\Resources\UserVerificationResource\Pages;

use App\Filament\App\Resources\UserResource;
use App\Filament\Resources\UserVerificationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUserVerification extends CreateRecord
{
    protected static string $resource = UserVerificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // অটোম্যাটিক্যালি লগইন করা ইউজারের ID সেট
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('ভেরিফিকেশন সাবমিটেড')
            ->body('আপনার ভেরিফিকেশন রিকোয়েস্ট পেন্ডিং অবস্থায় আছে।')
            ->success()
            ->sendToDatabase($this->record->user);
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('edit', ['record' => auth()->id()]);
    }
}
