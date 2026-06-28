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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->integer('type')->default(App\Enums\UserTypes::PROVIDER)->comment('0 = Provider, 1 = Client');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('email_verify_token')->nullable();
            $table->boolean('email_verified')->default(0)->comment('0=unverified, 1=verified');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('verified_status')->default(0)->comment('0:unverified, 1:verified');
            $table->boolean('terms_condition')->default(1)->comment('1=accepted, 0=not accepted');
            $table->boolean('status')->default(1)->comment('0=inactive, 1=active');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
