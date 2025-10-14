<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.meta_title', '');
        $this->migrator->add('site.meta_description', '');
        $this->migrator->add('site.meta_keywords', []);
        $this->migrator->add('site.og_image', '');
    }
};
