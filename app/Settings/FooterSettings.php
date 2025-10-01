<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FooterSettings extends Settings
{
    // Join Section
    public string $join_title;
    public string $join_subtitle;
    public string $join_button_text;
    public string $join_button_link;

    // Company Menu
    public ?array $company_menu; // আমরা Repeater ব্যবহার করব

    // Destinations Menu
    public ?array $destinations_menu; // আমরা Repeater ব্যবহার করব

    // Reach Us
    public string $location;
    public string $phone;
    public string $email;

    // Newsletter
    public string $newsletter_title;
    public string $newsletter_subtitle;

    // Social Links
    public ?string $facebook_link;
    public ?string $twitter_link;
    public ?string $instagram_link;
    public ?string $linkedin_link;

    public static function group(): string
    {
        return 'footer';
    }
}
