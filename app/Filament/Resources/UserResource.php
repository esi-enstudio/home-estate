<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\WishlistRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'All Users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    // === START: বাম পাশের মূল কন্টেন্ট (২/৩ অংশ) ===
                    Group::make()->schema([
                        Section::make('প্রোফাইল তথ্য')
                            ->schema([
                                FileUpload::make('avatar_url')
                                    ->label('প্রোফাইল ছবি')
                                    ->image()->avatar()->directory('avatars')->imageEditor()
                                    ->columnSpanFull(),
                                TextInput::make('name')->label('পুরো নাম')->required(),
                                Textarea::make('bio')->label('সংক্ষিপ্ত পরিচিতি')->rows(3)->columnSpanFull(),
                            ])->columns(1),

                        Section::make('যোগাযোগ ও নিরাপত্তা')
                            ->schema([
                                TextInput::make('email')->label('ইমেইল')->email()->unique(ignoreRecord: true)->nullable(),
                                TextInput::make('phone')->label('ফোন নম্বর')->tel()->required()->unique(ignoreRecord: true)
                                    ->live(onBlur: true)
                                    ->hint(function (Get $get, string $operation) {
                                        if ($operation !== 'create') return null;
                                        $phone = $get('phone');
                                        if (empty($phone)) return null;
                                        $exists = \App\Models\User::where('phone', $phone)->exists();
                                        return new HtmlString($exists ? '<span style="color: red;">এই নম্বরটি ব্যবহৃত হয়েছে।</span>' : '<span style="color: green;">এই নম্বরটি ব্যবহারযোগ্য।</span>');
                                    }),
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))->required(fn (string $context): bool => $context === 'create')
                                    ->rule(Password::default()),
                                TextInput::make('password_confirmation')->password()->requiredWith('password')->dehydrated(false),
                            ])->columns(2),

                        Section::make('সোশ্যাল মিডিয়া লিংক')
                            ->schema([
                                KeyValue::make('social_links')
                                    ->label('')
                                    ->keyLabel('প্ল্যাটফর্ম') // e.g., Facebook
                                    ->valueLabel('প্রোফাইল URL') // e.g., https://facebook.com/username
                                    ->reorderable(),
                            ]),

                        Section::make('টিম মেম্বার তথ্য')
                            ->description('এই ব্যবহারকারীকে "আমাদের অনুপ্রেরণা" পেজে দেখাতে চাইলে এই তথ্যগুলো পূরণ করুন।')
                            ->schema([
                                TextInput::make('designation')->label('পদবি (Designation)'),
                                Toggle::make('show_on_our_inspiration_page')->label('টিম পেজে দেখান'),
                            ])->columns(1),
                    ])->columnSpan(2),

                    // === START: ডান পাশের সাইডবার (১/৩ অংশ) ===
                    Group::make()->schema([
                        Section::make('স্ট্যাটাস ও ভূমিকা')
                            ->schema([
                                Select::make('status')->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                    'banned' => 'Banned',
                                    'pending' => 'Pending',
                                ])->default('active')->required(),

                                Select::make('roles')->relationship('roles', 'name')->multiple()->preload()->searchable(),
                            ]),

                        Section::make('ভেরিফিকেশন ও পরিসংখ্যান')
                            ->schema([
                                Placeholder::make('email_verified_at')
                                    ->label('ইমেইল ভেরিফাইড')
                                    ->content(fn ($record) => $record?->email_verified_at ? $record->email_verified_at->format('d M Y, h:i A') : new HtmlString('<span style="color: red;">না</span>')),

                                Placeholder::make('phone_verified_at')
                                    ->label('ফোন ভেরিফাইড')
                                    ->content(fn ($record) => $record?->phone_verified_at ? $record->phone_verified_at->format('d M Y, h:i A') : new HtmlString('<span style="color: red;">না</span>')),

                                Placeholder::make('reviews_count')->label('মোট রিভিউ দিয়েছেন')->content(fn ($record) => $record?->reviews_count ?? 0),
                                Placeholder::make('average_rating')->label('গড় রেটিং পেয়েছেন')->content(fn ($record) => number_format($record?->average_rating ?? 0, 1) . ' / 5.0'),
                            ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // কলাম ১: ইউজার তথ্য (নাম, ইমেইল ও ছবি)
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('ছবি')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')), // একটি ডিফল্ট ছবি দিন

                Tables\Columns\TextColumn::make('name')
                    ->label('নাম ও ইমেইল')
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record): string => $record->email ?? 'N/A'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('ফোন নাম্বার')
                    ->searchable()
                    ->sortable(),

                // কলাম ২: ভূমিকা (Roles)
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('ভূমিকা')
                    ->badge()
                    ->separator(','),

                // কলাম ৩: স্ট্যাটাস
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'secondary' => 'inactive',
                        'danger' => 'banned',
                        'warning' => 'pending',
                    ]),

                // কলাম ৪: ভেরিফিকেশন স্ট্যাটাস
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('ইমেইল ভেরিফাইড')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('phone_verified_at')
                    ->label('ফোন ভেরিফাইড')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger'),

                // কলাম ৫: যোগদানের তারিখ
                Tables\Columns\TextColumn::make('created_at')
                    ->label('যোগদান')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // ডিফল্টভাবে হাইড থাকবে
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'banned' => 'Banned',
                        'pending' => 'Pending',
                    ]),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name'),
                Tables\Filters\TernaryFilter::make('email_verified_at')->label('Email Verified')->nullable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // === START: আকর্ষণীয় বাল্ক অ্যাকশন ===
                    Tables\Actions\BulkAction::make('changeStatus')
                        ->label('স্ট্যাটাস পরিবর্তন করুন')
                        ->icon('heroicon-o-tag')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                    'banned' => 'Banned',
                                ])->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each->update(['status' => $data['status']]);
                        })
                        ->deselectRecordsAfterCompletion(),
                    // === END ===
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    public static function getRelations(): array
    {
        return [
//            WishlistRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
