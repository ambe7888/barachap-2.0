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
        Schema::create('order_cancellation_policies', function (Blueprint $table) {
            $table->id();
            $table->string("fine_type");
            $table->decimal('amount')->default(0);
            $table->string("available_type");
            $table->integer("time_in_min")->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_cancellation_policies');
    }
};
