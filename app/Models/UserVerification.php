<?php

namespace App\Models;

use EightyNine\Approvals\Models\ApprovableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, mixed $id)
 */
class UserVerification extends ApprovableModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_certificate',
        'passport',
        'nid',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // অপশনাল: কাস্টম লজিক যোগ করুন, যেমন ভেরিফাইড হলে ইভেন্ট ফায়ার
    protected static function booted(): void
    {
        static::created(function ($verification) {
            // নতুন রেকর্ড তৈরি হলে পেন্ডিং স্টেটাস সেট
            $verification->update(['status' => 'pending']);

            // অ্যাপ্রুভাল ফ্লো শুরু
            $verification->submitForApproval();
        });
    }
}
