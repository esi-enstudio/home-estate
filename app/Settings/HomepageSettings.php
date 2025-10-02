<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSettings extends Settings
{
    public ?string $badge_text;
    public ?string $title;
    public ?string $subtitle;
    public ?string $button_text;
    public ?string $button_link;

    public static function group(): string
    {
        return 'homepage';
    }
}
