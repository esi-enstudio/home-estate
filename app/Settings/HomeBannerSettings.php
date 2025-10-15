<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomeBannerSettings extends Settings
{
    public ?string $badge_text;
    public ?string $title_main;
    public ?string $title_highlighted;
    public ?string $subtitle;
    public ?string $button_text;
    public ?string $button_link;

    public static function group(): string
    {
        return 'home_banner';
    }
}
