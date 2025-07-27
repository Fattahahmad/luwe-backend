<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define common cooking units
        $commonUnits = [
            // Satuan Ukuran Berat
            'gram', 'kilogram', 'ons', 'miligram',
            
            // Satuan Ukuran Volume  
            'mililiter', 'liter', 'sendok makan', 'sendok teh', 'gelas', 'cangkir',
            
            // Satuan Potongan / Buah / Jumlah
            'buah', 'butir', 'siung', 'batang', 'lembar', 'potong', 'genggam', 'iris', 'sachet', 'bungkus'
        ];

        // Get all bahans and add appropriate units
        $bahans = \App\Models\Bahan::all();
        
        foreach ($bahans as $bahan) {
            // Assign units based on ingredient type
            $units = $this->getUnitsForIngredient($bahan->name, $commonUnits);
            
            $bahan->update([
                'units' => $units
            ]);
        }
    }

    /**
     * Get appropriate units for specific ingredient
     */
    private function getUnitsForIngredient($name, $commonUnits)
    {
        $name = strtolower($name);
        
        // For liquid ingredients
        if (str_contains($name, 'minyak') || str_contains($name, 'air') || 
            str_contains($name, 'susu') || str_contains($name, 'santan') ||
            str_contains($name, 'cuka') || str_contains($name, 'kecap')) {
            return ['mililiter', 'liter', 'sendok makan', 'sendok teh', 'gelas', 'cangkir'];
        }
        
        // For powder/spices
        if (str_contains($name, 'tepung') || str_contains($name, 'garam') || 
            str_contains($name, 'gula') || str_contains($name, 'merica') ||
            str_contains($name, 'bubuk') || str_contains($name, 'bumbu')) {
            return ['gram', 'kilogram', 'ons', 'sendok makan', 'sendok teh', 'sachet', 'bungkus'];
        }
        
        // For vegetables that are counted
        if (str_contains($name, 'bawang') || str_contains($name, 'cabai') || 
            str_contains($name, 'tomat') || str_contains($name, 'kentang') ||
            str_contains($name, 'wortel')) {
            return ['buah', 'siung', 'gram', 'kilogram', 'ons', 'potong', 'iris'];
        }
        
        // For leafy vegetables
        if (str_contains($name, 'daun') || str_contains($name, 'bayam') || 
            str_contains($name, 'kangkung') || str_contains($name, 'sawi')) {
            return ['lembar', 'genggam', 'gram', 'kilogram', 'ons', 'batang'];
        }
        
        // For meat/protein
        if (str_contains($name, 'ayam') || str_contains($name, 'daging') || 
            str_contains($name, 'ikan') || str_contains($name, 'udang') ||
            str_contains($name, 'telur')) {
            return ['gram', 'kilogram', 'ons', 'buah', 'butir', 'potong'];
        }
        
        // Default units for other ingredients
        return ['gram', 'kilogram', 'ons', 'buah', 'batang', 'lembar', 'potong', 'sendok makan', 'sendok teh'];
    }
}
