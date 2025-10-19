<?php

namespace App\Filament\Resources\IdentityVerificationResource\Pages;

use App\Filament\Resources\IdentityVerificationResource;
use App\Models\IdentityVerification;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewIdentityVerification extends ViewRecord
{
    protected static string $resource = IdentityVerificationResource::class;

    /**
     * পেজের উপরের ডানদিকে অ্যাকশন বাটনগুলো যোগ করার জন্য এই মেথডটি ব্যবহার করা হয়।
     */
    protected function getHeaderActions(): array
    {
        return [
            // Approve বাটন
            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->action(function (IdentityVerification $record) {
                    $record->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'rejection_reason' => null,
                    ]);

                    // সঠিক পদ্ধতিতে নোটিফিকেশন পাঠানোর কোড
                    Notification::make()
                        ->title('User identity has been approved.')
                        ->success()
                        ->send();

                    // কাজটি শেষ হলে লিস্ট পেজে রিডাইরেক্ট করা হবে
                    return redirect(static::getResource()::getUrl('index'));
                })
                // শুধুমাত্র 'pending' স্ট্যাটাসের জন্য এই বাটনটি দেখা যাবে
                ->visible(fn (IdentityVerification $record) => $record->status === 'pending'),

            // Reject বাটন
            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Reason for Rejection')
                        ->required(),
                ])
                ->action(function (IdentityVerification $record, array $data) {
                    // নিচের update() মেথডটি দেখুন
                    $record->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejection_reason' => $data['rejection_reason'],
                        // ছবির পাথ মুছে ফেলার লাইনগুলো সরিয়ে দেওয়া হয়েছে
                    ]);

                    // সঠিক পদ্ধতিতে নোটিফিকেশন পাঠানোর কোড
                    Notification::make()
                        ->title('User identity has been rejected.')
                        ->warning() // রিজেক্ট করার জন্য warning ব্যবহার করা ভালো
                        ->send();

                    return redirect(static::getResource()::getUrl('index'));
                })
                ->visible(fn (IdentityVerification $record) => $record->status === 'pending'),
        ];
    }
}
