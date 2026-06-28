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
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->bigInteger('gateway_id');
            $table->bigInteger('user_id');
            $table->longText('gateway_fields');
            $table->longText('note')->nullable();
            $table->string('image')->nullable();
            $table->integer('status')->default(1)->comment('1=pending, 2=complete, 3=cancel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};
