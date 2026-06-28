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
        Schema::table('refunded_orders', function (Blueprint $table) {
            $table->integer('user_type')->default(0)->comment('0=provider, 1=client, 2=admin')->index();
            $table->integer('current_suborder_status')->default(0)->comment('0=pending, 1=active, 2=completed, 3=delivered, 4=cancelled, 5=declined, 6=refunded')->index();
            $table->integer('type')->default(0)->comment('0=cancel, 1=decline')->index();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunded_orders', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'current_suborder_status','type']);

        });
    }
};
