<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, true $true)
 * @method static make()
 */
class MenuItem extends Model
{
    protected $guarded = [];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Recursively loads all descendants (children, grandchildren, etc.).
     *
     * @return HasMany
     */
    public function recursiveChildren(): HasMany
    {
        // 'children' রিলেশনশিপটি লোড করো, এবং তাদেরও 'recursiveChildren' লোড করো
        return $this->children()->with('recursiveChildren');
    }
}
