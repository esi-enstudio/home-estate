<?php

namespace App\Filament\Resources\UserVerificationResource\Pages;

use App\Filament\Resources\UserVerificationResource;
use EightyNine\Approvals\Models\ApprovableModel;
use EightyNine\Approvals\Traits\HasApprovalHeaderActions;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditUserVerification extends EditRecord
{
    use HasApprovalHeaderActions;

    protected static string $resource = UserVerificationResource::class;

    // অপশনাল: অ্যাপ্রুভাল কমপ্লিট হলে কাস্টম অ্যাকশন ডিফাইন করুন
    protected function getOnCompletionAction(): Action
    {
        return Action::make('ভেরিফাইড সম্পূর্ণ')  // বাটনের লেবেল
        ->color('success')  // সাকসেস কালার
        ->action(function (ApprovableModel $record) {
            // অ্যাপ্রুভাল কমপ্লিট হলে ইউজারকে ভেরিফাইড করুন
            $record->user->update(['verified' => true]);

            // অপশনাল: নোটিফিকেশন পাঠান (Laravel Notification ব্যবহার করে)
            // $record->user->notify(new VerificationApprovedNotification());

            // অথবা কাস্টম লজিক যোগ করুন, যেমন লগিং
            // \Log::info('UserVerification approved for user ID: ' . $record->user_id);
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // অপশনাল: অ্যাপ্রুভাল প্রসেস কাস্টমাইজ করুন (যদি প্রয়োজন হয়)
    protected function afterSave(): void
    {
        // এডিট সেভ হলে অ্যাপ্রুভাল ফ্লো রিস্টার্ট করুন যদি প্রয়োজন হয়
        if ($this->record->wasChanged()) {
            $this->record->submitForApproval();  // অপশনাল: পরিবর্তন হলে পুনরায় সাবমিট
        }
    }
}
