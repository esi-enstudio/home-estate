<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contact.breadcrumb_title', '');
        $this->migrator->add('contact.breadcrumb_subtitle', '');
        $this->migrator->add('contact.sales_title', '');
        $this->migrator->add('contact.sales_description', '');
        $this->migrator->add('contact.sales_phone', '');
        $this->migrator->add('contact.support_title', '');
        $this->migrator->add('contact.support_description', '');
        $this->migrator->add('contact.contact_emails', []);
        $this->migrator->add('contact.contact_phones', []);
        $this->migrator->add('contact.contact_address', '');
        $this->migrator->add('contact.image_one', '');
        $this->migrator->add('contact.image_two', '');
        $this->migrator->add('contact.map_iframe_url', '');
    }
};
