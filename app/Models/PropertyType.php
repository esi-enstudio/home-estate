<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method static create(array $array)
 * @method static find(mixed $originalCategoryId)
 * @method static withCount(string $string)
 * @method static count()
 * @property int|mixed $properties_count
 */
class PropertyType extends Model implements HasMedia
{
    use HasCustomSlug, InteractsWithMedia;

    protected $fillable = ['name_en', 'name_bn', 'slug', 'properties_count','icon_path'];

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image') // আমরা 'image' নামে একটি কালেকশন তৈরি করছি
        ->singleFile(); // প্রতিটি টাইপের জন্য একটি মাত্র ছবি থাকবে
    }
}
