<?php

namespace App\Filament\Pages;

use App\Settings\HomeBannerSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepageBanner extends SettingsPage
{
    protected static string $settings = HomeBannerSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Banner';
    protected static ?int $navigationSort = 1;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('badge_text')->label('Badge Text'),

            Section::make('শিরোনাম (Title)')
                ->description('শিরোনামটি দুটি অংশে বিভক্ত। হাইলাইট করা অংশটি ভিন্ন রঙে দেখানো হবে।')
                ->schema([
                    TextInput::make('title_main')
                        ->label('প্রধান শিরোনাম (Main Title)')
                        ->placeholder("World's Largest Property Listing site for"),

                    TextInput::make('title_highlighted')
                        ->label('হাইলাইট করা অংশ (Highlighted Part)')
                        ->placeholder('Rental, Buy & Sell...'),
                ])->columns(2),

            Textarea::make('subtitle')->label('Subtitle'),
            TextInput::make('button_text')->label('Button Text'),
            TextInput::make('button_link')->label('Button Link'),
        ]);
    }
}
