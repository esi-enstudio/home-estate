<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('header.light_logo', 'Add Logo');
        $this->migrator->add('header.dark_logo', 'Add Logo');
        $this->migrator->add('header.signin_button_text', 'Add Button Text');
        $this->migrator->add('header.register_button_text', 'Add Button Text');
    }
};
