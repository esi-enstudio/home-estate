<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if (session()->has('error')) {
            Notification::make()
                ->title(session('error'))
                ->danger()
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('phone')
                ->label('Phone Number')
                ->tel()
                ->required(),

            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'phone' => $data['phone'],
            'password' => $data['password'],
        ];
    }

    /**
     * Authenticate the user and check for roles.
     *
     * @return LoginResponse
     * @throws ValidationException
     */
    public function authenticate(): LoginResponse
    {
        // ১. ফর্ম থেকে ক্রেডেনশিয়ালগুলো নিন
        $data = $this->form->getState();
        $credentials = $this->getCredentialsFromFormData($data);

        // ২. ব্যবহারকারীকে অথেন্টিকেট করার চেষ্টা করুন
        if (! Auth::guard('web')->attempt($credentials, $data['remember'])) {
            // যদি অথেন্টিকেশন ব্যর্থ হয়
            throw ValidationException::withMessages([
                'data.phone' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        // ৩. অথেন্টিকেশন সফল! এখন ইউজার অবজেক্টটি নিন
        $user = Auth::guard('web')->user();

        // ৪. ইউজারের রোল আছে কিনা তা চেক করুন
        if ($user->roles->isEmpty()) {
            // ক. ব্যবহারকারীকে আবার লগআউট করে দিন
            Auth::guard('web')->logout();

            // খ. ভ্যালিডেশন এরর থ্রো করুন
            throw ValidationException::withMessages([
                'data.phone' => 'আপনার অ্যাকাউন্টের জন্য কোনো রোল নির্ধারণ করা হয়নি। অনুগ্রহ করে সাপোর্টে যোগাযোগ করুন।',
            ]);
        }

        // ৫. ইউজারের স্ট্যাটাস 'active' কিনা তা চেক করুন
        if ($user->status !== 'active') {
            // স্ট্যাটাস অনুযায়ী একটি সুন্দর বার্তা তৈরি করুন
            $statusMessage = match ($user->status) {
                'inactive' => 'আপনার অ্যাকাউন্টটি নিষ্ক্রিয় করা হয়েছে। বিস্তারিত জানতে সাপোর্টে যোগাযোগ করুন।',
                'banned'   => 'নীতিমালা লঙ্ঘনের জন্য আপনার অ্যাকাউন্টটি নিষিদ্ধ করা হয়েছে।',
                'pending'  => 'আপনার অ্যাকাউন্টটি এখনো অনুমোদনের জন্য অপেক্ষারত আছে।',
                default    => 'আপনার অ্যাকাউন্টটি সক্রিয় নয়।',
            };

            // ব্যবহারকারীকে আবার লগআউট করে দিন
            Auth::guard('web')->logout();

            // ভ্যালিডেশন এরর থ্রো করুন
            throw ValidationException::withMessages([
                'data.phone' => $statusMessage,
            ]);
        }

        // ৬. সকল চেক সফল! সেশন রিজেনারেট করুন
        session()->regenerate();

        // ৭. সফল লগইনের জন্য ফিলামেন্টের ডিফল্ট রেসপন্স রিটার্ন করুন
        return app(LoginResponse::class);
    }
}
