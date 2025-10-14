<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $meta_title;
    public ?string $meta_description;
    public ?array $meta_keywords;
    public ?string $og_image; // Open Graph Image

    public static function group(): string
    {
        return 'site';
    }
}
