<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AmenityResource\Pages;
use App\Filament\App\Resources\AmenityResource\RelationManagers;
use App\Models\Amenity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationLabel = 'Amenities';
    protected static ?int $navigationSort = 3;

    /**
     * Set this to false to hide the resource from the navigation menu.
     * এই রিসোর্সটি সাইডবার নেভিগেশনে দেখানো হবে না।
     */
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'facility' => 'Facility',
                                'utility' => 'Utility',
                                'safety' => 'Safety',
                                'environment' => 'Environment',
                            ])
                            ->native(false),

                        Forms\Components\TextInput::make('icon_class')
                            ->label('Icon Class')
                            ->helperText('Example: "wifi" for Material Icons, or "fa-solid fa-wifi" for FontAwesome.')
                            ->live(debounce: 500),

                        // আইকন প্রিভিউ দেখানোর জন্য
                        Forms\Components\Placeholder::make('icon_preview')
                            ->label('Icon Preview')
                            ->content(function ($get) {
                                $iconClass = $get('icon_class');
                                if (!$iconClass) {
                                    return 'Enter an icon class to see a preview.';
                                }
                                // Material Icons এর জন্য
                                return new HtmlString('<i class="material-icons-outlined">' . e($iconClass) . '</i>');
                                // FontAwesome এর জন্য: return new \Illuminate\Support\HtmlString('<i class="' . e($iconClass) . '"></i>');
                            })
                            ->visible(fn ($get) => !empty($get('icon_class'))),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                // আইকন প্রিভিউ
                Tables\Columns\TextColumn::make('icon_class')
                    ->label('Icon')
                    ->html() // এই মেথডটি কন্টেন্টকে HTML হিসেবে রেন্ডার করতে বলে
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) {
                            return ''; // যদি আইকন ক্লাস না থাকে, তাহলে খালি দেখান
                        }
                        // Material Icons এর জন্য HTML তৈরি করুন
                        return '<i class="material-icons-outlined">' . e($state) . '</i>';
                        // FontAwesome এর জন্য: return '<i class="' . e($state) . '"></i>';
                    })
                    ->alignCenter(), // আইকনটিকে কলামের মাঝখানে দেখানোর জন্য

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('properties_count')
                    ->label('Properties')
                    ->counts('properties') // রিলেশনশিপ থেকে গণনা করবে
                    ->sortable(),

                Tables\Columns\IconColumn::make('show_on_homepage')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'facility' => 'Facility',
                        'utility' => 'Utility',
                        'safety' => 'Safety',
                        'environment' => 'Environment',
                    ]),
            ])
            ->defaultPaginationPageOption(5)
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
            'index' => Pages\ListAmenities::route('/'),
//            'create' => Pages\CreateAmenity::route('/create'),
//            'edit' => Pages\EditAmenity::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }
}
