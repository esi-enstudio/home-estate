<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('maintenance.maintenance_title', 'Add title');
        $this->migrator->add('maintenance.maintenance_subtitle', 'Add subtitle');
        $this->migrator->add('maintenance.social_links', []);
        $this->migrator->add('maintenance.coming_soon_title', 'Add title');
        $this->migrator->add('maintenance.coming_soon_subtitle', 'Add subtitle');
        $this->migrator->add('maintenance.launch_date', '');
    }
};
