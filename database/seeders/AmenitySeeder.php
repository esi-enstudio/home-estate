<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // সিডার চালানোর আগে টেবিল খালি করে নেওয়া ভালো অভ্যাস
        Schema::disableForeignKeyConstraints();
        Amenity::truncate();
        Schema::enableForeignKeyConstraints();

        $amenities = [
            // --- Facilities (সুযোগ-সুবিধা) ---
            ['name_bn' => 'লিফট', 'name_en' => 'Elevator/Lift', 'type' => 'facility'],
            ['name_bn' => 'গাড়ি পার্কিং', 'name_en' => 'Car Parking', 'type' => 'facility'],
            ['name_bn' => 'বাইক পার্কিং', 'name_en' => 'Bike Parking', 'type' => 'facility'],
            ['name_bn' => 'এয়ার কন্ডিশনার (AC)', 'name_en' => 'Air Conditioner', 'type' => 'facility'],
            ['name_bn' => 'ইন্টারকম', 'name_en' => 'Intercom', 'type' => 'facility'],
            ['name_bn' => 'কমিউনিটি হল', 'name_en' => 'Community Hall', 'type' => 'facility'],
            ['name_bn' => 'সুইমিং পুল', 'name_en' => 'Swimming Pool', 'type' => 'facility'],
            ['name_bn' => 'জিমন্যাশিয়াম', 'name_en' => 'Gymnasium', 'type' => 'facility'],
            ['name_bn' => 'বারান্দা', 'name_en' => 'Balcony', 'type' => 'facility'],
            ['name_bn' => 'ছাদ ব্যবহারের সুবিধা', 'name_en' => 'Rooftop Access', 'type' => 'facility'],
            ['name_bn' => 'শিশুদের খেলার জায়গা', 'name_en' => 'Children\'s Play Area', 'type' => 'facility'],
            ['name_bn' => 'সার্ভিস কোয়ার্টার', 'name_en' => 'Servant Quarter', 'type' => 'facility'],
            ['name_bn' => 'গেস্ট রুম', 'name_en' => 'Guest Room', 'type' => 'facility'],
            ['name_bn' => 'পোশাক শুকানোর জায়গা', 'name_en' => 'Cloth Drying Area', 'type' => 'facility'],
            ['name_bn' => 'হুইলচেয়ার ব্যবহারের সুবিধা', 'name_en' => 'Wheelchair Accessible', 'type' => 'facility'],
            ['name_bn' => 'পোষা প্রাণী রাখা যাবে', 'name_en' => 'Pets Allowed', 'type' => 'facility'],

            // --- Utilities (ইউটিলিটি) ---
            ['name_bn' => 'গ্যাস সংযোগ (পাইপলাইন)', 'name_en' => 'Piped Gas Connection', 'type' => 'utility'],
            ['name_bn' => 'গ্যাস সংযোগ (সিলিন্ডার)', 'name_en' => 'Cylinder Gas', 'type' => 'utility'],
            ['name_bn' => 'জেনারেটর', 'name_en' => 'Generator', 'type' => 'utility'],
            ['name_bn' => 'আইপিএস সংযোগ', 'name_en' => 'IPS Connection', 'type' => 'utility'],
            ['name_bn' => 'পানি বিশুদ্ধকরণ ফিল্টার', 'name_en' => 'Water Purifier', 'type' => 'utility'],
            ['name_bn' => 'গরম পানির ব্যবস্থা (গিজার)', 'name_en' => 'Geyser/Hot Water', 'type' => 'utility'],
            ['name_bn' => 'ইন্টারনেট/ওয়াইফাই', 'name_en' => 'Internet/WiFi', 'type' => 'utility'],
            ['name_bn' => 'ডিশ/স্যাটেলাইট টিভি', 'name_en' => 'Cable/Satellite TV', 'type' => 'utility'],
            ['name_bn' => 'ময়লা ফেলার নির্দিষ্ট জায়গা', 'name_en' => 'Waste Disposal', 'type' => 'utility'],

            // --- Safety (নিরাপত্তা) ---
            ['name_bn' => 'সিসিটিভি নিরাপত্তা', 'name_en' => 'CCTV Security', 'type' => 'safety'],
            ['name_bn' => 'নিরাপত্তা প্রহরী', 'name_en' => 'Security Guard', 'type' => 'safety'],
            ['name_bn' => 'অগ্নি নির্বাপণ ব্যবস্থা', 'name_en' => 'Fire Extinguisher', 'type' => 'safety'],
            ['name_bn' => 'ইমার্জেন্সি এক্সিট', 'name_en' => 'Emergency Exit', 'type' => 'safety'],
            ['name_bn' => 'স্মোক ডিটেক্টর', 'name_en' => 'Smoke Detector', 'type' => 'safety'],
            ['name_bn' => 'বজ্রপাত নিরোধক', 'name_en' => 'Lightning Arrester', 'type' => 'safety'],

            // --- Environment (পারিপার্শ্বিক সুবিধা) ---
            ['name_bn' => 'কাছাকাছি মসজিদ', 'name_en' => 'Nearby Mosque', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি মাদ্রাসা', 'name_en' => 'Nearby Madrasha', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি পার্ক', 'name_en' => 'Nearby Park', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি স্কুল/কলেজ', 'name_en' => 'Nearby School/College', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি হাসপাতাল', 'name_en' => 'Nearby Hospital', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি বাজার/দোকান', 'name_en' => 'Nearby Market/Shops', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি বাস স্ট্যান্ড', 'name_en' => 'Nearby Bus Stand', 'type' => 'environment'],
            ['name_bn' => 'কাছাকাছি রেল স্টেশন', 'name_en' => 'Nearby Rail Station', 'type' => 'environment'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create([
                'name_bn' => $amenity['name_bn'],
                'name_en' => $amenity['name_en'],
                'type' => $amenity['type'],
                // 'icon_path' => null, // প্রয়োজনে আইকনের পাথ এখানে যোগ করতে পারেন
            ]);
        }
    }
}
