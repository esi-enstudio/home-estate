<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('homepage.badge_text', 'Add text');
        $this->migrator->add('homepage.title', 'Add title');
        $this->migrator->add('homepage.subtitle', 'Add subtitle');
        $this->migrator->add('homepage.button_text', 'Add button text');
        $this->migrator->add('homepage.button_link', 'Add button link');
    }
};
