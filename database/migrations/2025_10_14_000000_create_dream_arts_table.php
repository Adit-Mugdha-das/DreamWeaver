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
        Schema::create('dream_arts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dream_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('prompt')->nullable(); // AI-generated art prompt
            $table->string('image_path')->nullable(); // Path to generated/uploaded image
            $table->string('style')->default('surreal'); // Art style: surreal, abstract, realistic, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dream_arts');
    }
};
