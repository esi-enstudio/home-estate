<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContactSettings extends Settings
{
    // Breadcrumb Section
    public string $breadcrumb_title;
    public string $breadcrumb_subtitle;

    // Sales Team Section
    public string $sales_title;
    public string $sales_description;
    public string $sales_phone;

    // Support Section
    public string $support_title;
    public string $support_description;

    // Contact Info Boxes
    public array $contact_emails;
    public array $contact_phones;
    public string $contact_address;

    // Images
    public ?string $image_one; // contact-us-img-01.jpg
    public ?string $image_two; // contact-us-img-02.jpg

    // Google Map
    public string $map_iframe_url;

    public static function group(): string
    {
        return 'contact';
    }
}
