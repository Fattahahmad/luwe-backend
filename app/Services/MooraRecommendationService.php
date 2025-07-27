<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\Bahan;
use Illuminate\Support\Collection;

class MooraRecommendationService
{
    private $ingredientWeight = 0.7;  // 70% - faktor utama
    private $timeWeight = 0.3;        // 30% - faktor pendukung
    private $minimumScore = 0.3;      // threshold minimum
    private $maxResults = 10;         // top 10 results

    /**
     * Get MOORA-based recipe recommendations
     */
    public function getRecommendations($availableIngredients, $minTime, $maxTime)
    {
        // Step 1: Pre-filter eligible recipes
        $eligibleRecipes = $this->getEligibleRecipes($availableIngredients, $minTime, $maxTime);
        
        if ($eligibleRecipes->isEmpty()) {
            return [];
        }

        // Step 2: Calculate MOORA scores
        $recommendations = [];
        foreach ($eligibleRecipes as $recipe) {
            $scoreData = $this->calculateMooraScore($recipe, $availableIngredients, $minTime, $maxTime);
            
            // Only include recipes above minimum threshold
            if ($scoreData['moora_score'] >= $this->minimumScore) {
                $recommendations[] = array_merge([
                    'recipe' => $recipe->load(['user:id,name', 'images', 'bahans']),
                ], $scoreData);
            }
        }

        // Step 3: Sort by MOORA score (descending) and limit to top 10
        usort($recommendations, function($a, $b) {
            return $b['moora_score'] <=> $a['moora_score'];
        });

        return array_slice($recommendations, 0, $this->maxResults);
    }

    /**
     * Get recipes that meet basic criteria
     */
    private function getEligibleRecipes($availableIngredients, $minTime, $maxTime)
    {
        return Recipe::with(['bahans'])
            ->where('cooking_time', '>=', $minTime)
            ->where('cooking_time', '<=', $maxTime)
            ->whereHas('bahans', function($query) use ($availableIngredients) {
                $query->whereIn('bahans.id', $availableIngredients);
            })
            ->get();
    }

    /**
     * Calculate MOORA score for a recipe
     */
    private function calculateMooraScore($recipe, $availableIngredients, $minTime, $maxTime)
    {
        // Get recipe ingredient IDs
        $recipeIngredientIds = $recipe->bahans->pluck('id')->toArray();
        
        // Calculate ingredient matching
        $matchingIngredients = array_intersect($availableIngredients, $recipeIngredientIds);
        $matchingCount = count($matchingIngredients);
        $totalRecipeIngredients = count($recipeIngredientIds);
        
        // Kriteria 1: Ingredient Match Score (Benefit)
        $ingredientScore = $matchingCount / $totalRecipeIngredients;
        
        // Kriteria 2: Time Preference Score (Benefit)
        // Optimal time adalah di tengah range, dengan preferensi ke waktu yang lebih cepat
        $timeRange = $maxTime - $minTime;
        $midTime = ($minTime + $maxTime) / 2;
        
        if ($timeRange == 0) {
            $timeScore = 1.0; // Perfect if only one time option
        } else {
            // Distance from ideal time (closer to min is better)
            $timeDistance = abs($recipe->cooking_time - $minTime) / $timeRange;
            $timeScore = 1 - $timeDistance;
        }
        
        // MOORA Final Score
        $mooraScore = ($ingredientScore * $this->ingredientWeight) + 
                      ($timeScore * $this->timeWeight);
        
        // Get missing ingredients
        $missingIngredients = $this->getMissingIngredients($recipeIngredientIds, $availableIngredients);
        
        // Determine time efficiency level
        $timeEfficiency = $this->getTimeEfficiencyLevel($recipe->cooking_time, $minTime, $maxTime);
        
        return [
            'moora_score' => round($mooraScore, 3),
            'ingredient_score' => round($ingredientScore, 3),
            'time_score' => round($timeScore, 3),
            'match_percentage' => round($ingredientScore * 100),
            'matching_ingredients' => $matchingCount,
            'total_ingredients' => $totalRecipeIngredients,
            'missing_ingredients' => $missingIngredients,
            'time_efficiency' => $timeEfficiency,
            'cooking_time' => $recipe->cooking_time
        ];
    }

    /**
     * Get list of missing ingredient names
     */
    private function getMissingIngredients($recipeIngredientIds, $availableIngredients)
    {
        $missingIds = array_diff($recipeIngredientIds, $availableIngredients);
        
        if (empty($missingIds)) {
            return [];
        }
        
        return Bahan::whereIn('id', $missingIds)->pluck('name')->toArray();
    }

    /**
     * Get time efficiency level description
     */
    private function getTimeEfficiencyLevel($cookingTime, $minTime, $maxTime)
    {
        $range = $maxTime - $minTime;
        $position = ($cookingTime - $minTime) / ($range ?: 1);
        
        if ($position <= 0.33) {
            return 'Sangat Cepat';
        } elseif ($position <= 0.66) {
            return 'Sedang';
        } else {
            return 'Agak Lama';
        }
    }

    /**
     * Get recommendation statistics
     */
    public function getRecommendationStats($availableIngredients, $minTime, $maxTime)
    {
        $eligibleRecipes = $this->getEligibleRecipes($availableIngredients, $minTime, $maxTime);
        $totalEligible = $eligibleRecipes->count();
        $totalRecipes = Recipe::whereBetween('cooking_time', [$minTime, $maxTime])->count();
        
        $ingredientStats = [
            'total_available' => count($availableIngredients),
            'recipes_analyzed' => $totalEligible,
            'total_in_timerange' => $totalRecipes
        ];

        $scoreDistribution = [];
        $aboveThreshold = 0;
        foreach ($eligibleRecipes as $recipe) {
            $scoreData = $this->calculateMooraScore($recipe, $availableIngredients, $minTime, $maxTime);
            if ($scoreData['moora_score'] >= $this->minimumScore) {
                $aboveThreshold++;
                $scoreDistribution[] = round($scoreData['moora_score'], 3);
            }
        }
        
        return [
            'ingredient_stats' => $ingredientStats,
            'score_distribution' => [
                'qualifying_recipes' => $aboveThreshold,
                'avg_score' => count($scoreDistribution) > 0 ? round(array_sum($scoreDistribution) / count($scoreDistribution), 3) : 0,
                'max_score' => count($scoreDistribution) > 0 ? max($scoreDistribution) : 0,
                'min_score' => count($scoreDistribution) > 0 ? min($scoreDistribution) : 0
            ],
            'moora_config' => [
                'ingredient_weight' => $this->ingredientWeight,
                'time_weight' => $this->timeWeight,
                'minimum_threshold' => $this->minimumScore,
                'max_results' => 10
            ]
        ];
    }
}
