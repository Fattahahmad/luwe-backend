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
}
