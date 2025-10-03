<?php

namespace App\Filament\Pages;

use App\Settings\HomepageSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepage extends SettingsPage
{
    protected static string $settings = HomepageSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Homepage';
    protected static ?int $navigationSort = 1;

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
