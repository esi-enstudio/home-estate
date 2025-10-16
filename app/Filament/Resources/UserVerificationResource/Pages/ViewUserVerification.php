<?php

namespace App\Filament\Resources\UserVerificationResource\Pages;

use App\Filament\Resources\UserVerificationResource;
use EightyNine\Approvals\Traits\HasApprovalHeaderActions;
use EightyNine\Approvals\Models\ApprovableModel;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUserVerification extends ViewRecord
{
    use HasApprovalHeaderActions;

    protected static string $resource = UserVerificationResource::class;

    protected function getOnCompletionAction(): Action
    {
        return Action::make('ভেরিফাইড সম্পূর্ণ')
            ->color('success')
            ->action(function (ApprovableModel $record) {
                $record->user->update(['verified' => true]);
            });
    }
}
