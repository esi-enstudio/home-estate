<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Amenity;
use App\Models\SpaceOverview;
use App\Models\TenantType;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // শুধু ফ্যাক্টরিকে কল করলেই হবে, বাকি কাজ (রিলেশনশিপ অ্যাটাচ করা) ফ্যাক্টরি নিজেই করে নেবে।
        Property::factory()
            ->count(50) // আপনি যতগুলো প্রপার্টি চান
            ->create();
    }
}
