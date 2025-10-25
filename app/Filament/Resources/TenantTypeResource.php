<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Filament\Resources\TenantResource\RelationManagers;
use App\Models\TenantType;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantTypeResource extends Resource
{
    protected static ?string $model = TenantType::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationLabel = 'Tenants';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('ভাড়াটিয়ার ধরনের তথ্য')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name_en')
                            ->label('নাম (ইংরেজি)')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('name_bn')
                            ->label('নাম (বাংলা)')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('icon_class')
                            ->label('আইকন ক্লাস')
                            ->helperText('Font Awesome ক্লাস ব্যবহার করুন, যেমন: fas fa-users'),

                        Textarea::make('description_bn')
                            ->label('সংক্ষিপ্ত বর্ণনা')
                            ->columnSpanFull(), // এই ফিল্ডটি পুরো প্রস্থ জুড়ে থাকবে
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('icon_class')->label('আইকন'),

                TextColumn::make('name_bn')
                    ->label('নাম (বাংলা)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name_en')
                    ->label('নাম (ইংরেজি)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('তৈরির তারিখ')
                    ->dateTime('d M, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultPaginationPageOption(5)
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
