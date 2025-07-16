<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bahan;

class BahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bahans = [
            // Bahan Makanan Pokok
            'Beras',
            'Telur',
            'Minyak goreng',
            'Garam',
            'Gula pasir',
            'Bawang merah',
            'Bawang putih',
            'Cabai merah',
            'Cabai hijau',
            'Kecap manis',
            'Saus sambal',
            'Saus tomat',
            'Tepung terigu',
            'Margarin',
            'Mentega',
            'Air',
            
            // Bahan Pelengkap / Tambahan
            'Mie instan',
            'Kentang',
            'Wortel',
            'Daun bawang',
            'Seledri',
            'Tomat',
            'Timun',
            'Kol',
            'Kubis',
            'Tahu',
            'Tempe',
            'Daging ayam',
            'Daging sapi',
            'Ikan asin',
            'Ikan teri',
            'Susu kental manis',
            'Susu UHT',
            'Santan instan',
            'Kaldu bubuk',
            'Penyedap rasa',
            'Roti tawar',
            'Keju',
            
            // Bumbu Dapur Kering
            'Lada bubuk',
            'Ketumbar bubuk',
            'Kunyit bubuk',
            'Jahe bubuk',
            'Lengkuas bubuk',
            'Daun salam kering',
            'Serai kering'
        ];

        foreach ($bahans as $bahan) {
            Bahan::create(['name' => $bahan]);
        }
    }
}
