<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        // ফরেন কী চেক সাময়িকভাবে বন্ধ করা
        Schema::disableForeignKeyConstraints();

        // ১. টেবিল খালি করা
        // এটি টেবিলের সব ডেটা মুছে ফেলবে এবং auto-increment ID রিসেট করে দেবে
        PropertyType::truncate();

        // ফরেন কী চেক আবার চালু করা
        Schema::enableForeignKeyConstraints();

        $types = [
            // 🏠 Residential
            ['Apartment', 'এপার্টমেন্ট'],
            ['Flat', 'ফ্ল্যাট'],
            ['Studio Apartment', 'স্টুডিও এপার্টমেন্ট'],
            ['Duplex', 'ডুপ্লেক্স'],
            ['Penthouse', 'পেন্টহাউস'],
            ['Shared Room', 'শেয়ার্ড রুম'],
            ['Bachelor Room', 'ব্যাচেলর রুম'],
            ['Family Apartment', 'ফ্যামিলি এপার্টমেন্ট'],
            ['Sublet', 'সাবলেট'],
            ['House', 'বাড়ি'],
            ['Villa', 'ভিলা'],
            ['Cottage', 'কটেজ'],
            ['Mess', 'মেস'],

            // 🏢 Commercial
            ['Office Space', 'অফিস স্পেস'],
            ['Shop', 'দোকান'],
            ['Showroom', 'শোরুম'],
            ['Warehouse', 'গুদাম'],
            ['Factory', 'কারখানা'],
            ['Restaurant Space', 'রেস্টুরেন্ট স্পেস'],
            ['Café Space', 'ক্যাফে স্পেস'],
            ['Coaching Center', 'কোচিং সেন্টার'],
            ['Beauty Salon', 'বিউটি স্যালন'],
            ['Gym Space', 'জিম স্পেস'],
            ['Community Hall', 'কমিউনিটি হল'],

            // 🏡 Land & Plot
            ['Residential Plot', 'আবাসিক প্লট'],
            ['Commercial Plot', 'বাণিজ্যিক প্লট'],
            ['Agricultural Land', 'কৃষিজমি'],
            ['Industrial Land', 'শিল্প জমি'],

            // 🏨 Short-term / Hospitality
            ['Guest House', 'গেস্ট হাউস'],
            ['Hostel', 'হোস্টেল'],
            ['Hotel Room', 'হোটেল রুম'],
            ['Resort', 'রিসোর্ট'],
            ['Serviced Apartment', 'সার্ভিসড এপার্টমেন্ট'],

            // 🚗 Parking & Others
            ['Garage', 'গ্যারেজ'],
            ['Car Parking Space', 'গাড়ি পার্কিং স্পেস'],
            ['Storage Room', 'স্টোরেজ রুম'],
            ['Rooftop Space', 'ছাদ স্পেস'],
            ['Basement Space', 'বেসমেন্ট স্পেস'],
        ];

        foreach ($types as [$en, $bn]) {
            PropertyType::create([
                'name_en' => $en,
                'name_bn' => $bn,
                'properties_count' => 0,
            ]);
        }
    }
}
