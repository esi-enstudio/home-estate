<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterLinkResource\Pages;
use App\Filament\Resources\FooterLinkResource\RelationManagers;
use App\Models\FooterLink;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FooterLinkResource extends Resource
{
    protected static ?string $model = FooterLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Site Management';
    protected static ?string $navigationLabel = 'Footer Links';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('লিংকের বিবরণ')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('লিংকের লেখা (Title)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('url')
                            ->label('লিংকের URL')
                            ->required()
                            ->prefixIcon('heroicon-o-globe-alt')
                            ->helperText('অভ্যন্তরীণ লিংকের জন্য `/page/slug` অথবা বাহ্যিক লিংকের জন্য `https://example.com` ব্যবহার করুন।')
                            ->maxLength(255),

                        Forms\Components\Select::make('group')
                            ->label('মেন্যু গ্রুপ')
                            ->options([
                                'company' => 'Company',
                                'destinations' => 'Destinations',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->native(false), // সুন্দর UI-এর জন্য

                        Forms\Components\TextInput::make('sort_order')
                            ->label('সাজানোর ক্রম (Order)')
                            ->numeric()
                            ->default(0)
                            ->helperText('ছোট সংখ্যাগুলো আগে দেখানো হবে।'),
                    ])->columns(2),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('লিংকের লেখা')
                    ->searchable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->copyable() // URL কপি করার জন্য একটি আইকন যুক্ত করবে
                    ->copyableState(fn (string $state): string => url($state)), // সম্পূর্ণ URL কপি করবে

                Tables\Columns\TextColumn::make('group')
                    ->label('গ্রুপ')
                    ->badge()
                    ->colors([
                        'primary' => 'company',
                        'success' => 'destinations',
                        'warning' => 'other',
                    ]),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ক্রম')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'company' => 'Company',
                        'destinations' => 'Destinations',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListFooterLinks::route('/'),
            'create' => Pages\CreateFooterLink::route('/create'),
            'edit' => Pages\EditFooterLink::route('/{record}/edit'),
        ];
    }
}
