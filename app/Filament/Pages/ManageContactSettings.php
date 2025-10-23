<?php

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageContactSettings extends SettingsPage
{
    protected static string $settings = ContactSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Contact';
    protected static ?int $navigationSort = 6;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // --- Tab 1: Page Header ---
                        Tabs\Tab::make('পেজের হেডার')
                            ->icon('heroicon-o-view-columns')
                            ->schema([
                                TextInput::make('breadcrumb_title')
                                    ->label('ব্রেডক্রাম্ব শিরোনাম')
                                    ->required(),
                                TextInput::make('breadcrumb_subtitle')
                                    ->label('ব্রেডক্রাম্ব উপ-শিরোনাম')
                                    ->required(),
                            ]),

                        // --- Tab 2: Sales & Support ---
                        Tabs\Tab::make('বিক্রয় ও সাপোর্ট')
                            ->icon('heroicon-o-phone-arrow-up-right')
                            ->schema([
                                TextInput::make('sales_title')
                                    ->label('বিক্রয় দলের শিরোনাম')
                                    ->required(),
                                Textarea::make('sales_description')
                                    ->label('বিক্রয় দলের বর্ণনা')
                                    ->rows(3),
                                TextInput::make('sales_phone')
                                    ->label('বিক্রয় দলের ফোন')
                                    ->required(),
                                TextInput::make('support_title')
                                    ->label('সাপোর্ট সেকশনের শিরোনাম')
                                    ->required(),
                                Textarea::make('support_description')
                                    ->label('সাপোর্ট সেকশনের বর্ণনা')
                                    ->rows(3),
                            ])->columns(2),

                        // --- Tab 3: Contact Details ---
                        Tabs\Tab::make('যোগাযোগের ঠিকানা')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Repeater::make('contact_emails')
                                    ->label('ইমেইল অ্যাড্রেস')
                                    ->schema([TextInput::make('address')->email()->required()])
                                    ->addActionLabel('নতুন ইমেইল যোগ করুন')
                                    ->columns(1),

                                Repeater::make('contact_phones')
                                    ->label('ফোন নম্বর')
                                    ->schema([TextInput::make('number')->tel()->required()])
                                    ->addActionLabel('নতুন ফোন নম্বর যোগ করুন')
                                    ->columns(1),

                                Textarea::make('contact_address')
                                    ->label('অফিসের ঠিকানা')
                                    ->required(),
                            ]),

                        // --- Tab 4: Images & Map ---
                        Tabs\Tab::make('ছবি ও ম্যাপ')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('image_one')
                                    ->label('প্রথম সেকশনের ছবি')
                                    ->disk('public')->image(),
                                FileUpload::make('image_two')
                                    ->label('দ্বিতীয় সেকশনের ছবি')
                                    ->disk('public')->image(),
                                TextInput::make('map_iframe_url')
                                    ->label('গুগল ম্যাপ Iframe URL')
                                    ->url()
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(), // <-- এটি নিশ্চিত করে যে ট্যাবগুলো সম্পূর্ণ প্রস্থ নেবে
            ]);
    }
}
