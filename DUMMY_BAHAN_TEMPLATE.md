# Template untuk Generate Dummy Bahan dengan GPT

## Prompt Template untuk ChatGPT/GPT:

```
Buatkan dummy data bahan makanan dalam format JSON untuk aplikasi resep masakan. 

Format yang dibutuhkan:
- name: nama bahan dalam bahasa Indonesia
- units: array JSON berisi unit-unit yang sesuai untuk bahan tersebut

Struktur JSON:
{
  "name": "Nama Bahan",
  "units": ["unit1", "unit2", "unit3", ...]
}

Units yang tersedia:
- "gram", "kilogram", "ons"
- "mililiter", "liter" 
- "sendok makan", "sendok teh"
- "gelas", "cangkir"
- "buah", "biji", "butir"
- "lembar", "helai"
- "batang", "tangkai"
- "potong", "iris"
- "sachet", "bungkus", "kemasan"
- "siung" (untuk bawang putih)

Kategori bahan yang dibutuhkan:
1. Bumbu dan Rempah (20 item)
2. Sayuran (25 item) 
3. Protein (daging, ikan, telur, tahu, tempe) (15 item)
4. Karbohidrat (beras, mie, tepung, dll) (10 item)
5. Dairy dan Lemak (susu, keju, mentega, minyak) (8 item)
6. Buah-buahan (12 item)
7. Bahan Pantry (gula, garam, kecap, saus, dll) (15 item)
8. Seafood (10 item)

Total: 115 bahan

Buatkan dalam format JSON array dengan struktur seperti ini:

[
  {
    "name": "Bawang merah",
    "units": ["buah", "siung", "gram", "ons"]
  },
  {
    "name": "Cabai merah", 
    "units": ["buah", "gram", "ons", "potong"]
  },
  // ... seterusnya
]

Pastikan:
- Nama bahan spesifik dan realistis
- Units sesuai dengan cara penggunaan bahan tersebut
- Variasi yang cukup untuk setiap kategori
- Tidak ada duplikasi nama
```

## Contoh Output yang Diharapkan:

```json
[
  {
    "name": "Bawang merah",
    "units": ["buah", "siung", "gram", "ons"]
  },
  {
    "name": "Cabai merah",
    "units": ["buah", "gram", "ons", "potong"]
  },
  {
    "name": "Tomat",
    "units": ["buah", "gram", "ons", "potong", "iris"]
  },
  {
    "name": "Daging sapi",
    "units": ["gram", "kilogram", "ons", "potong"]
  },
  {
    "name": "Beras",
    "units": ["gram", "kilogram", "ons", "gelas", "cangkir"]
  }
]
```

## Script untuk Import ke Database:

Setelah mendapat JSON dari GPT, gunakan script ini di Laravel Tinker:

```php
// Copy JSON result dari GPT ke variable
$dummyBahans = [
  // paste JSON array di sini
];

// Import ke database
foreach($dummyBahans as $bahan) {
    App\Models\Bahan::create([
        'name' => $bahan['name'],
        'units' => json_encode($bahan['units'])
    ]);
}

echo "Successfully imported " . count($dummyBahans) . " bahan items";
```

## Alternative: Menggunakan Seeder

Buat file seeder baru:
```bash
php artisan make:seeder DummyBahanSeeder
```

Isi seeder dengan data dari GPT:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bahan;

class DummyBahanSeeder extends Seeder
{
    public function run()
    {
        $bahans = [
            // paste JSON array dari GPT di sini
        ];

        foreach($bahans as $bahan) {
            Bahan::create([
                'name' => $bahan['name'],
                'units' => json_encode($bahan['units'])
            ]);
        }
    }
}
```

Jalankan seeder:
```bash
php artisan db:seed --class=DummyBahanSeeder
```

## Tips untuk GPT Prompt:

1. Minta bahan-bahan yang umum digunakan di masakan Indonesia
2. Sertakan variasi regional (bumbu Jawa, Sumatera, dll)
3. Pastikan units realistis (jangan pakai "kilogram" untuk garam)
4. Minta format JSON yang valid
5. Bisa minta dalam batch (misal 20-30 bahan per request)

## Validasi Data:

Setelah import, cek dengan:
```php
// Cek total bahan
App\Models\Bahan::count()

// Cek duplikasi
App\Models\Bahan::selectRaw('name, COUNT(*) as count')
    ->groupBy('name')
    ->having('count', '>', 1)
    ->get()

// Cek sample data
App\Models\Bahan::inRandomOrder()->take(5)->get(['name', 'units'])
```
