<?php

namespace App\Filament\Pages;

use App\Settings\MaintenanceSettings;
use App\Settings\SiteSettings;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\SettingsPage;

class ManageSite extends SettingsPage
{
    protected static string $settings = SiteSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site';
    protected static ?int $navigationSort = 5;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // প্রথমে একটি Tabs কন্টেইনার তৈরি করা হয়েছে
                Tabs::make('Site Settings Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        // এরপর Tab গুলোকে ->tabs() অ্যারের ভেতরে রাখা হয়েছে
                        Tabs\Tab::make('গ্লোবাল SEO')
                            ->schema([
                                TextInput::make('meta_title')->label('সাইটের ডিফল্ট মেটা টাইটেল'),
                                Textarea::make('meta_description')->label('সাইটের ডিফল্ট মেটা বর্ণনা')->rows(3),
                                TagsInput::make('meta_keywords')->label('সাইটের ডিফল্ট মেটা কিওয়ার্ড'),
                                FileUpload::make('og_image')->label('ডিফল্ট শেয়ারিং ইমেজ (OG Image)')->image()->directory('seo'),
                            ]),

                        // আপনি ভবিষ্যতে এখানে আরও ট্যাব যোগ করতে পারেন
                        // Tabs\Tab::make('অন্যান্য সেটিংস')
                        //     ->schema([
                        //         // ...
                        //     ]),
                    ]),
            ]);
    }
}
