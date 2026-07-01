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
        if(!Schema::hasTable('whats_app_bookings')) {
            Schema::create('whats_app_bookings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('service_id');
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->unsignedBigInteger('location_id')->nullable();
                $table->date('date')->index()->nullable();
                $table->string('schedule')->index()->nullable();

                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_bookings');
    }
};
