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
        Schema::table('live_chat_messages', function (Blueprint $table) {
            // Modify the 'from_user' column comment
            $table->integer('from_user')
                ->comment('1 = client, 2 = provider')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_chat_messages', function (Blueprint $table) {
            // Optionally, revert the comment to the old one
            $table->integer('from_user')
                ->comment('1 = user, 2 = admin')
                ->change();
        });
    }
};
