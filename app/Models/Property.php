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

/**
 * @method static where(string $string, string $string1)
 * @method static whereIn(string $string, string[] $array)
 * @method static count()
 * @method static trending()
 */
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
        'meta_keywords' => 'array',

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
     * This now includes both dedicated columns and JSON-based additional features.
     *
     * @return array
     */
    public function getFormattedFeatures(): array
    {
        // --- ধাপ ১: মূল ফিচারগুলো আগের মতোই ম্যাপ করা ---
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
            if (!empty($this->{$attribute})) {
                $features[] = [
                    'label' => $details['label'],
                    'value' => $this->{$attribute} . $details['suffix'],
                    'icon'  => $details['icon'],
                ];
            }
        }

        // === START: নতুন এবং ডাইনামিক অংশ ===
        // --- ধাপ ২: 'additional_features' JSON কলাম থেকে ডেটা যোগ করা ---
        // প্রথমে নিশ্চিত করুন যে additional_features খালি নয় এবং এটি একটি অ্যারে
        if (!empty($this->additional_features) && is_array($this->additional_features)) {
            foreach ($this->additional_features as $featureName => $featureValue) {
                // শুধুমাত্র যদি নাম এবং মান উভয়ই থাকে, তবেই যোগ করুন
                if (!empty($featureName) && !empty($featureValue)) {
                    $features[] = [
                        'label' => $featureName,
                        'value' => $featureValue,
                        'icon'  => 'check_circle', // সকল অতিরিক্ত ফিচারের জন্য একটি সুন্দর ডিফল্ট আইকন
                    ];
                }
            }
        }
        // === END: নতুন এবং ডাইনামিক অংশ ===

        return $features;
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'property_user');
    }

    /**
     * Scope a query to only include trending properties.
     * This calculates a "trending score" based on recent activity.
     *
     */
    public function scopeTrending(Builder $query): Builder
    {
        $sevenDaysAgo = now()->subDays(7)->toDateTimeString(); // <-- স্ট্রিং-এ রূপান্তরিত করা হলো

        return $query
            ->where('status', 'active')
            ->select('properties.*')
            // === START: মূল পরিবর্তন এখানে ===
            ->addSelect(
                DB::raw(
                    "(SELECT
                        (p.views_count * 1) +
                        (SELECT COUNT(*) FROM enquiries WHERE enquiries.property_id = p.id AND enquiries.created_at >= '{$sevenDaysAgo}') * 5 +
                        (SELECT COUNT(*) FROM property_user WHERE property_user.property_id = p.id AND property_user.created_at >= '{$sevenDaysAgo}') * 3 +
                        (CASE WHEN p.is_trending = 1 THEN 50 ELSE 0 END)
                    FROM properties as p WHERE p.id = properties.id)
                    as trending_score"
                )
            )
            // === END ===
            ->orderByDesc('trending_score');
    }
}
