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
            $table->string('name_en'); // শুধু name এর পরিবর্তে name_en
            $table->string('name_bn'); // বাংলা নামের জন্য
            $table->string('slug')->unique();
            $table->string('icon_class')->nullable(); // আইকনের জন্য (e.g., Font Awesome class)
            $table->text('description')->nullable(); // সংক্ষিপ্ত বর্ণনার জন্য
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
