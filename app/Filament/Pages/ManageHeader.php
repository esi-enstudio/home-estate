<?php

namespace App\Filament\Pages;

use App\Settings\HeaderSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHeader extends SettingsPage
{
    protected static string $settings = HeaderSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Header';
    protected static ?int $navigationSort = 2;

    // === START: আরও সুন্দর এবং ব্যবহারকারী-বান্ধব লেবেল ===
    protected static ?string $title = 'হেডার সেটিংস';
    // === END ===

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('লোগো')
                ->description('ওয়েবসাইটের লাইট এবং ডার্ক মোডের জন্য লোগো আপলোড করুন।')
                ->schema([
                    FileUpload::make('light_logo')->label('Light Mode Logo')->image()->columnSpan(1),
                    FileUpload::make('dark_logo')->label('Dark Mode Logo')->image()->columnSpan(1),
                ])->columns(2),

            Section::make('বাটন')
                ->description('হেডারের লগইন এবং রেজিস্টার বাটনের লেখা পরিবর্তন করুন।')
                ->schema([
                    TextInput::make('signin_button_text')->label('Sign In Button Text'),
                    TextInput::make('register_button_text')->label('Register Button Text'),
                ])->columns(2),
        ]);
    }
}
