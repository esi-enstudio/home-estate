<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $id)
 * @method static find(int $reviewId)
 */
class Review extends Model
{
    protected $fillable = ['property_id','user_id','title','body','rating','status','likes_count','dislikes_count','favorites_count'];

    public function user(): BelongsTo
    { return $this->belongsTo(User::class); }

    public function property(): BelongsTo
    { return $this->belongsTo(Property::class); }
    public function replies(): HasMany
    { return $this->hasMany(Review::class, 'parent_id'); }

    public function authUserReaction(): HasOne
    {
        return $this->hasOne(ReviewReaction::class)->where('user_id', auth()->id());
    }
}
