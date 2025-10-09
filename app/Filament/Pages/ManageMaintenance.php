<?php

namespace App\Filament\Pages;

use App\Settings\MaintenanceSettings;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageMaintenance extends SettingsPage
{
    protected static string $settings = MaintenanceSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Maintenance';
    protected static ?int $navigationSort = 4;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                    Tabs\Tab::make('মেইনটেন্যান্স মোড')
                        ->schema([
                            Section::make('রক্ষণাবেক্ষণ পেজের কন্টেন্ট')
                                ->schema([
                                    TextInput::make('maintenance_title')->label('শিরোনাম (Title)'),
                                    Textarea::make('maintenance_subtitle')->label('উপ-শিরোনাম (Subtitle)'),
                                ])->columns(1),

                            Section::make('সোশ্যাল মিডিয়া লিংক')
                                ->schema([
                                    KeyValue::make('social_links')
                                        ->label('সোশ্যাল প্রোফাইল')
                                        ->keyLabel('প্ল্যাটফর্ম') // যেমন: facebook, twitter
                                        ->valueLabel('প্রোফাইল URL')
                                        ->addActionLabel('নতুন লিংক যোগ করুন')
                                        ->reorderable()
                                        ->columnSpanFull(),
                                ]),
                        ]),

                        Tabs\Tab::make('Coming Soon পেজ')
                            ->schema([
                                Section::make('পেজের কন্টেন্ট')
                                    ->schema([
                                        TextInput::make('coming_soon_title')->label('শিরোনাম (Title)'),
                                        Textarea::make('coming_soon_subtitle')->label('উপ-শিরোনাম (Subtitle)'),
                                        DateTimePicker::make('launch_date')
                                            ->label('লঞ্চের তারিখ ও সময়')
                                            ->native(false) // সুন্দর UI-এর জন্য
                                            ->helperText('কাউন্টডাউন টাইমারটি এই সময়ের উপর ভিত্তি করে চলবে।'),
                                    ])->columns(1),
                            ]),
                ]),
            ]);
    }
}
