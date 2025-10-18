<?php

namespace App\Filament\Resources\IdentityVerificationResource\Pages;

use App\Filament\Resources\IdentityVerificationResource;
use App\Models\IdentityVerification;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

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

                    // সফল হওয়ার পর একটি নোটিফিকেশন দেখানো হবে
                    $this->notify('success', 'User identity has been approved.');

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
                // রিজেক্ট করার কারণ লেখার জন্য একটি ফর্ম দেখানো হবে
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Reason for Rejection')
                        ->required(),
                ])
                ->action(function (IdentityVerification $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejection_reason' => $data['rejection_reason'],
                    ]);

                    $this->notify('success', 'User identity has been rejected.');

                    return redirect(static::getResource()::getUrl('index'));
                })
                // শুধুমাত্র 'pending' স্ট্যাটাসের জন্য এই বাটনটি দেখা যাবে
                ->visible(fn (IdentityVerification $record) => $record->status === 'pending'),
        ];
    }
}
