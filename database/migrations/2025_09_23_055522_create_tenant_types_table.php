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
        Schema::create('tenant_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // যেমন: Family, Bachelor, Office
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('property_tenant_type', function (Blueprint $table) {
            $table->foreignIdFor(App\Models\Property::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\TenantType::class)->constrained()->cascadeOnDelete();
            $table->primary(['property_id', 'tenant_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_types');
    }
};
