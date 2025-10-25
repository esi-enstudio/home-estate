<?php

namespace Database\Seeders;

use App\Models\TenantType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // সিডার চালানোর আগে টেবিল খালি করে নেওয়া
        Schema::disableForeignKeyConstraints();
        TenantType::truncate();
        Schema::enableForeignKeyConstraints();

        $types = [
            [
                'name_en' => 'Family',
                'name_bn' => 'পরিবার',
                'icon_class' => 'fas fa-users',
                'description' => 'স্বামী-স্ত্রী, সন্তানসহ পরিবারের বসবাসের জন্য উপযুক্ত ফ্ল্যাট বা বাড়ি।'
            ],
            [
                'name_en' => 'Bachelor (Male)',
                'name_bn' => 'ব্যাচেলর (পুরুষ)',
                'icon_class' => 'fas fa-male',
                'description' => 'শুধুমাত্র কর্মজীবী বা ছাত্র (পুরুষ) এককভাবে বা শেয়ার করে থাকার জন্য।'
            ],
            [
                'name_en' => 'Bachelor (Female)',
                'name_bn' => 'ব্যাচেলর (মহিলা)',
                'icon_class' => 'fas fa-female',
                'description' => 'শুধুমাত্র কর্মজীবী বা ছাত্রী (মহিলা) এককভাবে বা শেয়ার করে থাকার জন্য।'
            ],
            [
                'name_en' => 'Sublet',
                'name_bn' => 'সাবলেট',
                'icon_class' => 'fas fa-house-user',
                'description' => 'একটি ফ্ল্যাটের অংশ (সাধারণত একটি রুম) অন্য কারো সাথে শেয়ার করে থাকার জন্য।'
            ],
            [
                'name_en' => 'Office',
                'name_bn' => 'অফিস',
                'icon_class' => 'fas fa-briefcase',
                'description' => 'প্রাতিষ্ঠানিক বা ব্যবসায়িক কার্যক্রম পরিচালনার জন্য ব্যবহৃত স্থান।'
            ],
            [
                'name_en' => 'Commercial',
                'name_bn' => 'কমার্শিয়াল',
                'icon_class' => 'fas fa-store',
                'description' => 'দোকান, শোরুম, রেস্টুরেন্ট বা অন্য যেকোনো বাণিজ্যিক কার্যক্রমের জন্য।'
            ],
            [
                'name_en' => 'Guest House',
                'name_bn' => 'গেস্ট হাউস',
                'icon_class' => 'fas fa-hotel',
                'description' => 'স্বল্পমেয়াদে অতিথি, পর্যটক বা কর্পোরেট অতিথিদের থাকার জন্য।'
            ],
            [
                'name_en' => 'Students',
                'name_bn' => 'ছাত্র/ছাত্রী',
                'icon_class' => 'fas fa-user-graduate',
                'description' => 'বিশেষভাবে ছাত্র বা ছাত্রীদের জন্য মেস বা হোস্টেল।'
            ],
        ];

        foreach ($types as $type) {
            TenantType::create([
                'name_en' => $type['name_en'],
                'name_bn' => $type['name_bn'],
                'icon_class' => $type['icon_class'],
                'description' => $type['description'],
            ]);
        }
    }
}
