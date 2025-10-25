<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Emil Sadekin Islam',
            'email' => 'sadekinislam6@gmail.com',
            'phone' => '01732547755',
            'password' => Hash::make(3213),
        ]);

        $this->call([
           UserSeeder::class,
           PropertyTypeSeeder::class,
           AmenitySeeder::class,
           TenantSeeder::class,
           DivisionSeeder::class,
           DistrictSeeder::class,
           UpazilaSeeder::class,
           UnionSeeder::class,
           PropertySeeder::class,
        ]);
    }
}
