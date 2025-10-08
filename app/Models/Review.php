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
 * @method static distinct(string $string)
 * @method static findOrFail(int $reviewId)
 */
class Review extends Model
{
    protected $fillable = ['property_id','user_id', 'reply_to_id', 'title','body','rating','status','likes_count','dislikes_count','favorites_count','is_testimonial'];

    public function user(): BelongsTo
    { return $this->belongsTo(User::class); }

    public function property(): BelongsTo
    { return $this->belongsTo(Property::class); }

    /**
     * Recursively loads all approved replies (children, grandchildren, etc.).
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Review::class, 'parent_id')
            ->where('status', 'approved')
            ->with('user', 'replies', 'authUserReaction', 'replyTo'); // <-- রিকার্সিভ Eager Loading
    }

    /**
     * Get the parent review of a reply (the one being replied to).
     */
    public function replyTo(): BelongsTo
    {
        // একটি রিপ্লাই শুধুমাত্র একটি কমেন্টের উত্তরে হয়
        return $this->belongsTo(Review::class, 'reply_to_id')->with('user');
    }

    public function authUserReaction(): HasOne
    {
        return $this->hasOne(ReviewReaction::class)->where('user_id', auth()->id());
    }
}
