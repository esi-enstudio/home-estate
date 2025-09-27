<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Phone Number')
                    ->required()
                    ->tel()
                    ->minLength(11)
                    ->maxLength(11)
                    ->rule('digits:11')
                    ->hint(function (Get $get) {
                        // শুধু create পেজে hint দেখানোর জন্য
                        if (!str(request()->route()->getName())->contains('.create')) {
                            return null;
                        }

                        $value = $get('phone_number');

                        if (!$value || strlen($value) !== 11) {
                            return new HtmlString('<span class="text-gray-500">Enter a valid 11-digit number</span>');
                        }

                        $exists = User::where('phone_number', $value)->exists();

                        return new HtmlString(
                            $exists
                                ? '<span class="text-red-600 font-medium">Not available</span>'
                                : '<span class="text-green-600 font-medium">Available</span>'
                        );
                    })
                    ->hintIcon('heroicon-o-phone')
                    ->live(onBlur: true)
                    ->reactive(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->rule(Password::default())
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create')
                    ->maxLength(255),

                TextInput::make('password_confirmation')
                    ->password()
                    ->requiredWith('password')
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state)) // Ignore empty values on update
                    ->same('password'),

                Forms\Components\Textarea::make('bio')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('type')
                    ->options([
                        'house_owner' => 'House Owner',
                        'tenant' => 'Tenant',
                    ]),

                Select::make('status')
                    ->default('active')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                FileUpload::make('avatar_url')
                    ->label('Avatar')
                    ->disk('public')
                    ->directory('avatars'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')->label('Avatar')->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Str::title($state))
                    ->color('danger'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => Str::title($state))
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Personal Information')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('name'),
                        Components\TextEntry::make('email'),
                        Components\TextEntry::make('phone'), // যদি 'phone' কলাম থাকে
                        Components\TextEntry::make('role')->badge(),
                    ]),

                Components\Section::make('System Information')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('created_at')->dateTime(),
                        Components\TextEntry::make('updated_at')->dateTime(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            WishlistRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
//            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
