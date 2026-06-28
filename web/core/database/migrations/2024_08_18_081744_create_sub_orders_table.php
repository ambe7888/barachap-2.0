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
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('service_id')->nullable()->index();
            $table->unsignedBigInteger('job_post_id')->nullable()->index();
            $table->unsignedBigInteger('provider_id')->nullable()->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->unsignedBigInteger('admin_id')->nullable()->index();
            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->date('date')->nullable()->index();
            $table->string('schedule')->nullable()->index();
            $table->double('basic_price', 10, 2)->default(0);
            $table->double('sub_total', 10, 2)->default(0);
            $table->double('tax',10, 2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->string('commission_type')->nullable();
            $table->double('commission_charge', 10, 2)->nullable();
            $table->double('commission_amount', 10, 2)->nullable();
            $table->longText('order_note')->nullable();
            $table->integer('complete_request')->default(0);
            $table->integer('status')->default(0)->comment('0=pending, 1=active, 2=completed, 3=delivered, 4=cancelled, 5=declined, 6=refunded')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_orders');
    }
};
