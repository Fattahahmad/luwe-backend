<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MooraRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecommendationController extends Controller
{
    private $mooraService;

    public function __construct(MooraRecommendationService $mooraService)
    {
        $this->mooraService = $mooraService;
    }

    /**
     * Get MOORA-based recipe recommendations
     */
    public function getMooraRecommendations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'available_ingredients' => 'required|array|min:1',
            'available_ingredients.*' => 'integer|exists:bahans,id',
            'min_cooking_time' => 'required|integer|min:1',
            'max_cooking_time' => 'required|integer|min:1|gte:min_cooking_time'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $recommendations = $this->mooraService->getRecommendations(
                $request->available_ingredients,
                $request->min_cooking_time,
                $request->max_cooking_time
            );

            $stats = $this->mooraService->getRecommendationStats(
                $request->available_ingredients,
                $request->min_cooking_time,
                $request->max_cooking_time
            );

            return response()->json([
                'success' => true,
                'message' => 'MOORA recommendations retrieved successfully',
                'search_criteria' => [
                    'ingredients_count' => count($request->available_ingredients),
                    'ingredient_names' => \App\Models\Bahan::whereIn('id', $request->available_ingredients)->pluck('name')->toArray(),
                    'time_range' => $request->min_cooking_time . '-' . $request->max_cooking_time . ' menit',
                    'moora_method' => 'Multi-Objective Optimization Ratio Analysis'
                ],
                'statistics' => $stats,
                'data' => $recommendations,
                'total_results' => count($recommendations)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get recommendations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recommendation explanation for educational purpose
     */
    public function getMethodExplanation(Request $request)
    {
        return response()->json([
            'success' => true,
            'method' => 'MOORA (Multi-Objective Optimization on the basis of Ratio Analysis)',
            'description' => 'Metode pengambilan keputusan yang mengoptimalkan beberapa kriteria sekaligus untuk memberikan rekomendasi resep terbaik.',
            'criteria' => [
                [
                    'name' => 'Kecocokan Bahan',
                    'weight' => '70%',
                    'type' => 'Benefit',
                    'description' => 'Persentase bahan yang tersedia dibanding total bahan resep'
                ],
                [
                    'name' => 'Efisiensi Waktu',
                    'weight' => '30%', 
                    'type' => 'Benefit',
                    'description' => 'Preferensi waktu memasak dalam rentang yang diinginkan'
                ]
            ],
            'calculation_steps' => [
                '1. Filter resep dalam rentang waktu dan minimal 1 bahan cocok',
                '2. Hitung skor kecocokan bahan (matching/total)',
                '3. Hitung skor efisiensi waktu (preferensi ke waktu minimal)',
                '4. Kalkulasi MOORA: (skor_bahan × 0.7) + (skor_waktu × 0.3)',
                '5. Filter hasil dengan skor minimal 0.3',
                '6. Urutkan dan ambil top 10 rekomendasi'
            ],
            'threshold' => [
                'minimum_score' => 0.3,
                'max_results' => 10,
                'required_criteria' => 'Minimal 1 bahan cocok dan waktu dalam rentang'
            ]
        ]);
    }

    /**
     * Test MOORA calculation with sample data
     */
    public function testMooraCalculation()
    {
        // Sample test data
        $sampleIngredients = [1, 2, 3]; // Sample ingredient IDs
        $minTime = 15;
        $maxTime = 30;

        try {
            $recommendations = $this->mooraService->getRecommendations(
                $sampleIngredients,
                $minTime,
                $maxTime
            );

            return response()->json([
                'success' => true,
                'message' => 'MOORA test calculation completed',
                'test_parameters' => [
                    'sample_ingredients' => $sampleIngredients,
                    'time_range' => $minTime . '-' . $maxTime . ' minutes'
                ],
                'results' => $recommendations,
                'algorithm_info' => [
                    'method' => 'MOORA',
                    'weights' => ['ingredient' => 0.7, 'time' => 0.3],
                    'threshold' => 0.3
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test calculation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
