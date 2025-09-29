<?php

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
        Schema::create('featured_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name_bn'); // এলাকার নাম (বাংলা)
            $table->string('name_en'); // এলাকার নাম (ইংরেজি, properties.address_area এর সাথে মিলবে)
            $table->string('slug')->unique();
            $table->boolean('is_featured')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_locations');
    }
};
