<?php

use App\Models\District;
use App\Models\Division;
use App\Models\PropertyType;
use App\Models\Tenant;
use App\Models\Union;
use App\Models\Upazila;
use App\Models\User;
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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('Owner of the property')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(PropertyType::class)->constrained();

            // --- Core Information ---
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('property_code')->unique()->comment('Human-readable unique ID like BHARA-101');
            $table->enum('purpose', ['rent', 'sell'])->default('rent')->comment('Purpose of the listing');

            // --- Pricing Details ---
            $table->unsignedInteger('rent_price')->comment('Monthly rent amount');
            $table->enum('rent_type', ['day', 'week', 'month', 'year'])->default('month');
            $table->unsignedInteger('service_charge')->nullable()->default(0);
            $table->unsignedInteger('security_deposit')->nullable()->default(0);
            $table->enum('is_negotiable', ['negotiable', 'fixed'])->default('fixed');

            // --- Property Specifications ---
            $table->unsignedSmallInteger('bedrooms');
            $table->unsignedSmallInteger('bathrooms');
            $table->unsignedSmallInteger('balconies')->default(0);
            $table->unsignedInteger('size_sqft')->comment('Area in square feet');
            $table->string('floor_level')->nullable()->comment('e.g., 5th floor of 12');
            $table->unsignedSmallInteger('total_floors')->nullable();
            $table->string('facing_direction')->nullable()->comment('e.g., South, North-East');
            $table->year('year_built')->nullable();

            // --- Location ---
            $table->foreignIdFor(Division::class)->constrained();
            $table->foreignIdFor(District::class)->constrained();
            $table->foreignIdFor(Upazila::class)->constrained();
            $table->foreignIdFor(Union::class)->nullable()->constrained();
            $table->text('address_street');
            $table->string('address_area'); // e.g., Dhanmondi, Gulshan
            $table->string('address_zipcode')->nullable();
            $table->string('google_maps_location_link')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->text('house_rules')->nullable();
            $table->json('faqs')->nullable();
            $table->json('additional_features')->nullable();

            // --- Media ---
            $table->string('video_url')->nullable()->comment('Youtube or Vimeo video link');

            // --- Status & Visibility ---
            $table->enum('status', ['pending', 'active', 'rented', 'inactive', 'sold_out', 'upcoming'])->default('pending');
            $table->boolean('is_available')->default(true);
            $table->date('available_from');
            $table->boolean('is_featured')->default(false)->comment('For "Featured" badge');
            $table->boolean('is_trending')->default(false)->comment('For "Trending" badge');
            $table->boolean('is_verified')->default(false)->comment('Verified by platform admin');

            // --- System & SEO ---
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->decimal('average_rating', 2, 1)->default(0.0);
            $table->integer('score')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property');
    }
};
