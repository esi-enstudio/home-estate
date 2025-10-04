<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PropertyResource\Pages;
use App\Filament\App\Resources\PropertyResource\RelationManagers;
use App\Filament\Resources\PropertyResource\RelationManagers\AmenitiesRelationManager;
use App\Filament\Resources\PropertyResource\RelationManagers\EnquiriesRelationManager;
use App\Filament\Resources\PropertyResource\RelationManagers\ReviewsRelationManager;
use App\Models\District;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Union;
use App\Models\Upazila;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationLabel = 'My Properties';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columnSpan(2)
                    ->columns(3)
                    ->schema([
                        // Edit Form Fields
                        TextInput::make('views_count')
                            ->label('ভিউ সংখ্যা')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn(['edit','view']),

                        TextInput::make('reviews_count')
                            ->label('রিভিউ সংখ্যা')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn(['edit','view']),

                        TextInput::make('average_rating')
                            ->label('গড় রেটিং')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn(['edit','view']),
                    ]),

                Forms\Components\Grid::make(3)
                    ->schema([
                        // বাম পাশের মূল কন্টেন্ট (২/৩ অংশ)
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Section::make('মূল তথ্য (Core Information)')
                                    ->schema([
                                        TextInput::make('property_code')
                                            ->label('প্রপার্টি কোড')
                                            ->disabled() // ইউজার এটি পরিবর্তন করতে পারবে না
                                            ->hiddenOn('create'), // নতুন প্রপার্টি তৈরির সময় এটি হাইড থাকবে

                                        TextInput::make('title')
                                            ->label('বাসার শিরোনাম')
                                            ->required()
                                            ->maxLength(255),

                                        RichEditor::make('description')
                                            ->label('বিস্তারিত বর্ণনা')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Images & Media')
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('featured_image')
                                            ->label('ফিচার্ড বা প্রধান ছবি (থাম্বনেইল)')
                                            ->collection('featured_image')
                                            ->multiple(false)
                                            ->required()
                                            ->image()
                                            ->maxSize(2048)
                                            ->imageResizeMode('cover')
                                            ->panelLayout('compact')
                                            ->helperText('এটি আপনার প্রপার্টির প্রধান ছবি হিসেবে ওয়েবসাইটে দেখানো হবে।'),

                                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery_images')
                                            ->label('গ্যালারির জন্য অতিরিক্ত ছবি')
                                            ->collection('gallery')
                                            ->multiple()
                                            ->reorderable()
                                            ->image()
                                            ->maxSize(2048)
                                            ->maxFiles(10)
                                            ->panelLayout('compact'),

                                        Forms\Components\TextInput::make('video_url')
                                            ->label('ভিডিও লিংক (ইউটিউব/ভিমিও)')
                                            ->url(),
                                    ]),

                                Forms\Components\Section::make('বাসার স্পেসিফিকেশন (Property Specifications)')
                                    ->schema([
                                        Forms\Components\Grid::make(3)->schema([
                                            // Bedrooms - শুধু আবাসিক প্রপার্টির জন্য
                                            Select::make('bedrooms')
                                                ->label('বেডরুম')
                                                ->options(array_combine(range(0, 10), range(0, 10)))
                                                ->required()
                                                ->visible(function ($get) {
                                                    $propertyType = PropertyType::find($get('property_type_id'));
                                                    return in_array($propertyType?->slug, ['apartment', 'duplex', 'tin-shed', 'semi-ripe', 'room', 'house', 'villa', 'penthouse']);
                                                }),

                                            // Bathrooms - শুধু আবাসিক প্রপার্টির জন্য
                                            Select::make('bathrooms')
                                                ->label('বাথরুম')
                                                ->options(array_combine(range(0, 10), range(0, 10)))
                                                ->required()
                                                ->visible(function ($get) {
                                                    $propertyType = PropertyType::find($get('property_type_id'));
                                                    return in_array($propertyType?->slug, ['apartment', 'duplex', 'tin-shed', 'semi-ripe', 'room', 'house', 'villa', 'penthouse']);
                                                }),

                                            // Balconies - শুধু এপার্টমেন্ট/ডুপ্লেক্স/পেন্টহাউস এর জন্য
                                            Select::make('balconies')
                                                ->label('বারান্দা')
                                                ->options(array_combine(range(0, 10), range(0, 10)))
                                                ->visible(function ($get) {
                                                    $propertyType = PropertyType::find($get('property_type_id'));
                                                    return in_array($propertyType?->slug, ['apartment', 'duplex', 'penthouse']);
                                                }),

                                            // Size - সব প্রপার্টির জন্য
                                            TextInput::make('size_sqft')
                                                ->label('আকার (স্কয়ার ফিট)')
                                                ->required()
                                                ->numeric(),

                                            // Floor level - এপার্টমেন্ট, ডুপ্লেক্স, কমার্শিয়াল স্পেস, অফিসের জন্য
                                            TextInput::make('floor_level')
                                                ->label('ফ্লোর লেভেল')
                                                ->numeric()
                                                ->maxLength(255)
                                                ->visible(function ($get) {
                                                    $propertyType = PropertyType::find($get('property_type_id'));
                                                    return in_array($propertyType?->slug, ['apartment', 'duplex', 'commercial-space', 'office', 'penthouse', 'shopping-mall']);
                                                }),

                                            // Total floors - মাল্টি-স্টোরি বিল্ডিং এর জন্য
                                            TextInput::make('total_floors')
                                                ->label('মোট তলা')
                                                ->numeric()
                                                ->minValue(1)
                                                ->maxValue(100)
                                                ->nullable()
                                                ->visible(function ($get) {
                                                    $propertyType = PropertyType::find($get('property_type_id'));
                                                    return in_array($propertyType?->slug, ['apartment', 'duplex', 'commercial-space', 'office', 'shopping-mall', 'hospital', 'hotel']);
                                                }),

                                            // Facing direction - আবাসিক প্রপার্টির জন্য
                                            Select::make('facing_direction')
                                                ->label('কোনমুখী ফ্ল্যাট')
                                                ->options([
                                                    'south' => 'দক্ষিণ',
                                                    'north' => 'উত্তর',
                                                    'east' => 'পূর্ব',
                                                    'west' => 'পশ্চিম',
                                                    'south-east' => 'দক্ষিণ-পূর্ব',
                                                    'north-east' => 'উত্তর-পূর্ব',
                                                ])
                                                ->visible(function ($get) {
                                                    $propertyType = PropertyType::find($get('property_type_id'));
                                                    return in_array($propertyType?->slug, ['apartment', 'duplex', 'room', 'house', 'villa', 'penthouse']);
                                                }),

                                            // Year built - সব প্রপার্টির জন্য
                                            TextInput::make('year_built')
                                                ->label('নির্মাণ সাল')
                                                ->numeric()
                                                ->maxValue(date('Y')),
                                        ]),
                                    ]),

                                Forms\Components\Section::make('অতিরিক্ত সুবিধা ও নিয়মাবলী (Additional Features & Rules)')
                                    ->schema([
                                        KeyValue::make('additional_features')
                                            ->label('অন্যান্য সুবিধা')
                                            ->keyLabel('ফিচারের নাম') // "Key" ইনপুট ফিল্ডের লেবেল
                                            ->valueLabel('সংখ্যা বা বিবরণ') // "Value" ইনপুট ফিল্ডের লেবেল
                                            ->addActionLabel('নতুন সুবিধা যোগ করুন') // নতুন আইটেম যোগ করার বাটনের লেখা
                                            ->helperText('এখানে ফ্ল্যাটের ভেতরের অতিরিক্ত সুবিধাগুলো যোগ করুন, যেমন - AC, Fridge, Geyser ইত্যাদি।')
                                            ->columnSpanFull(),

                                        Textarea::make('house_rules')
                                            ->label('বাসার নিয়মাবলী')
                                            ->rows(5)
                                            ->helperText('প্রতিটি নিয়ম একটি নতুন লাইনে লিখুন।'),

                                        Forms\Components\Repeater::make('faqs')
                                            ->label('Frequently Asked Questions (FAQs)')
                                            ->schema([
                                                Forms\Components\TextInput::make('question')->required(),
                                                Forms\Components\Textarea::make('answer')->required(),
                                            ])
                                            ->columns(2),
                                    ]),

                                Forms\Components\Section::make('SEO Meta Data')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title'),
                                        Forms\Components\Textarea::make('meta_description'),
                                        Forms\Components\TagsInput::make('meta_keywords'),
                                    ]),

                            ])->columnSpan(2),

                        // ডান পাশের সাইডবার (১/৩ অংশ)
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Section::make('মূল্য এবং প্রাপ্যতা (Pricing & Availability)')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'pending' => 'Pending',
                                                'active' => 'Active',
                                                'rented' => 'Rented',
                                                'inactive' => 'Inactive',
                                                'sold_out' => 'Sold Out',
                                            ])
                                            ->required()
                                            ->default('pending'),

                                        TextInput::make('rent_price')
                                            ->label('মাসিক ভাড়া')
                                            ->required()
                                            ->numeric()
                                            ->prefix('৳'),

                                        Forms\Components\Select::make('rent_type')
                                            ->label('Rent Type')
                                            ->options([
                                                'day' => 'Day',
                                                'week' => 'Week',
                                                'month' => 'Month',
                                                'year' => 'Year',
                                            ])
                                            ->required()
                                            ->default('month'),

                                        TextInput::make('service_charge')
                                            ->label('সার্ভিস চার্জ')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('৳'),

                                        TextInput::make('security_deposit')
                                            ->label('সিকিউরিটি ডিপোজিট')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('৳'),

                                        Forms\Components\Select::make('is_negotiable')
                                            ->options(['negotiable' => 'Negotiable', 'fixed' => 'Fixed'])
                                            ->required()->default('fixed'),

                                        DatePicker::make('available_from')
                                            ->label('কবে থেকে পাওয়া যাবে')
                                            ->required(),
                                    ]),

                                Forms\Components\Section::make('Associations')
                                    ->schema([
                                        Forms\Components\Select::make('user_id')->label('Owner')
                                            ->relationship('user', 'name')
                                            ->searchable()->required(),

                                        Select::make('property_type_id')
                                            ->relationship(
                                                'propertyType', // relationship name
                                                'name_en' // default title column (will be overridden by getOptionLabelFromRecordUsing)
                                            )
                                            ->getOptionLabelFromRecordUsing(function (PropertyType $record) {
                                                return "{$record->name_en} ({$record->name_bn})";
                                            })
                                            ->searchable(['name_en', 'name_bn'])
                                            ->helperText('এটি কি ফ্ল্যাট, ডুপ্লেক্স নাকি অন্য কোনো ধরনের প্রপার্টি? তা নির্বাচন করুন।')
                                            ->label('Property Type')
                                            ->live()
                                            ->preload()
                                            ->required(),

                                        Forms\Components\Select::make('purpose')
                                            ->options(['rent' => 'For Rent', 'sell' => 'For Sell'])
                                            ->required()->default('rent'),
                                    ]),

                                Forms\Components\Section::make('অবস্থান (Location)')
                                    ->schema([
                                        Select::make('division_id')
                                            ->label('বিভাগ')
                                            ->required()
                                            ->relationship('division', 'bn_name')
                                            ->helperText('প্রপার্টিটি কোন বিভাগে অবস্থিত তা নির্বাচন করুন।')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('district_id', null)),

                                        Select::make('district_id')
                                            ->label('জেলা')
                                            ->options(fn (Get $get): Collection => District::query()
                                                ->where('division_id', $get('division_id'))
                                                ->pluck('bn_name', 'id'))
                                            ->getOptionLabelUsing(fn ($value): ?string => District::find($value)?->bn_name)
                                            ->searchable()->live()->preload()
                                            ->afterStateUpdated(fn (Set $set) => $set('upazila_id', null))
                                            ->helperText('প্রপার্টিটি কোন জেলায় অবস্থিত তা নির্বাচন করুন।')
                                            ->required(),

                                        Select::make('upazila_id')
                                            ->label('উপজেলা')
                                            ->options(fn (Get $get): Collection => Upazila::query()
                                                ->where('district_id', $get('district_id'))
                                                ->pluck('bn_name', 'id'))
                                            // --- এখানে getOptionLabel() যোগ করা হয়েছে ---
                                            ->getOptionLabelUsing(fn ($value): ?string => Upazila::find($value)?->bn_name)
                                            ->searchable()->live()->preload()
                                            ->afterStateUpdated(fn (Set $set) => $set('union_id', null))
                                            ->helperText('প্রপার্টিটি কোন উপজেলায় অবস্থিত তা নির্বাচন করুন।')
                                            ->required(),

                                        Select::make('union_id')
                                            ->label('ইউনিয়ন')
                                            ->helperText('প্রপার্টিটি কোন ইউনিয়নে অবস্থিত তা নির্বাচন করুন (যদি থাকে)।')
                                            ->options(fn (Get $get): Collection => Union::query()
                                                ->where('upazila_id', $get('upazila_id'))
                                                ->pluck('bn_name', 'id'))
                                            ->getOptionLabelUsing(fn ($value): ?string => Union::find($value)?->bn_name)
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),

                                        TextInput::make('address_street')->label('রাস্তার ঠিকানা')->required(),
                                        TextInput::make('address_area')->label('এলাকা')->helperText('(যেমন: চন্ডিবেড় মধ্যপাড়া, ভৈরবপুর উত্তরপাড়া)')->required(),
                                        TextInput::make('address_zipcode')->label('জিপ কোড')->numeric(),
                                        TextInput::make('google_maps_location_link')->label('গুগল ম্যাপস লিংক')->url(),

                                        TextInput::make('latitude')
                                            ->label('Latitude (অক্ষাংশ)')
                                            ->helperText('যেমন: 23.77701234')
                                            ->nullable()
                                            // ডেটাবেসে পাঠানোর আগে ফরম্যাট করা
                                            ->dehydrateStateUsing(fn (?string $state): ?string => $state ? rtrim(rtrim($state, '0'), '.') : null)
                                            ->rule('regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'), // অক্ষাংশের জন্য ভ্যালিডেশন

                                        TextInput::make('longitude')
                                            ->label('Longitude (দ্রাঘিমাংশ)')
                                            ->helperText('যেমন: 90.39945100')
                                            ->nullable()
                                            // ডেটাবেসে পাঠানোর আগে ফরম্যাট করা
                                            ->dehydrateStateUsing(fn (?string $state): ?string => $state ? rtrim(rtrim($state, '0'), '.') : null)
                                            ->rule('regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'), // দ্রাঘিমাংশের জন্য ভ্যালিডাউনলোড
                                    ]),

                                Forms\Components\Section::make('Visibility')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_available')->default(true),
                                        Forms\Components\Toggle::make('is_featured'),
                                        Forms\Components\Toggle::make('is_trending'),
                                        Forms\Components\Toggle::make('is_verified'),
                                    ]),

                            ])->columnSpan(1),
                    ])
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured_image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('propertyType.name_en')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rent_price')
                    ->money('BDT')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'rented',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('property_type_id')
                    ->label('Property Type')
                    ->relationship('propertyType', 'name_en'),

                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TernaryFilter::make('is_verified'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            AmenitiesRelationManager::class,
            EnquiriesRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    // এই মেথডটি নিশ্চিত করবে যে ইউজার শুধুমাত্র তার নিজের প্রপার্টি দেখতে পাবে
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getNavigationBadge(): ?string
    {
        // static::getModel()::count() এর পরিবর্তে আমরা getEloquentQuery() ব্যবহার করব
        // যা স্বয়ংক্রিয়ভাবে শুধুমাত্র লগইন করা ইউজারের প্রপার্টি গণনা করবে।
        return static::getEloquentQuery()->count();
    }
}
