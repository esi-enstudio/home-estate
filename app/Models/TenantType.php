<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(string[] $array)
 * @method static withCount(string $string)
 * @method static truncate()
 */
class TenantType extends Model
{
    use HasCustomSlug, HasFactory;

    protected $fillable = ['name_en','name_bn','slug','icon_class','description'];


    /**
     * Define which field to use for slug generation.
     */
    public function getSluggableField(): string
    {
        return 'name_en';
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'property_tenant');
    }
}
