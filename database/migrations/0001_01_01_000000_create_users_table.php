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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->text('bio')->nullable(); // Changed to text for longer biographies
            $table->string('designation')->nullable();
            $table->boolean('show_on_our_inspiration_page')->default(false);
            $table->json('social_links')->nullable();
            $table->unsignedInteger('reviews_count')->default(0);
            $table->decimal('average_rating', 2, 1)->default(0.0);
            $table->enum('status', ['active', 'inactive', 'banned', 'pending'])->default('active'); // Added 'pending' for initial state
            $table->enum('identity_status', ['unverified', 'pending', 'approved', 'rejected'])
                ->default('unverified');
            $table->text('identity_rejection_reason')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable(); // For OTP verification
            $table->string('password');
            $table->string('avatar_url')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
