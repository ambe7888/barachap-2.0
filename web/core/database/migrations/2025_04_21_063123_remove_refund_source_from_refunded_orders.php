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
            $table->dropColumn('refund_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunded_orders', function (Blueprint $table) {
            $table->integer('refund_source')->default(0)->comment('0=order_cancel,1=refund_request');
        });
    }
};
