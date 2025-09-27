<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(mixed $union_id)
 */
class Union extends Model
{
    protected $fillable = ['upazila_id','name','bn_name'];

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
