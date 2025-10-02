<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HeaderSettings extends Settings
{
    public ?string $light_logo;
    public ?string $dark_logo;
    public ?string $signin_button_text;
    public ?string $register_button_text;

    public static function group(): string
    {
        return 'header';
    }
}
