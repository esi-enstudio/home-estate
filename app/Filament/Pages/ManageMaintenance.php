<?php

namespace App\Filament\Pages;

use App\Settings\MaintenanceSettings;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                                        Toggle::make('coming_soon_enabled')
                                            ->label('"Coming Soon" মোড চালু করুন')
                                            ->helperText('এটি চালু করলে সুপার-অ্যাডমিন ছাড়া অন্য কেউ সাইট ভিজিট করতে পারবে না এবং সবাই "Coming Soon" পেজটি দেখতে পাবে।')
                                            ->live(), // <-- live() অপরিহার্য

                                        TextInput::make('coming_soon_title')
                                            ->visible(fn (Get $get) => $get('coming_soon_enabled'))
                                            ->label('শিরোনাম (Title)'),

                                        Textarea::make('coming_soon_subtitle')
                                            ->visible(fn (Get $get) => $get('coming_soon_enabled'))
                                            ->label('উপ-শিরোনাম (Subtitle)'),

                                        DateTimePicker::make('launch_date')
                                            ->label('লঞ্চের তারিখ ও সময়')
                                            ->native(false) // সুন্দর UI-এর জন্য
                                            ->visible(fn (Get $get) => $get('coming_soon_enabled'))
                                            ->helperText('কাউন্টডাউন টাইমারটি এই সময়ের উপর ভিত্তি করে চলবে।'),

                                    ])->columns(1),

                                Section::make('ছবি')
                                    ->visible(fn (Get $get) => $get('coming_soon_enabled'))
                                    ->schema([
                                        FileUpload::make('background_image')
                                            ->label('পেজের ছবি')
                                            ->image()
                                            ->directory('site') // ছবিটি storage/app/public/site ফোল্ডারে সেভ হবে
                                            ->helperText('সেরা ফলাফলের জন্য একটি উচ্চ-রেজোলিউশনের ভার্টিকাল ছবি ব্যবহার করুন।'),
                                    ])->columns(1),
                            ]),
                ]),
            ]);
    }
}
