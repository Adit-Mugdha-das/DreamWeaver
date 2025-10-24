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
        Schema::create('dream_dna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Emotion Genes (JSON array of emotion frequencies)
            $table->json('emotion_genes')->nullable();
            
            // Symbol Genes (JSON array of recurring symbols/archetypes)
            $table->json('symbol_genes')->nullable();
            
            // Color Genes (JSON array of dominant colors)
            $table->json('color_genes')->nullable();
            
            // Archetype Genes (JSON array of recurring archetypes)
            $table->json('archetype_genes')->nullable();
            
            // DNA Statistics
            $table->integer('total_dreams_analyzed')->default(0);
            $table->string('dominant_emotion')->nullable();
            $table->string('dominant_color')->nullable();
            $table->string('dominant_archetype')->nullable();
            
            // DNA Evolution Score (0-100)
            $table->integer('evolution_score')->default(0);
            
            // DNA Mutations (tracks significant changes)
            $table->json('mutations')->nullable();
            
            // Last computed timestamp
            $table->timestamp('last_computed_at')->nullable();
            
            $table->timestamps();
            
            // Ensure one DNA per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dream_dna');
    }
};
