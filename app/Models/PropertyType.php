<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static find(mixed $originalCategoryId)
 * @method static withCount(string $string)
 * @method static count()
 * @property int|mixed $properties_count
 */
class PropertyType extends Model
{
    use HasCustomSlug;

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
}
