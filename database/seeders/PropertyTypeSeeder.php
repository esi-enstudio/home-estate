<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['Apartment', 'এপার্টমেন্ট'],
            ['Duplex', 'ডুপ্লেক্স'],
            ['Penthouse', 'পেন্টহাউস'],
            ['Studio', 'স্টুডিও'],
            ['Commercial Space', 'কমার্শিয়াল স্পেস'],
            ['Warehouse', 'গুদামঘর'],
            ['Shop', 'দোকান'],
        ];

        foreach ($types as [$en, $bn]) {
            PropertyType::updateOrCreate(
                ['slug' => Str::slug($en)],
                [
                    'name_en' => $en,
                    'name_bn' => $bn,
                    'properties_count' => 0,
                ]
            );
        }
    }
}
