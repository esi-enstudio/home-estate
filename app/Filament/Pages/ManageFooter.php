<?php

namespace App\Filament\Pages;

use App\Settings\FooterSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageFooter extends SettingsPage
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Footer';
    protected static ?int $navigationSort = 3;
    protected static string $settings = FooterSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([

                    Forms\Components\Tabs\Tab::make('যোগাযোগ ও ফুটার')
                        ->schema([
                            Forms\Components\Section::make('Join Section (ফুটারের উপরে)')->schema([
                                Forms\Components\TextInput::make('join_title')->label('Title'),
                                Forms\Components\TextInput::make('join_subtitle')->label('Subtitle'),
                                Forms\Components\TextInput::make('join_button_text')->label('Button Text'),
                                Forms\Components\TextInput::make('join_button_link')->label('Button Link'),
                            ])->columns(2),

                            Forms\Components\Section::make('যোগাযোগের তথ্য')->schema([
                                Forms\Components\Textarea::make('location')->label('ঠিকানা'),
                                Forms\Components\TextInput::make('phone')->label('ফোন নম্বর'),
                                Forms\Components\TextInput::make('email')->label('ইমেইল'),
                            ])->columns(3),
                        ]),

                    Forms\Components\Tabs\Tab::make('ফুটার মেন্যু')
                        ->schema([
                            Forms\Components\KeyValue::make('company_menu')
                                ->label('Company Menu')
                                ->keyLabel('লিংকের লেখা (Label)')
                                ->valueLabel('লিংকের URL')
                                ->addActionLabel('নতুন লিংক যোগ করুন')
                                ->reorderable(),

                            Forms\Components\KeyValue::make('destinations_menu')
                                ->label('Destinations Menu')
                                ->keyLabel('লিংকের লেখা (Label)')
                                ->valueLabel('লিংকের URL')
                                ->addActionLabel('নতুন লিংক যোগ করুন')
                                ->reorderable(),
                        ]),

                    Forms\Components\Tabs\Tab::make('অন্যান্য')
                        ->schema([
                            Forms\Components\Section::make('নিউজলেটার সেকশন')->schema([
                                Forms\Components\TextInput::make('newsletter_title'),
                                Forms\Components\TextInput::make('newsletter_subtitle'),
                            ])->columns(2),

                            Forms\Components\Section::make('সোশ্যাল মিডিয়া লিংক')->schema([
                                Forms\Components\TextInput::make('facebook_link'),
                                Forms\Components\TextInput::make('twitter_link'),
                                Forms\Components\TextInput::make('instagram_link'),
                                Forms\Components\TextInput::make('linkedin_link'),
                            ])->columns(2),
                        ]),
                ]),
            ]);
    }
}
