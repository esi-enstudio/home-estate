<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeaturedLocationResource\Pages;
use App\Filament\Resources\FeaturedLocationResource\RelationManagers;
use App\Models\FeaturedLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeaturedLocationResource extends Resource
{
    protected static ?string $model = FeaturedLocation::class;

    // === START: Navigation & Grouping Configuration ===
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'ওয়েবসাইট ম্যানেজমেন্ট';
    protected static ?string $navigationLabel = 'ফিচার্ড এলাকা';
    protected static ?string $modelLabel = 'ফিচার্ড এলাকা';
    protected static ?string $pluralModelLabel = 'ফিচার্ড এলাকাসমূহ';
    protected static ?int $navigationSort = 2;
    // === END: Navigation & Grouping Configuration ===

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('এলাকার বিবরণ')
                    ->schema([
                        Forms\Components\TextInput::make('name_bn')
                            ->label('এলাকার নাম (বাংলা)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name_en')
                            ->label('Area Name (English)')
                            ->required()
                            ->helperText('প্রপার্টি গণনার জন্য এই নামটি অবশ্যই `properties` টেবিলের `address_area`-এর সাথে হুবহু মিলতে হবে।')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),

                Forms\Components\Section::make('ছবি ও স্ট্যাটাস')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('featured_location_image')
                            ->label('এলাকার ছবি')
                            ->collection('featured_location_image')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->helperText('হোমপেজে দেখানোর জন্য একটি আকর্ষণীয় ছবি দিন। প্রস্তাবিত আকার: 400x300 পিক্সেল।')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('হোমপেজে দেখান')
                            ->default(true)
                            ->helperText('এটি চালু থাকলে এলাকাটি হোমপেজে প্রদর্শিত হবে।'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('সাজানোর ক্রম (Order)')
                            ->numeric()
                            ->default(0)
                            ->helperText('ছোট সংখ্যাগুলো আগে দেখানো হবে।'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_location_image')
                    ->collection('featured_location_image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name_bn')
                    ->label('নাম')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->name_en),

                // টেবিলে প্রপার্টির সংখ্যা দেখানোর জন্য
                Tables\Columns\TextColumn::make('properties_count')
                    ->label('সক্রিয় প্রপার্টি')
                    ->counts('properties')
                    ->badge(),

                // টেবিল থেকেই স্ট্যাটাস পরিবর্তন করার জন্য
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('হোমপেজে দেখান'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ক্রম')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('হোমপেজের স্ট্যাটাস'),
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
                ]),
            ])
            // === START: ড্র্যাগ-এন্ড-ড্রপ করে সাজানোর সুবিধা ===
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            // === END: ড্র্যাগ-এন্ড-ড্রপ করে সাজানোর সুবিধা ===
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
            'index' => Pages\ListFeaturedLocations::route('/'),
            'create' => Pages\CreateFeaturedLocation::route('/create'),
            'edit' => Pages\EditFeaturedLocation::route('/{record}/edit'),
        ];
    }
}
