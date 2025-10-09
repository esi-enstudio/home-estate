<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MaintenanceSettings extends Settings
{
    public ?string $maintenance_title;
    public ?string $maintenance_subtitle;
    public array $social_links;

    public ?string $coming_soon_title;
    public ?string $coming_soon_subtitle;
    public ?string $launch_date; // আমরা ডেট পিকার ব্যবহার করব

    public static function group(): string
    {
        return 'maintenance';
    }
}
