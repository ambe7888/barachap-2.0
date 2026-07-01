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
        if(!Schema::hasTable('whats_app_booking_addons')) {
            // Create the whats_app_booking_addons table
            Schema::create('whats_app_booking_addons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('whats_app_booking_id');
                $table->unsignedBigInteger('addon_id');
                $table->double('quantity', 8, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_booking_addons');
    }
};
