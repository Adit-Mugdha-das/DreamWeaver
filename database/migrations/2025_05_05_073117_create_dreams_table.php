<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dreams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('emotion_summary')->nullable(); // For Gemini result
            $table->text('interpretation')->nullable();    // For GPT/Gemini interpretation
            $table->text('story')->nullable();             // For dream-to-story
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dreams');
    }
};
