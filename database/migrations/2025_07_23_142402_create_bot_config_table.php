<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bot_config', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('name'); // Bot name
            $table->string('tone'); // e.g. Formal, Casual, Friendly
            $table->string('status')->default('inactive'); // optional: active/inactive
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bot_config');
    }
};

