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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->double('sub_total', 10, 2);
            $table->double('tax', 10, 2);
            $table->double('total', 10, 2);
            $table->string('coupon_code')->nullable()->index();
            $table->string('coupon_type')->nullable();
            $table->double('coupon_amount', 10, 2)->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_status')->nullable()->index();
            $table->string('transaction_id')->nullable()->index();
            $table->string("invoice_number", 50)->unique()->nullable()->index();
            $table->string('payment_attachment')->nullable();
            $table->integer('status')->default(0)->comment('0=pending, 1=active, 2=completed, 3=delivered, 4=cancelled')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
