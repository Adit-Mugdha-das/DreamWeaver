<?php

namespace App\Http\Controllers;

use App\Models\DreamDNA;
use App\Models\Dream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DreamDNAController extends Controller
{
    /**
     * Show the Dream DNA visualization page
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Get or create DNA
        $dna = DreamDNA::firstOrCreate(
            ['user_id' => $user->id],
            [
                'emotion_genes' => [],
                'symbol_genes' => [],
                'color_genes' => [],
                'archetype_genes' => [],
                'total_dreams_analyzed' => 0,
                'evolution_score' => 0,
                'mutations' => [],
            ]
        );

        // Recompute if needed (if new dreams exist or stale)
        $shouldRecompute = $this->shouldRecompute($dna, $user);
        
        if ($shouldRecompute) {
            $this->computeDNA($dna, $user);
        }

        $helixData = $dna->getHelixData();
        $healthScore = $dna->getHealthScore();
        $profile = $dna->getPersonalityProfile();

        return view('dreams.dna.show', compact('dna', 'helixData', 'healthScore', 'profile'));
    }

    /**
     * Recompute DNA manually (AJAX endpoint)
     */
    public function recompute(Request $request)
    {
        $user = $request->user();
        $dna = DreamDNA::where('user_id', $user->id)->firstOrFail();
        
        $this->computeDNA($dna, $user);

        return response()->json([
            'success' => true,
            'message' => 'Dream DNA recomputed successfully!',
            'dna' => [
                'helixData' => $dna->getHelixData(),
                'healthScore' => $dna->getHealthScore(),
                'profile' => $dna->getPersonalityProfile(),
            ]
        ]);
    }

    /**
     * Check if DNA needs recomputing
     */
    private function shouldRecompute(DreamDNA $dna, $user): bool
    {
        // If never computed
        if (!$dna->last_computed_at) {
            return true;
        }

        // If new dreams added since last computation
        $dreamCount = Dream::where('user_id', $user->id)->count();
        if ($dreamCount > $dna->total_dreams_analyzed) {
            return true;
        }

        // If last computed more than 7 days ago
        if ($dna->last_computed_at->diffInDays(now()) > 7) {
            return true;
        }

        return false;
    }

    /**
     * Core DNA computation logic
     */
    private function computeDNA(DreamDNA $dna, $user): void
    {
        $dreams = Dream::where('user_id', $user->id)
            ->whereNotNull('emotion_summary')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($dreams->isEmpty()) {
            return;
        }

        // Initialize gene counters
        $emotionFreq = [];
        $symbolFreq = [];
        $colorFreq = [];
        $archetypeFreq = [];

        foreach ($dreams as $dream) {
            // Parse emotions from emotion_summary field
            // emotion_summary can be a JSON string or plain text
            $emotionData = $dream->emotion_summary;
            
            if (!empty($emotionData)) {
                // Try to decode as JSON first
                $emotions = json_decode($emotionData, true);
                
                if (is_array($emotions)) {
                    // If it's JSON array of emotion objects
                    foreach ($emotions as $emotion) {
                        $emotionName = strtolower($emotion['name'] ?? $emotion);
                        $emotionFreq[$emotionName] = ($emotionFreq[$emotionName] ?? 0) + 1;
                    }
                } else {
                    // If it's plain text, try to extract emotion words
                    $emotionName = strtolower(trim($emotionData));
                    if (!empty($emotionName)) {
                        $emotionFreq[$emotionName] = ($emotionFreq[$emotionName] ?? 0) + 1;
                    }
                }
            }
            
            // Also check emotion_category if available
            if (!empty($dream->emotion_category)) {
                $categoryName = strtolower($dream->emotion_category);
                $emotionFreq[$categoryName] = ($emotionFreq[$categoryName] ?? 0) + 1;
            }

            // Get dream text content (use content field, fallback to title if needed)
            $dreamText = $dream->content ?? $dream->title ?? '';

            // Extract symbols from content (basic keyword extraction)
            if (!empty($dreamText)) {
                $symbols = $this->extractSymbols($dreamText);
                foreach ($symbols as $symbol) {
                    $symbolFreq[$symbol] = ($symbolFreq[$symbol] ?? 0) + 1;
                }

                // Extract colors from content
                $colors = $this->extractColors($dreamText);
                foreach ($colors as $color) {
                    $colorFreq[$color] = ($colorFreq[$color] ?? 0) + 1;
                }

                // Extract archetypes
                $archetypes = $this->extractArchetypes($dreamText);
                foreach ($archetypes as $archetype) {
                    $archetypeFreq[$archetype] = ($archetypeFreq[$archetype] ?? 0) + 1;
                }
            }
        }

        // Sort by frequency to determine dominants (highest first)
        arsort($emotionFreq);
        arsort($symbolFreq);
        arsort($colorFreq);
        arsort($archetypeFreq);

        // Build gene arrays (top 10 for each)
        $emotionGenes = $this->buildGeneArray($emotionFreq, 10);
        $symbolGenes = $this->buildGeneArray($symbolFreq, 10);
        $colorGenes = $this->buildGeneArray($colorFreq, 8);
        $archetypeGenes = $this->buildGeneArray($archetypeFreq, 8);

        // Determine dominants (now correctly sorted by frequency)
        $dominantEmotion = array_key_first($emotionFreq) ?? 'neutral';
        $dominantColor = array_key_first($colorFreq) ?? 'blue';
        $dominantArchetype = array_key_first($archetypeFreq) ?? 'seeker';

        // Calculate evolution score (based on diversity and dream count)
        $evolutionScore = $this->calculateEvolutionScore(
            count($emotionGenes),
            count($symbolGenes),
            count($colorGenes),
            count($archetypeGenes),
            $dreams->count()
        );

        // Detect mutations (significant changes from previous DNA)
        $mutations = $this->detectMutations($dna, $emotionGenes, $symbolGenes);

        // Update DNA
        $dna->update([
            'emotion_genes' => $emotionGenes,
            'symbol_genes' => $symbolGenes,
            'color_genes' => $colorGenes,
            'archetype_genes' => $archetypeGenes,
            'total_dreams_analyzed' => $dreams->count(),
            'dominant_emotion' => $dominantEmotion,
            'dominant_color' => $dominantColor,
            'dominant_archetype' => $dominantArchetype,
            'evolution_score' => $evolutionScore,
            'mutations' => $mutations,
            'last_computed_at' => now(),
        ]);
    }

    /**
     * Extract dream symbols from description
     */
    private function extractSymbols(string $text): array
    {
        $symbolKeywords = [
            'water', 'fire', 'sky', 'ocean', 'forest', 'mountain', 'door', 'window',
            'mirror', 'stairs', 'bridge', 'house', 'car', 'animal', 'bird', 'snake',
            'spider', 'butterfly', 'key', 'book', 'light', 'shadow', 'moon', 'sun',
            'star', 'tree', 'flower', 'clock', 'ring', 'sword', 'crown', 'mask',
            'labyrinth', 'garden', 'cave', 'tower', 'castle', 'child', 'stranger'
        ];

        $found = [];
        $lowerText = strtolower($text);

        foreach ($symbolKeywords as $symbol) {
            if (stripos($lowerText, $symbol) !== false) {
                $found[] = $symbol;
            }
        }

        return $found;
    }

    /**
     * Extract color references
     */
    private function extractColors(string $text): array
    {
        $colorKeywords = [
            'red', 'blue', 'green', 'yellow', 'purple', 'orange', 'pink', 'black',
            'white', 'gray', 'brown', 'gold', 'silver', 'violet', 'indigo', 'cyan'
        ];

        $found = [];
        $lowerText = strtolower($text);

        foreach ($colorKeywords as $color) {
            if (stripos($lowerText, $color) !== false) {
                $found[] = $color;
            }
        }

        return $found;
    }

    /**
     * Extract Jungian archetypes
     */
    private function extractArchetypes(string $text): array
    {
        $archetypePatterns = [
            'hero' => ['hero', 'warrior', 'champion', 'fighter', 'brave'],
            'shadow' => ['shadow', 'dark', 'fear', 'enemy', 'monster'],
            'anima' => ['feminine', 'woman', 'mother', 'goddess', 'sister'],
            'animus' => ['masculine', 'man', 'father', 'king', 'brother'],
            'sage' => ['wise', 'teacher', 'mentor', 'guide', 'elder'],
            'trickster' => ['fool', 'jester', 'clown', 'mischief', 'chaos'],
            'child' => ['child', 'innocent', 'youth', 'baby', 'young'],
            'seeker' => ['journey', 'quest', 'search', 'explore', 'wander'],
        ];

        $found = [];
        $lowerText = strtolower($text);

        foreach ($archetypePatterns as $archetype => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($lowerText, $keyword) !== false) {
                    $found[] = $archetype;
                    break; // Only count once per archetype
                }
            }
        }

        return $found;
    }

    /**
     * Build gene array with frequency and percentage
     */
    private function buildGeneArray(array $freq, int $limit): array
    {
        arsort($freq);
        $total = array_sum($freq);
        $genes = [];

        $count = 0;
        foreach ($freq as $gene => $frequency) {
            if ($count >= $limit) break;
            
            $genes[] = [
                'name' => $gene,
                'frequency' => $frequency,
                'percentage' => $total > 0 ? round(($frequency / $total) * 100, 1) : 0,
            ];
            
            $count++;
        }

        return $genes;
    }

    /**
     * Calculate evolution score
     */
    private function calculateEvolutionScore(
        int $emotionCount,
        int $symbolCount,
        int $colorCount,
        int $archetypeCount,
        int $dreamCount
    ): int {
        $diversityScore = min(40, ($emotionCount + $symbolCount + $colorCount + $archetypeCount) * 2);
        $volumeScore = min(30, $dreamCount * 3);
        $maturityScore = min(30, ($emotionCount > 5 && $symbolCount > 5) ? 30 : 15);

        return (int) round($diversityScore + $volumeScore + $maturityScore);
    }

    /**
     * Detect mutations (significant changes in DNA)
     */
    private function detectMutations(DreamDNA $dna, array $newEmotions, array $newSymbols): array
    {
        $mutations = $dna->mutations ?? [];
        
        // Check for new dominant emotion
        $oldDominant = $dna->dominant_emotion;
        $newDominant = $newEmotions[0]['name'] ?? 'neutral';
        
        if ($oldDominant && $oldDominant !== $newDominant) {
            $mutations[] = [
                'type' => 'emotion_shift',
                'from' => $oldDominant,
                'to' => $newDominant,
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        // Keep only last 10 mutations
        return array_slice($mutations, -10);
    }
}
