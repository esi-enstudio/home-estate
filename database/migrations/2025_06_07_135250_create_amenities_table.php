<?php

use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Lift", "Generator", "Gas Connection", "Parking"
            $table->string('slug')->unique();
            $table->string('icon_class')->nullable(); // For FontAwesome or other icon libraries
            $table->enum('type', ['facility', 'utility', 'safety', 'environment']); // Amenities গ্রুপ করার জন্য
            $table->boolean('show_on_homepage')->default(false); // হোমপেজে দেখানোর জন্য ফ্ল্যাগ
            $table->unsignedInteger('properties_count')->default(0);
            $table->timestamps();
        });

        // পিভট টেবিলের নামে সাধারণত দুটি মডেলের নাম singular এবং alphabetical order-এ থাকে
        Schema::create('amenity_property', function (Blueprint $table) {
            // কোনো foreignId এর জন্য id() লেখার প্রয়োজন নেই
            $table->foreignIdFor(Amenity::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Property::class)->constrained()->cascadeOnDelete();

            // প্রতিটি সুবিধার জন্য অতিরিক্ত তথ্য রাখার কলাম
            $table->string('details')->nullable(); // যেমন: Parking এর জন্য: "1 car", Gas এর জন্য: "Cylinder"
            $table->boolean('is_key_feature')->nullable()->default(false);

            // ডুপ্লিকেট ডেটা এড়ানোর জন্য দুটি কলামকে একসাথে Primary Key বানানো হলো
            $table->primary(['amenity_id', 'property_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
