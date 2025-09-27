<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(mixed $upazila_id)
 */
class Upazila extends Model
{
    protected $fillable = ['district_id','name','bn_name'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function unions(): HasMany
    {
        return $this->hasMany(Union::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
