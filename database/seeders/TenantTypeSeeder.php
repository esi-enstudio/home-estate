<?php

namespace Database\Seeders;

use App\Models\TenantType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Family', 'slug' => 'family'],
            ['name' => 'Bachelor', 'slug' => 'bachelor'],
            ['name' => 'Sublet', 'slug' => 'sublet'],
            ['name' => 'Office', 'slug' => 'office'],
            ['name' => 'Commercial Space', 'slug' => 'commercial-space'],
        ];

        foreach ($types as $type) {
            TenantType::create($type);
        }
    }
}
