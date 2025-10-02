<?php

namespace App\Filament\Pages;

use App\Settings\HomepageSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static string $settings = HomepageSettings::class;
    protected static ?string $navigationGroup = 'হোমপেজ ম্যানেজমেন্ট';
    protected static ?string $navigationLabel = 'ব্যানার সেকশন';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('badge_text')->label('Badge Text'),
            TextInput::make('title')->label('Title')->required(),
            Textarea::make('subtitle')->label('Subtitle'),
            TextInput::make('button_text')->label('Button Text'),
            TextInput::make('button_link')->label('Button Link'),
        ]);
    }
}
