<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static has(string $string)
 * @method static count()
 * @method static whereHas(string $string, \Closure $param)
 * @method static where(string $string, mixed $phone)
 * @method static withCount(string $string)
 * @property mixed $id
 */
class User extends Authenticatable implements HasMedia, FilamentUser, MustVerifyEmail, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'designation',
        'show_on_our_inspiration_page',
        'social_links',
        'reviews_count',
        'average_rating',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'password',
        'avatar_url',
        'identity_status', 'identity_rejection_reason',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * এটিই আপনার সমস্যার চূড়ান্ত সমাধান করবে।
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed', // <-- পাসওয়ার্ডের জন্য এটি সেরা অনুশীলন

        // Eloquent কে বলে দেওয়া হচ্ছে যে 'social_links' কলামটিকে
        // সেভ করার সময় JSON-এ এবং পড়ার সময় অ্যারে-তে রূপান্তর করতে হবে।
        'social_links' => 'array',
    ];

    public function favoriteProperties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'property_user')->withTimestamps();
    }

    /**
     * Get the user's designation in Bengali based on their role or activity.
     * This is an Eloquent Accessor, accessed via the `$user->designation_bn` property.
     *
     * @return Attribute
     */
    protected function designation(): Attribute
    {
        return Attribute::make(
            get: function () {
                // ধাপ ১: প্রথমে চেক করুন ব্যবহারকারী কি সুপার অ্যাডমিন?
                // (hasRole মেথডটি spatie/laravel-permission প্যাকেজের)
                if ($this->hasRole('super_admin')) {
                    return 'সুপার অ্যাডমিন';
                }

                // ধাপ ২: যদি সুপার অ্যাডমিন না হয়, তাহলে চেক করুন তিনি কি একজন বাড়ির মালিক?
                // 'properties' রিলেশনশিপে কোনো রেকর্ড আছে কিনা তা চেক করা হচ্ছে।
                // 'exists()' মেথডটি 'count()' এর চেয়ে বেশি পারফরম্যান্স-ফ্রেন্ডলি।
                if ($this->properties()->exists()) {
                    return 'বাড়ির মালিক';
                }

                // ধাপ ৩: যদি উপরের কোনোটিই না হয়, তাহলে ডিফল্ট পদবি রিটার্ন করুন।
                return 'সম্মানিত গ্রাহক';
            }
        );
    }

    /**
     * Get all of the properties for the User.
     * একজন ইউজারের অনেকগুলো প্রপার্টি থাকতে পারে।
     * এই রিলেশনশিপটি 'trustedOwners' গণনার জন্য অপরিহার্য।
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function identityVerifications(): HasMany
    {
        return $this->hasMany(IdentityVerification::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // ১. 'admin' প্যানেলের জন্য:
        // শুধুমাত্র 'Super Admin' রোল থাকা ব্যবহারকারীরাই অ্যাক্সেস পাবে।
        if ($panel->getId() === 'superadmin') {
            return $this->hasRole('super_admin');
        }

        // ২. 'app' প্যানেলের জন্য:
        // সকল রেজিস্টার্ড ব্যবহারকারী (সুপার-অ্যাডমিন সহ) অ্যাক্সেস পাবে।
        // যেহেতু কোনো বিশেষ শর্ত নেই, তাই আমরা শুধু true রিটার্ন করব।
        if ($panel->getId() === 'app') {
            return true;
        }

        // ৩. অন্য কোনো অজানা প্যানেলের জন্য ডিফল্টভাবে অ্যাক্সেস দেওয়া হবে না।
        return false;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }
}
