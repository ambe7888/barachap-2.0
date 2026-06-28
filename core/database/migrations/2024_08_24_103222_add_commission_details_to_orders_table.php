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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('commission_type')->nullable()->after('payment_attachment');
            $table->decimal('commission_charge', 10, 2)->nullable()->after('commission_type');
            $table->decimal('commission_amount', 10, 2)->nullable()->after('commission_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['commission_type', 'commission_charge', 'commission_amount']);
        });
    }
};
