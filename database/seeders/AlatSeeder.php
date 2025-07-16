<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alat;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alats = [
            'Kompor gas',
            'Kompor listrik',
            'Wajan',
            'Panci',
            'Spatula',
            'Sutil',
            'Pisau dapur',
            'Talenan',
            'Saringan',
            'Saringan minyak',
            'Cobek',
            'Ulekan',
            'Sendok',
            'Garpu',
            'Mangkuk',
            'Piring',
            'Blender',
            'Panci kukus',
            'Dandang',
            'Teko',
            'Ketel',
            'Rice cooker',
            'Wadah plastik',
            'Wadah kaca'
        ];

        foreach ($alats as $alat) {
            Alat::create(['name' => $alat]);
        }
    }
}
