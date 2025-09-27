<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'Lift', 'type' => 'facility'],
            ['name' => 'Generator', 'type' => 'utility'],
            ['name' => 'Parking', 'type' => 'facility'],
            ['name' => 'Gas Connection', 'type' => 'utility'],
            ['name' => 'CCTV Security', 'type' => 'safety'],
            ['name' => 'Security Guard', 'type' => 'safety'],
            ['name' => 'Rooftop Access', 'type' => 'facility'],
            ['name' => 'Pets Allowed', 'type' => 'facility'],
            ['name' => 'Nearby Mosque', 'type' => 'environment'],
            ['name' => 'Nearby Park', 'type' => 'environment'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create([
                'name' => $amenity['name'],
                'slug' => Str::slug($amenity['name']),
                'type' => $amenity['type'],
            ]);
        }
    }
}
