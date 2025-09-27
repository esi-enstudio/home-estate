<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
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

        // ৩. অথেন্টিকেশন সফল! এখন রোল চেক করুন
        $user = Auth::guard('web')->user();

        if ($user->roles->isEmpty()) {
            // ক. ব্যবহারকারীকে আবার লগআউট করে দিন
            Auth::guard('web')->logout();

            // খ. ভ্যালিডেশন এরর থ্রো করুন
            throw ValidationException::withMessages([
                'data.phone' => 'You do not have any roles assigned. Please contact support.',
            ]);
        }

        // ৪. রোল আছে! সেশন রিজেনারেট করুন
        session()->regenerate();

        // ৫. সফল লগইনের জন্য ফিলামেন্টের ডিফল্ট রেসপন্স রিটার্ন করুন
        return app(LoginResponse::class);
    }
}
