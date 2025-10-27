<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('home_banner.badge_text', 'বাসা খোঁজার ঝামেলা শেষ। এক ক্লিকে ভৈরবের বাসা');
        $this->migrator->add('home_banner.title_main', 'ভৈরবের প্রথম ও একমাত্র অনলাইন');
        $this->migrator->add('home_banner.title_highlighted', 'বাসা ভাড়া প্ল্যাটফর্ম');
        $this->migrator->add('home_banner.subtitle', 'এখন ঘরে বসেই খুঁজে নিন আপনার পছন্দের বাসা — দ্রুত, সহজ ও নির্ভরযোগ্যভাবে।');
        $this->migrator->add('home_banner.button_text', 'আপনার বাসা যুক্ত করুন');
        $this->migrator->add('home_banner.button_link', 'listings/create');
    }
};
