<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('footer.join_title', 'Add Title');
        $this->migrator->add('footer.join_subtitle', 'Add Subtitle');
        $this->migrator->add('footer.join_button_text', 'Add Button Text');
        $this->migrator->add('footer.join_button_link', 'Add Button Link');
        $this->migrator->add('footer.company_menu', []);
        $this->migrator->add('footer.destinations_menu', []);
        $this->migrator->add('footer.location', 'Add Location');
        $this->migrator->add('footer.phone', 'Add Phone');
        $this->migrator->add('footer.email', 'Add Email');
        $this->migrator->add('footer.newsletter_title', 'Add Newsletter Title');
        $this->migrator->add('footer.newsletter_subtitle', 'Add Newsletter Subtitle');
        $this->migrator->add('footer.facebook_link', 'Add Social link');
        $this->migrator->add('footer.twitter_link', 'Add Social link');
        $this->migrator->add('footer.instagram_link', 'Add Social link');
        $this->migrator->add('footer.linkedin_link', 'Add Social link');
    }
};
