<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('telegram_users', function (Blueprint $table) {
            $table->primary('chat_id');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('chat_id')->references('chat_id')->on('telegram_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
        });

        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('telegram_users', function (Blueprint $table) {
            $table->id()->first();
        });
    }
};
