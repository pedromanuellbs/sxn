<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // One-to-one with users
            $table->string('knowledge_source')->nullable(); // Placeholder for future FK
            $table->string('assistant_name');
            $table->string('tone')->default('Casual');
            $table->string('additional_instruction')->nullable();
            $table->string('language')->default('English');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
