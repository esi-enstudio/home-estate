<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, int $int)
 * @method static find(mixed $division_id)
 */
class Division extends Model
{
    protected $fillable = ['name','bn_name'];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
