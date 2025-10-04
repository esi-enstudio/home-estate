<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static has(string $string)
 * @method static count()
 * @method static whereHas(string $string, \Closure $param)
 * @method static where(string $string, mixed $phone)
 */
class User extends Authenticatable implements HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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
        'social_links',
        'reviews_count',
        'average_rating',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'password',
        'avatar_url',
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
     * Get all of the properties for the User.
     * একজন ইউজারের অনেকগুলো প্রপার্টি থাকতে পারে।
     * এই রিলেশনশিপটি 'trustedOwners' গণনার জন্য অপরিহার্য।
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }
}
