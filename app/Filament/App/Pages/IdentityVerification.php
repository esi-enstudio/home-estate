<?php

namespace App\Filament\App\Pages;

use App\Filament\Resources\UserResource;
use App\Jobs\NotifyAdminsOfIdentitySubmission;
use App\Models\User;
use App\Notifications\IdentityDocumentSubmitted;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class IdentityVerification extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static string $view = 'filament.app.pages.identity-verification';
    protected static ?string $navigationLabel = 'পরিচয়পত্র ভেরিফিকেশন';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        $user = auth()->user();
        return [
            Section::make('আপনার ভেরিফিকেশন স্ট্যাটাস')
                ->schema([
                    Placeholder::make('status')
                        ->label('')
                        ->content(function () use ($user) {
                            $status = $user->identity_status;
                            $color = match ($status) {
                                'approved' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger',
                                default => 'gray',
                            };
                            return new HtmlString("<span class='text-lg font-bold text-{$color}-500'>" . ucfirst($status) . "</span>");
                        }),

                    Placeholder::make('rejection_reason')
                        ->label('প্রত্যাখ্যানের কারণ')
                        ->content($user->identity_rejection_reason)
                        ->visible($user->identity_status === 'rejected'),
                ]),

            Section::make('ডকুমেন্ট আপলোড করুন')
                ->description('আপনার পাসপোর্ট, জন্ম সনদ অথবা জাতীয় পরিচয়পত্র (NID) এর উভয় পাশের ছবি আপলোড করুন।')
                ->schema([
                    FileUpload::make('documents')
                        ->label('')
                        ->multiple()->maxFiles(2)->reorderable()
                        ->image()->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png'])
                        ->helperText('সর্বোচ্চ ২টি ছবি আপলোড করা যাবে (প্রতিটি ২ মেগাবাইটের নিচে)।')
                        ->required(),
                ])
                // এই সেকশনটি শুধুমাত্র তখনই দেখা যাবে যখন স্ট্যাটাস 'approved' বা 'pending' নয়
                ->visible(!in_array($user->identity_status, ['approved', 'pending'])),
        ];
    }

    public function submit(): void
    {
        $user = auth()->user();
        $data = $this->form->getState();

        foreach ($data['documents'] as $document) {
            if ($document instanceof TemporaryUploadedFile) {
                $user->addMedia($document->getRealPath())
                    ->toMediaCollection('identity_documents');
            }
        }

        $user->update(['identity_status' => 'pending', 'identity_rejection_reason' => null]);

        // সরাসরি নোটিফিকেশন না পাঠিয়ে, জবটিকে queue-তে পাঠানো হচ্ছে
        NotifyAdminsOfIdentitySubmission::dispatch($user->id);

        Notification::make()->title('আপনার ডকুমেন্টস সফলভাবে জমা হয়েছে।')->success()->send();
        $this->redirect(static::getUrl());
    }
}
