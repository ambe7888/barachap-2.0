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
        if(Schema::hasTable('services'))
        {
            Schema::table('services', function (Blueprint $table) {
                if (!Schema::hasColumn('services', 'staffs_id')) {
                    $table->string('staffs_id')->nullable();
                }
                if (!Schema::hasColumn('services', 'disable_staff')) {
                    $table->boolean('disable_staff')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['staffs_id', 'disable_staff']);
        });
    }
};
