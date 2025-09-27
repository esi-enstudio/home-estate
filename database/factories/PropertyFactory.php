<?php

namespace Database\Factories;

use App\Models\Amenity;
use App\Models\District;
use App\Models\Division;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\TenantType; // Tenant এর পরিবর্তে TenantType ব্যবহার করতে হবে
use App\Models\Union;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        // ====================================================================
        // পারফরম্যান্সের জন্য ID গুলো আগে থেকেই কালেকশনে নিয়ে আসা হলো
        // ====================================================================
        $userIds = User::pluck('id');
        $propertyTypeIds = PropertyType::pluck('id');

        // ====================================================================
        // বাস্তবসম্মত এবং সম্পর্কযুক্ত লোকেশন ডেটা তৈরি
        // ====================================================================
        $division = Division::with('districts.upazilas.unions')->inRandomOrder()->first();
        $district = $division->districts->isNotEmpty() ? $division->districts->random() : null;
        $upazila = $district && $district->upazilas->isNotEmpty() ? $district->upazilas->random() : null;
        $union = $upazila && $upazila->unions->isNotEmpty() ? $upazila->unions->random() : null;

        return [
            'user_id'           => $this->faker->randomElement($userIds),
            'property_type_id'  => $this->faker->randomElement($propertyTypeIds),
            // 'tenant_id' কলামটি বাদ দেওয়া হয়েছে

            'title'             => $title,
            'slug'              => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'description'       => $this->faker->paragraph(10),
            'property_code'     => 'BHARA-' . $this->faker->unique()->numberBetween(101, 999),
            'purpose'           => $this->faker->randomElement(['rent', 'sell']),

            'rent_price'        => $this->faker->numberBetween(50, 500) * 100, // 5000 থেকে 50000
            'rent_type'         => 'month',
            'service_charge'    => $this->faker->optional(0.7)->numberBetween(10, 50) * 100, // 1000 থেকে 5000
            'security_deposit'  => $this->faker->optional(0.8)->numberBetween(100, 500) * 100, // 10000 থেকে 50000
            'is_negotiable'     => $this->faker->randomElement(['negotiable', 'fixed']),

            'bedrooms'          => $this->faker->numberBetween(1, 5),
            'bathrooms'         => $this->faker->numberBetween(1, 4),
            'balconies'         => $this->faker->numberBetween(0, 3),
            'size_sqft'         => $this->faker->numberBetween(400, 3000),
            'floor_level'       => $this->faker->optional()->numberBetween(1, 15),
            'total_floors'      => $this->faker->optional()->numberBetween(5, 20),
            'facing_direction'  => $this->faker->optional()->randomElement(['North', 'South', 'East', 'West', 'North-East']),
            'year_built'        => $this->faker->optional()->year(),

            // সম্পর্কযুক্ত লোকেশন আইডি সেট করা হচ্ছে
            'division_id'       => $division->id,
            'district_id'       => $district?->id,
            'upazila_id'        => $upazila?->id,
            'union_id'          => $union?->id,

            'address_street'    => $this->faker->streetAddress(),
            'address_area'      => $this->faker->streetName(),
            'address_zipcode'   => $this->faker->optional()->postcode(),
            'latitude'          => $this->faker->optional()->latitude(23.6, 23.9), // ঢাকার আশপাশের ল্যাটিটিউড
            'longitude'         => $this->faker->optional()->longitude(90.3, 90.5), // ঢাকার আশপাশের লংগিটিউড

            'status'            => 'active', // ডিফল্ট 'active' রাখা ভালো, এতে সাইটে ডেটা দেখা যাবে
            'is_available'      => true,
            'available_from'    => $this->faker->dateTimeBetween('-1 months', '+2 months')->format('Y-m-d'),
            'is_featured'       => $this->faker->boolean(20), // ২০% প্রপার্টি featured হবে
            'is_trending'       => $this->faker->boolean(15),
            'is_verified'       => $this->faker->boolean(50),

            'views_count'       => $this->faker->numberBetween(100, 5000),
            'average_rating'    => $this->faker->randomFloat(1, 3, 5),
        ];
    }

    /**
     * ফ্যাক্টরির কনফিগারেশন, যা মডেল তৈরি হওয়ার পর কাজ করে
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Property $property) {
            // Amenities অ্যাটাচ করা হচ্ছে (many-to-many)
            $amenities = Amenity::inRandomOrder()->take(rand(5, 10))->get();
            foreach ($amenities as $amenity) {
                $property->amenities()->attach($amenity->id, [
                    // পিভট টেবিলের অতিরিক্ত কলামের জন্য ডেটা
                    'details' => $this->faker->optional()->sentence(3)
                ]);
            }

            // Tenant Types অ্যাটাচ করা হচ্ছে (many-to-many)
            // আপনার মডেলে tenant_types নামে রিলেশনশিপ থাকতে হবে
            $tenantTypes = TenantType::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $property->tenantTypes()->attach($tenantTypes);
        });
    }
}
