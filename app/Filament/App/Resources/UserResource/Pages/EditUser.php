<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Filament\App\Resources\UserResource;
use App\Filament\Resources\UserVerificationResource;
use App\Models\UserVerification;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ভেরিফিকেশন সাবমিট বাটন
            Action::make('submit_verification')
                ->label('ভেরিফিকেশন সাবমিট')
                ->color('primary')
                ->url(fn () => UserVerificationResource::getUrl('create')) // CreateUserVerification পেজে রিডাইরেক্ট
                ->visible(function () {
                    // শুধুমাত্র লগইন করা ইউজার এবং ভেরিফাইড না হলে দেখাবে
                    $user = auth()->user();
                    return $user && !$user->verified && !UserVerification::where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->exists();
                })
                ->disabled(fn () => auth()->user()->verified), // ভেরিফাইড হলে ডিসেবল

            Actions\DeleteAction::make(),
        ];
    }
}
