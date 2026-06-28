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
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->integer('status')->default(0)
                ->comment('0=pending, 1=active, 2=completed, 3=delivered, 4=cancelled, 5=declined')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->integer('status')->default(0)
                ->comment('0=pending, 1=active, 2=completed, 3=delivered, 4=cancelled, 5=declined')
                ->change();
        });
    }
};
