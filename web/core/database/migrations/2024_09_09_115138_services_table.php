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
        // Check if the 'services' table already exists
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('provider_id')->nullable();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('sub_category_id')->nullable();
                $table->unsignedBigInteger('child_category_id')->nullable();
                $table->string('title');
                $table->string('slug');
                $table->longText('description')->nullable();
                $table->string('image')->nullable();
                $table->string('gallery_images')->nullable();
                $table->text('video_url')->nullable();
                $table->decimal('price')->default(0);
                $table->decimal('discount_price')->default(0);
                $table->string('unit')->nullable();
                $table->integer('view')->default(0);
                $table->integer('sold_count')->default(0);
                $table->boolean('is_featured')->default(0);
                $table->boolean('status')->default(0);
                $table->boolean('is_published')->default(0);
                $table->timestamp('published_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
