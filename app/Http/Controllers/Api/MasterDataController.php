<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Bahan;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Get all alats (cooking tools)
     */
    public function getAlats()
    {
        $alats = Alat::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Alats retrieved successfully',
            'data' => $alats
        ]);
    }

    /**
     * Get all bahans (ingredients)
     */
    public function getBahans()
    {
        $bahans = Bahan::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Bahans retrieved successfully',
            'data' => $bahans
        ]);
    }

    /**
     * Search alats
     */
    public function searchAlats(Request $request)
    {
        $query = Alat::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $alats = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Alats search results',
            'data' => $alats
        ]);
    }

    /**
     * Search bahans
     */
    public function searchBahans(Request $request)
    {
        $query = Bahan::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $bahans = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Bahans search results',
            'data' => $bahans
        ]);
    }

    /**
     * Get units for specific bahan
     */
    public function getBahanUnits($bahanId)
    {
        $bahan = Bahan::find($bahanId);

        if (!$bahan) {
            return response()->json([
                'success' => false,
                'message' => 'Bahan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Units retrieved successfully',
            'data' => [
                'bahan' => $bahan->name,
                'units' => $bahan->units ?? []
            ]
        ]);
    }

    /**
     * Get all common cooking units
     */
    public function getAllUnits()
    {
        $allUnits = [
            'weight' => [
                'label' => 'Satuan Berat',
                'units' => ['gram', 'kilogram', 'ons', 'miligram']
            ],
            'volume' => [
                'label' => 'Satuan Volume',
                'units' => ['mililiter', 'liter', 'sendok makan', 'sendok teh', 'gelas', 'cangkir']
            ],
            'pieces' => [
                'label' => 'Satuan Potongan/Buah',
                'units' => ['buah', 'butir', 'siung', 'batang', 'lembar', 'potong', 'genggam', 'iris', 'sachet', 'bungkus']
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'All units retrieved successfully',
            'data' => $allUnits
        ]);
    }
}
