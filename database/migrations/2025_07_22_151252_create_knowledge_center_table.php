<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeCenterTable extends Migration
{
    public function up()
    {
        Schema::create('knowledge_center', function (Blueprint $table) {
            $table->id(); 
            $table->string('title');
            $table->enum('status', ['Ready', 'Not Ready']);
            $table->string('size');
            $table->string('file_path');
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('knowledge_center');
    }
}

