<?php

namespace App\Models;

// --- প্রয়োজনীয় ক্লাসগুলো ইম্পোর্ট করা হলো ---
use App\Traits\HasCustomSlug;
use App\Traits\TracksViews;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Property extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, HasCustomSlug;

    protected $fillable = [
        'user_id',
        'property_type_id',
        'title',
        'slug',
        'description',
        'property_code',
        'purpose',
        'rent_price',
        'rent_type',
        'service_charge',
        'security_deposit',
        'is_negotiable',
        'bedrooms',
        'bathrooms',
        'balconies',
        'size_sqft',
        'floor_level',
        'total_floors',
        'facing_direction',
        'year_built',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'address_street',
        'address_area',
        'address_zipcode',
        'google_maps_location_link',
        'latitude',
        'longitude',
        'house_rules',
        'faqs',
        'additional_features',
        'video_url',
        'status',
        'is_available',
        'available_from',
        'is_featured',
        'is_trending',
        'is_verified',
        'views_count',
        'reviews_count',
        'average_rating',
        'score',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Boolean Casts
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_verified' => 'boolean',

        // Date & Array Casts
        'available_from' => 'date',
        'additional_features' => 'array',
        'faqs' => 'array',

        // Decimal Casts for precision
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'average_rating' => 'decimal:1',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(414)
            ->height(267)
            ->format('webp')
            ->quality(85)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->width(832)
            ->height(472)
            ->format('webp')
            ->quality(85)
            ->nonQueued();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($property) {
            $prefix = 'PROP-'; // আপনার পছন্দের প্রিফিক্স

            do {
                // ৮ অক্ষরের একটি র‍্যান্ডম, বড় হাতের অক্ষর ও সংখ্যার স্ট্রিং তৈরি করুন
                $randomPart = strtoupper(Str::random(10));
                $code = $prefix . $randomPart;
            } while (self::where('property_code', $code)->exists());

            $property->property_code = $code;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug'; // Use slug instead of id in routes
    }

    public function getSluggableField(): string
    {
        return 'title'; // Use slug instead of id in routes
    }

    // --- Accessors & Mutators ---

    // --- নতুন সংযোজন: সম্পূর্ণ ঠিকানা ---
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(', ', array_filter([
                $this->address_street,
                $this->address_area,
                $this->district?->name, // Optional chaining for safety
                $this->division?->name,
            ]))
        );
    }

    // ... Route Model Binding এবং Media Library মেথড অপরিবর্তিত ...

    // ====================================================================
    // রিলেশনশিপগুলো (RELATIONSHIPS)
    // ====================================================================
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function propertyType(): BelongsTo { return $this->belongsTo(PropertyType::class); }
    public function division(): BelongsTo { return $this->belongsTo(Division::class); }
    public function district(): BelongsTo { return $this->belongsTo(District::class); }
    public function upazila(): BelongsTo { return $this->belongsTo(Upazila::class); }
    public function union(): BelongsTo { return $this->belongsTo(Union::class); }

    public function tenantTypes(): BelongsToMany { return $this->belongsToMany(TenantType::class, 'property_tenant_type'); }
    public function amenities(): BelongsToMany { return $this->belongsToMany(Amenity::class)->withPivot('details', 'is_key_feature')->withTimestamps(); }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function reviews(): HasMany { return $this->hasMany(Review::class)->whereNull('parent_id'); }

    // --- নতুন সংযোজন: রিপ্লাই সহ সকল রিভিউ ---
    public function allReviews(): HasMany { return $this->hasMany(Review::class); }

//    public function wishlistedByUser(): BelongsToMany { return $this->belongsToMany(User::class, 'wishlists')->withTimestamps(); }

    // ====================================================================
    // লোকাল স্কোপ (LOCAL SCOPES)
    // ====================================================================

    // --- নতুন সংযোজন: Active এবং Featured স্কোপ ---
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active')->where('is_available', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Get a formatted and filtered list of property features for display.
     * Only features with a non-empty value will be returned.
     * ভবিষ্যতে যদি আপনাকে নতুন কোনো ফিচার (যেমন total_floors) যোগ করতে হয়, তাহলে আপনাকে শুধু Property মডেলের $featureMap অ্যারেতে একটি নতুন লাইন যোগ করতে হবে। ব্লেড ফাইলে কোনো পরিবর্তনের প্রয়োজনই হবে না!
     * @return array
     */
    public function getFormattedFeatures(): array
    {
        // এখানে আমরা আমাদের সকল ফিচার, তাদের লেবেল, আইকন এবং একক (unit) ম্যাপ করে রাখব
        $featureMap = [
            'bedrooms'         => ['label' => 'Bedrooms', 'icon' => 'bed', 'suffix' => ''],
            'bathrooms'        => ['label' => 'Bathrooms', 'icon' => 'bathtub', 'suffix' => ''],
            'balconies'        => ['label' => 'Balconies', 'icon' => 'corporate_fare', 'suffix' => ''],
            'floor_level'      => ['label' => 'Floor', 'icon' => 'door_sliding', 'suffix' => ''],
            'size_sqft'        => ['label' => 'Size', 'icon' => 'straighten', 'suffix' => ' sqft'],
            'facing_direction' => ['label' => 'Facing', 'icon' => 'explore', 'suffix' => ''],
            'year_built'       => ['label' => 'Built Year', 'icon' => 'calendar_today', 'suffix' => ''],
        ];

        $features = [];

        foreach ($featureMap as $attribute => $details) {
            // যদি এই প্রপার্টিতে ওই নির্দিষ্ট ফিচারের মান থাকে (null, empty string, or 0 নয়)
            if (!empty($this->{$attribute})) {
                $features[] = [
                    'label' => $details['label'],
                    'value' => $this->{$attribute} . $details['suffix'], // মানের সাথে একক যোগ করা হলো
                    'icon'  => $details['icon'],
                ];
            }
        }

        return $features;
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'property_user');
    }
}
