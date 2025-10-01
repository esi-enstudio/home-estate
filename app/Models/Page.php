<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, true $true)
 */
class Page extends Model
{
    use HasCustomSlug;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content', 'is_published'];

    /**
     * The attributes that should be cast.
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug'; // Use slug instead of id in routes
    }

    public function getSluggableField(): string
    {
        return 'title'; // Use slug instead of id in routes
    }

    /**
     * Interact with the page's content.
     * এটিই আপনার সমস্যার চূড়ান্ত সমাধান করবে।
     *
     * @return Attribute
     */
    protected function content(): Attribute
    {
        return Attribute::make(
        // --- GET (ডেটা দেখানোর সময়) ---
        // Blade-এ যখন $page->content কল করা হবে, তখন এটি কাজ করবে।
        // এটি নিশ্চিত করবে যে ডাবল এনকোডেড ডেটা সঠিকভাবে প্রদর্শিত হচ্ছে।
            get: fn (?string $value) => html_entity_decode($value),

            // --- SET (ডেটা সেভ করার সময়) ---
            // Filament থেকে ডেটা সেভ করার সময় এটি কাজ করবে।
            // html_entity_decode() এখানে নিশ্চিত করবে যে ভুলবশত এনকোড হয়ে আসা ডেটা
            // ডাটাবেজে সেভ হওয়ার আগেই আবার সাধারণ HTML-এ রূপান্তরিত হচ্ছে।
            set: fn (?string $value) => html_entity_decode($value),
        );
    }
}
