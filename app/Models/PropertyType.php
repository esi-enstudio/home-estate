<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method static create(array $array)
 * @method static find(mixed $originalCategoryId)
 * @method static withCount(string $string)
 * @method static count()
 * @method static orderBy(string $string)
 * @method static whereIn(string $string, \Illuminate\Support\Collection $favoritedTypeIds)
 * @method static updateOrCreate(array $array, array $array1)
 * @method static truncate()
 * @property int|mixed $properties_count
 */
class PropertyType extends Model implements HasMedia
{
    use HasCustomSlug, InteractsWithMedia;

    protected $fillable = ['name_en', 'name_bn', 'slug', 'properties_count'];

    /**
     * Define the media collections.
     * অরিজিনাল ছবি না রাখার নির্দেশনা এখানেই দিতে হবে।
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('property_type_image') // আপনার ফিলামেন্ট ফিল্ডের কালেকশনের নাম
            ->singleFile(); // এই কালেকশনে শুধুমাত্র একটি ফাইল থাকবে
    }


    /**
     * Define which field to use for slug generation.
     */
    public function getSluggableField(): string
    {
        return 'name_en';
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->name_en} - {$this->name_bn}";
    }

    /**
     * Get all the properties for the PropertyType.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
