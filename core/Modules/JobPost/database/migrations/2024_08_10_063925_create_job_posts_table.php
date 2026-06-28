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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('sub_category_id')->nullable();
            $table->bigInteger('child_category_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->string('gallery_images')->nullable();
            $table->double('budget')->default(0);
            $table->integer('view')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->integer('status')->default(0);
            $table->boolean('is_published')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
            // Index
            $table->index(['category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
