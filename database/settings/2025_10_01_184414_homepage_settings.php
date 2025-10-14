<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('homepage.badge_text', 'বাসা খোঁজার ঝামেলা শেষ। এক ক্লিকে ভৈরবের বাসা');
        $this->migrator->add('homepage.title', 'ভৈরবের প্রথম ও একমাত্র অনলাইন বাসা ভাড়া প্ল্যাটফর্ম');
        $this->migrator->add('homepage.subtitle', 'এখন ঘরে বসেই খুঁজে নিন আপনার পছন্দের বাসা — দ্রুত, সহজ ও নির্ভরযোগ্যভাবে।');
        $this->migrator->add('homepage.button_text', 'আপনার বাসা যুক্ত করুন');
        $this->migrator->add('homepage.button_link', 'app/properties/create');
    }
};
