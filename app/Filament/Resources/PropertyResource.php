<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
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
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationLabel = 'My Properties';
    protected static ?int $navigationSort = 1;


    /**
     * কোন প্রপার্টি টাইপের জন্য কোন ফিল্ডগুলো দেখানো হবে, তার একটি কেন্দ্রীয় ম্যাপ।
     * এটি রক্ষণাবেক্ষণ করা খুবই সহজ।
     */
    private static array $fieldsByPropertyType = [
        // আবাসিক (Residential) - এদের জন্য প্রায় সব ফিল্ডই প্রযোজ্য
        'apartment'   => ['bedrooms', 'bathrooms', 'balconies', 'floor_level', 'total_floors', 'facing_direction', 'additional_features', 'house_rules', 'faqs'],
        'duplex'      => ['bedrooms', 'bathrooms', 'balconies', 'floor_level', 'total_floors', 'facing_direction', 'additional_features', 'house_rules', 'faqs'],
        'house'       => ['bedrooms', 'bathrooms', 'facing_direction', 'additional_features', 'house_rules', 'faqs'],
        'villa'       => ['bedrooms', 'bathrooms', 'facing_direction', 'additional_features', 'house_rules', 'faqs'],
        'penthouse'   => ['bedrooms', 'bathrooms', 'balconies', 'floor_level', 'total_floors', 'facing_direction', 'additional_features', 'house_rules', 'faqs'],
        'room'        => ['bedrooms', 'bathrooms', 'facing_direction', 'additional_features', 'house_rules', 'faqs'],
        'tin-shed'    => ['bedrooms', 'bathrooms', 'additional_features', 'house_rules', 'faqs'],
        'semi-ripe'   => ['bedrooms', 'bathrooms', 'additional_features', 'house_rules', 'faqs'],

        // বাণিজ্যিক (Commercial) - এদের জন্য house_rules প্রযোজ্য নয়
        'commercial-space' => ['floor_level', 'total_floors', 'additional_features', 'faqs'],
        'office'           => ['floor_level', 'total_floors', 'additional_features', 'faqs'],
        'shopping-mall'    => ['total_floors', 'additional_features', 'faqs'],
        'factory'          => ['additional_features', 'faqs'],
        'warehouse'        => ['additional_features', 'faqs'],
        'hotel'            => ['total_floors', 'additional_features', 'faqs'],
        'hospital'         => ['total_floors', 'additional_features', 'faqs'],

        // ভূমি (Land) - এদের জন্য শুধুমাত্র faqs প্রযোজ্য হতে পারে
        'land' => ['faqs'],
    ];

    /**
     * সকল প্রপার্টি টাইপের জন্য প্রযোজ্য সাধারণ ফিল্ড।
     */
    private static array $commonFields = ['size_sqft', 'year_built'];

    /**
     * এই মেথডটি প্রতিটি ফিল্ডের জন্য visibility চেক করবে।
     * এটি static caching ব্যবহার করে যাতে প্রতি রিকোয়েস্টে শুধুমাত্র একবার ডাটাবেজ কোয়েরি হয়।
     */
    private static function isFieldVisible(string $fieldName, Get $get): bool
    {
        // স্ট্যাটিক ভ্যারিয়েবলগুলো শুধুমাত্র একবার ইনিশিয়ালাইজ হয় এবং রিকোয়েস্টের মধ্যে তাদের মান ধরে রাখে।
        static $propertyTypeSlug = null;
        static $lastTypeId = null;

        $currentTypeId = $get('property_type_id');

        // যদি কোনো টাইপ সিলেক্ট করা না থাকে, তাহলে ফিল্ড দেখাও না।
        if (empty($currentTypeId)) {
            return false;
        }

        // যদি টাইপ পরিবর্তন হয়, তবেই কেবল নতুন করে ডাটাবেজ থেকে slug আনা হবে।
        if ($lastTypeId !== $currentTypeId) {
            $lastTypeId = $currentTypeId;
            $propertyTypeSlug = PropertyType::find($currentTypeId)?->slug;
        }

        if (empty($propertyTypeSlug)) {
            return false;
        }

        // চেক করুন ফিল্ডটি সাধারণ ফিল্ডের তালিকায় আছে কিনা।
        if (in_array($fieldName, self::$commonFields)) {
            return true;
        }

        // চেক করুন ফিল্ডটি ওই নির্দিষ্ট প্রপার্টি টাইপের জন্য প্রযোজ্য কিনা।
        $visibleFields = self::$fieldsByPropertyType[$propertyTypeSlug] ?? [];
        return in_array($fieldName, $visibleFields);
    }

    /**
     * Checks if a section should be visible by checking if any of its fields are visible.
     */
    private static function isSectionVisible(array $fields, Get $get): bool
    {
        // সেকশনের ফিল্ডগুলোর মধ্যে যেকোনো একটি দৃশ্যমান হলেই সেকশনটি দেখানো হবে।
        foreach ($fields as $field) {
            if (self::isFieldVisible($field, $get)) {
                return true; // একটি পাওয়া গেলেই আর চেক করার দরকার নেই
            }
        }
        return false; // কোনো ফিল্ডই দৃশ্যমান না হলে সেকশনটি হাইড করা হবে
    }

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
                                        Forms\Components\Grid::make(3)->schema([
                                            TextInput::make('property_code')
                                                ->label('প্রপার্টি কোড')
                                                ->disabled()
                                                ->dehydrated() // disabled থাকা সত্ত্বেও সেভ হবে
                                                ->placeholder('স্বয়ংক্রিয়ভাবে তৈরি হবে')
                                                ->columnSpan(1),

                                            TextInput::make('title')
                                                ->label('শিরোনাম (Title)')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpan(2),
                                        ]),

                                        RichEditor::make('description')
                                            ->label('বিস্তারিত বর্ণনা (Description)')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('ছবি ও ভিডিও (Images & Video)')
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('featured_image')
                                            ->label('ফিচার্ড ছবি (Featured Image)')
                                            ->collection('featured_image')
                                            ->required()->image()->imageEditor()->maxSize(2048)
                                            ->panelLayout('compact'),

                                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery_images')
                                            ->label('গ্যালারির ছবি (Gallery Images)')
                                            ->collection('gallery')
                                            ->multiple()->reorderable()->image()->maxSize(2048)->maxFiles(10)
                                            ->panelLayout('compact'),

                                        Forms\Components\TextInput::make('video_url')
                                            ->label('ভিডিও লিংক (Video URL)')
                                            ->prefixIcon('heroicon-o-video-camera')
                                            ->url(),
                                    ]),

                                Forms\Components\Section::make('প্রপার্টির স্পেসিফিকেশন (Specifications)')
                                    ->schema([
                                        Forms\Components\Grid::make(3)->schema([
                                            Select::make('property_type_id')
                                                ->label('প্রপার্টির ধরণ (Property Type)')
                                                ->relationship('propertyType', 'name_bn')
                                                ->searchable()->preload()->live()->required()
                                                ->columnSpanFull(),

                                            Select::make('bedrooms')->label('বেডরুম')->options(array_combine(range(0, 10), range(0, 10)))
                                                ->visible(fn (Get $get) => self::isFieldVisible('bedrooms', $get)),
                                            Select::make('bathrooms')->label('বাথরুম')->options(array_combine(range(0, 10), range(0, 10)))
                                                ->visible(fn (Get $get) => self::isFieldVisible('bathrooms', $get)),
                                            Select::make('balconies')->label('বারান্দা')->options(array_combine(range(0, 10), range(0, 10)))
                                                ->visible(fn (Get $get) => self::isFieldVisible('balconies', $get)),

                                            TextInput::make('size_sqft')->label('আকার (স্কয়ার ফিট)')->numeric()->required()
                                                ->visible(fn (Get $get) => self::isFieldVisible('size_sqft', $get)),
                                            TextInput::make('floor_level')->label('ফ্লোর লেভেল')
                                                ->visible(fn (Get $get) => self::isFieldVisible('floor_level', $get)),
                                            TextInput::make('total_floors')->label('মোট তলা')->numeric()
                                                ->visible(fn (Get $get) => self::isFieldVisible('total_floors', $get)),
                                            Select::make('facing_direction')->label('কোনমুখী')
                                                ->options(['south' => 'দক্ষিণ', 'north' => 'উত্তর', 'east' => 'পূর্ব', 'west' => 'পশ্চিম', 'south-east' => 'দক্ষিণ-পূর্ব', 'north-east' => 'উত্তর-পূর্ব'])
                                                ->visible(fn (Get $get) => self::isFieldVisible('facing_direction', $get)),
                                            TextInput::make('year_built')->label('নির্মাণ সাল')->numeric()->maxValue(date('Y'))
                                                ->visible(fn (Get $get) => self::isFieldVisible('year_built', $get)),
                                        ]),
                                    ]),

                                Forms\Components\Section::make('অতিরিক্ত তথ্য (Additional Information)')
                                    ->visible(fn (Get $get) => self::isSectionVisible(['additional_features', 'house_rules', 'faqs'], $get))
                                    ->schema([
                                        KeyValue::make('additional_features')->label('অন্যান্য সুবিধা (Additional Features)')
                                            ->keyLabel('ফিচারের নাম')->valueLabel('বিবরণ')->addActionLabel('নতুন সুবিধা যোগ করুন')
                                            ->visible(fn (Get $get) => self::isFieldVisible('additional_features', $get)),

                                        Textarea::make('house_rules')->label('বাসার নিয়মাবলী (House Rules)')->rows(4)
                                            ->visible(fn (Get $get) => self::isFieldVisible('house_rules', $get)),

                                        Forms\Components\Repeater::make('faqs')->label('সচরাচর জিজ্ঞাসিত প্রশ্ন (FAQs)')
                                            ->schema([
                                                Forms\Components\TextInput::make('question')->required(),
                                                Forms\Components\Textarea::make('answer')->required(),
                                            ])->columns(2)->visible(fn (Get $get) => self::isFieldVisible('faqs', $get)),
                                    ]),

                                Forms\Components\Section::make('SEO')
                                    ->description('সার্চ ইঞ্জিনের জন্য মেটা ডেটা যোগ করুন।')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')->label('মেটা টাইটেল'),
                                        Forms\Components\Textarea::make('meta_description')->label('মেটা বর্ণনা')->rows(2),
                                        Forms\Components\TagsInput::make('meta_keywords')->label('মেটা কিওয়ার্ড'),
                                    ]),

                            ])->columnSpan(2),

                        // ডান পাশের সাইডবার (১/৩ অংশ)
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Section::make('মূল্য এবং প্রাপ্যতা (Pricing & Availability)')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('স্ট্যাটাস (Status)')
                                            ->options([
                                                'pending' => 'অপেক্ষারত (Pending)',
                                                'active' => 'সক্রিয় (Active)',
                                                'rented' => 'ভাড়া হয়ে গেছে (Rented)',
                                                'inactive' => 'নিষ্ক্রিয় (Inactive)',
                                                'sold_out' => 'বিক্রি হয়ে গেছে (Sold Out)',
                                            ])
                                            ->required()
                                            ->default('pending'),

                                        TextInput::make('rent_price')
                                            ->label('ভাড়া/মূল্য (Price)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('৳'),

                                        Forms\Components\Select::make('rent_type')
                                            ->label('ভাড়ার ধরণ (Rent Type)')
                                            ->options([
                                                'day' => 'দৈনিক (Day)',
                                                'week' => 'সাপ্তাহিক (Week)',
                                                'month' => 'মাসিক (Month)',
                                                'year' => 'বাৎসরিক (Year)',
                                            ])
                                            ->required()
                                            ->default('month')
                                            ->native(false), // <-- সুন্দর UI-এর জন্য

                                        TextInput::make('service_charge')
                                            ->label('সার্ভিস চার্জ (Service Charge)')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('৳'),

                                        TextInput::make('security_deposit')
                                            ->label('সিকিউরিটি ডিপোজিট (Security Deposit)')
                                            ->numeric()
                                            ->default(0)
                                            ->prefix('৳'),

                                        Forms\Components\ToggleButtons::make('is_negotiable')
                                            ->label('মূল্য নির্ধারণ (Price Negotiation)')
                                            ->options([
                                                'negotiable' => 'আলোচনা সাপেক্ষ',
                                                'fixed' => 'একদাম',
                                            ])
                                            ->colors([
                                                'negotiable' => 'info',
                                                'fixed' => 'primary',
                                            ])
                                            ->icons([
                                                'negotiable' => 'heroicon-o-chat-bubble-left-right',
                                                'fixed' => 'heroicon-o-lock-closed',
                                            ])
                                            ->inline() // <-- বাটনগুলোকে পাশাপাশি দেখাবে
                                            ->required()
                                            ->default('fixed'),

                                        DatePicker::make('available_from')
                                            ->label('কবে থেকে পাওয়া যাবে (Available From)')
                                            ->required(),
                                    ]),

                                Forms\Components\Section::make('সম্পর্কিত তথ্য (Associations)')
                                    ->schema([
                                        Forms\Components\Select::make('user_id')
                                            ->label('মালিক (Owner)')
                                            ->relationship('user', 'name')
                                            // শুধুমাত্র অ্যাডমিন প্যানেলেই এটি সার্চযোগ্য হবে
                                            ->searchable()
                                            ->preload()

                                            // ডিফল্ট মান হিসেবে লগইন করা ইউজারের আইডি সেট করা হচ্ছে
                                            ->default(auth()->id())
                                            ->required(),

                                        // === START: 'purpose' এর আকর্ষণীয় সংস্করণ ===
                                        Forms\Components\ToggleButtons::make('purpose')
                                            ->label('উদ্দেশ্য (Purpose)')
                                            ->options([
                                                'rent' => 'ভাড়া (Rent)',
                                                'sell' => 'বিক্রয় (Sell)',
                                            ])
                                            ->colors([
                                                'rent' => 'info',
                                                'sell' => 'success',
                                            ])
                                            ->icons([
                                                'rent' => 'heroicon-o-key',
                                                'sell' => 'heroicon-o-banknotes',
                                            ])
                                            ->inline() // বাটনগুলোকে পাশাপাশি দেখাবে
                                            ->required()
                                            ->default('rent'),
                                        // === END ===
                                    ]),

                                Forms\Components\Section::make('অবস্থান (Location)')
                                    ->schema([
                                        // Dependent Selects for Location
                                        Select::make('division_id')
                                            ->label('বিভাগ (Division)')
                                            ->relationship('division', 'bn_name')
                                            ->searchable()->preload()->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('district_id', null))
                                            ->required(),

                                        Select::make('district_id')
                                            ->label('জেলা (District)')
                                            ->options(fn (Get $get): Collection => District::query()
                                                ->where('division_id', $get('division_id'))
                                                ->pluck('bn_name', 'id'))
                                            ->searchable()->live()->preload()
                                            ->afterStateUpdated(fn (Set $set) => $set('upazila_id', null))
                                            ->required(),

                                        Select::make('upazila_id')
                                            ->label('উপজেলা (Upazila)')
                                            ->options(fn (Get $get): Collection => Upazila::query()
                                                ->where('district_id', $get('district_id'))
                                                ->pluck('bn_name', 'id'))
                                            ->searchable()->live()->preload()
                                            ->afterStateUpdated(fn (Set $set) => $set('union_id', null))
                                            ->required(),

                                        Select::make('union_id')
                                            ->label('ইউনিয়ন (Union)')
                                            ->options(fn (Get $get): Collection => Union::query()
                                                ->where('upazila_id', $get('upazila_id'))
                                                ->pluck('bn_name', 'id'))
                                            ->searchable()->preload()->nullable(),

                                        // Address Fields
                                        Textarea::make('address_street')->label('বাসা ও রাস্তার ঠিকানা (Street Address)')->required()->columnSpanFull(),
                                        TextInput::make('address_area')->label('এলাকা (Area)')->helperText('যেমন: ধানমন্ডি, গুলশান')->required(),
                                        TextInput::make('address_zipcode')->label('পোস্ট কোড (Post Code)')->numeric(),

                                        // Map & Coordinates
                                        TextInput::make('google_maps_location_link')->label('গুগল ম্যাপস লিংক (Google Maps Link)')->url()->columnSpanFull(),
                                        TextInput::make('latitude')->label('অক্ষাংশ (Latitude)')->numeric()->rule('regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'),
                                        TextInput::make('longitude')->label('দ্রাঘিমাংশ (Longitude)')->numeric()->rule('regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'),

                                    ])->columns(2), // পুরো সেকশনটিকে ২ কলামে ভাগ করা হলো

                                Forms\Components\Section::make('দৃশ্যমানতা ও স্ট্যাটাস (Visibility & Status)')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_available')
                                            ->label('ভাড়ার জন্য উপলব্ধ (Available)')
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->default(true),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('ফিচার্ড হিসেবে দেখান (Featured)')
                                            ->onColor('success')
                                            ->offColor('danger'),

                                        Forms\Components\Toggle::make('is_trending')
                                            ->label('ট্রেন্ডিং হিসেবে দেখান (Trending)')
                                            ->onColor('success')
                                            ->offColor('danger'),

                                        Forms\Components\Toggle::make('is_verified')
                                            ->label('যাচাইকৃত (Verified)')
                                            ->onColor('success')
                                            ->offColor('danger'),
                                    ])->columns(1), // ৪টি টগলকে সুন্দরভাবে পাশাপাশি দেখানো হলো

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
            ->defaultSort('created_at', 'desc')
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
                Tables\Actions\DeleteAction::make(),
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
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
