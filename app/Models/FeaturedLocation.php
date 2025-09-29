<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FeaturedLocation extends Model implements HasMedia
{
    use HasCustomSlug, InteractsWithMedia;

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug'; // Use slug instead of id in routes
    }

    public function getSluggableField(): string
    {
        return 'name_en'; // Use slug instead of id in routes
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_location_image')->singleFile();
    }

    // এই রিলেশনশিপটি 'name_en' কলামের সাথে 'properties.address_area' কে যুক্ত করবে
    public function properties()
    {
        return $this->hasMany(Property::class, 'address_area', 'name_en');
    }
}
