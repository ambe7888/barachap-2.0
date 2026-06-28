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
            $table->integer('add_fee')->default(0)->comment('0=no , 1=yes ')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunded_orders', function (Blueprint $table) {
            $table->dropColumn('add_fee');
        });
    }
};
