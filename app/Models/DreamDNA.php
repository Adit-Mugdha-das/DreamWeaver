<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DreamDNA extends Model
{
    use HasFactory;

    protected $table = 'dream_dna';

    protected $fillable = [
        'user_id',
        'emotion_genes',
        'symbol_genes',
        'color_genes',
        'archetype_genes',
        'total_dreams_analyzed',
        'dominant_emotion',
        'dominant_color',
        'dominant_archetype',
        'evolution_score',
        'mutations',
        'last_computed_at',
    ];

    protected $casts = [
        'emotion_genes' => 'array',
        'symbol_genes' => 'array',
        'color_genes' => 'array',
        'archetype_genes' => 'array',
        'mutations' => 'array',
        'last_computed_at' => 'datetime',
    ];

    /**
     * User relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the DNA helix visualization data
     */
    public function getHelixData(): array
    {
        return [
            'emotions' => $this->emotion_genes ?? [],
            'symbols' => $this->symbol_genes ?? [],
            'colors' => $this->color_genes ?? [],
            'archetypes' => $this->archetype_genes ?? [],
            'evolution' => $this->evolution_score,
            'totalDreams' => $this->total_dreams_analyzed,
        ];
    }

    /**
     * Calculate DNA health score (0-100)
     */
    public function getHealthScore(): int
    {
        if ($this->total_dreams_analyzed < 3) {
            return 0;
        }

        $diversity = $this->calculateDiversity();
        $frequency = min(100, $this->total_dreams_analyzed * 5);
        $evolution = $this->evolution_score;

        return (int) round(($diversity + $frequency + $evolution) / 3);
    }

    /**
     * Calculate genetic diversity
     */
    private function calculateDiversity(): int
    {
        $emotionCount = count($this->emotion_genes ?? []);
        $symbolCount = count($this->symbol_genes ?? []);
        $colorCount = count($this->color_genes ?? []);
        $archetypeCount = count($this->archetype_genes ?? []);

        $total = $emotionCount + $symbolCount + $colorCount + $archetypeCount;
        
        return min(100, $total * 2);
    }

    /**
     * Get DNA personality profile
     */
    public function getPersonalityProfile(): array
    {
        $dominant = $this->dominant_emotion ?? 'neutral';
        
        $profiles = [
            'fear' => [
                'title' => 'The Vigilant Dreamer',
                'description' => 'Your subconscious is highly alert, processing challenges and preparing you for obstacles.',
                'color' => '#ef4444',
                'icon' => '🛡️'
            ],
            'joy' => [
                'title' => 'The Radiant Dreamer',
                'description' => 'Your dreams are filled with light and positivity, reflecting inner contentment.',
                'color' => '#f59e0b',
                'icon' => '☀️'
            ],
            'sadness' => [
                'title' => 'The Reflective Dreamer',
                'description' => 'Your subconscious dives deep into emotions, processing and healing.',
                'color' => '#3b82f6',
                'icon' => '🌙'
            ],
            'anger' => [
                'title' => 'The Passionate Dreamer',
                'description' => 'Your dreams channel intense energy and transformative power.',
                'color' => '#dc2626',
                'icon' => '🔥'
            ],
            'surprise' => [
                'title' => 'The Explorer Dreamer',
                'description' => 'Your subconscious thrives on novelty and unexpected discoveries.',
                'color' => '#8b5cf6',
                'icon' => '✨'
            ],
            'love' => [
                'title' => 'The Harmonious Dreamer',
                'description' => 'Your dreams are woven with connection, compassion, and unity.',
                'color' => '#ec4899',
                'icon' => '💖'
            ],
            'neutral' => [
                'title' => 'The Balanced Dreamer',
                'description' => 'Your subconscious maintains equilibrium across many emotional dimensions.',
                'color' => '#6366f1',
                'icon' => '⚖️'
            ],
        ];

        return $profiles[$dominant] ?? $profiles['neutral'];
    }
}
