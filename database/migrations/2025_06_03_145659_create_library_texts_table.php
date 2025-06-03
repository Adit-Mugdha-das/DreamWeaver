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
        Schema::create('library_texts', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // Title of the text
            $table->string('author');          // Author name
            $table->enum('type', ['poem', 'story', 'myth'])->default('story'); // Category
            $table->text('content');           // The actual story/poem
            $table->timestamps();              // Created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_texts');
    }
};
