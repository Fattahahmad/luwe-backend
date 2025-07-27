# Template untuk Generate Dummy Resep dengan GPT

## Prompt Template untuk ChatGPT/GPT:

```
Buatkan 30 dummy data resep masakan Indonesia dalam format JSON untuk aplikasi resep masakan. 

Format yang dibutuhkan:
- title: nama resep dalam bahasa Indonesia
- description: deskripsi singkat resep (1-2 kalimat)
- cooking_time: waktu memasak dalam menit (integer)
- category: kategori resep
- ingredients: array bahan-bahan dengan amount dan unit

Struktur JSON:
{
  "title": "Nama Resep",
  "description": "Deskripsi singkat resep yang menggugah selera",
  "cooking_time": 30,
  "category": "main_course",
  "ingredients": [
    {
      "name": "Bahan 1",
      "amount": 200,
      "unit": "gram"
    },
    {
      "name": "Bahan 2", 
      "amount": 2,
      "unit": "buah"
    }
  ]
}

Kategori yang tersedia:
- "appetizer" (pembuka/camilan)
- "main_course" (makanan utama)
- "dessert" (pencuci mulut)

Waktu memasak yang realistis:
- Appetizer: 10-30 menit
- Main course: 20-90 menit  
- Dessert: 15-60 menit

Bahan-bahan yang umum (sesuaikan dengan yang ada):
- Bumbu: bawang merah, bawang putih, cabai, jahe, kunyit, lengkuas, serai
- Sayuran: tomat, timun, kangkung, bayam, wortel, kentang
- Protein: daging ayam, daging sapi, ikan, telur, tahu, tempe
- Karbohidrat: beras, mie, tepung terigu
- Pantry: garam, gula pasir, minyak goreng, kecap manis, saus sambal

Units yang tersedia:
- "gram", "kilogram", "ons"
- "mililiter", "liter"
- "sendok makan", "sendok teh"
- "gelas", "cangkir"
- "buah", "siung", "butir"
- "lembar", "helai", "potong"
- "sachet", "bungkus"

Distribusi yang diinginkan:
- Appetizer: 8 resep
- Main course: 15 resep
- Dessert: 7 resep

Pastikan:
- Nama resep menarik dan spesifik
- Deskripsi singkat tapi menggugah selera
- Waktu memasak realistis
- Bahan-bahan sesuai dengan jenis masakan
- Jumlah bahan 3-8 per resep
- Tidak ada duplikasi nama resep

Contoh output:
[
  {
    "title": "Nasi Goreng Kampung",
    "description": "Nasi goreng dengan bumbu tradisional dan sayuran segar",
    "cooking_time": 25,
    "category": "main_course",
    "ingredients": [
      {"name": "Beras", "amount": 300, "unit": "gram"},
      {"name": "Telur", "amount": 2, "unit": "buah"},
      {"name": "Bawang merah", "amount": 3, "unit": "siung"},
      {"name": "Cabai merah", "amount": 2, "unit": "buah"}
    ]
  }
]
```

## Script untuk Import ke Database:

Setelah mendapat JSON dari GPT, gunakan script ini di Laravel Tinker:

```php
// Copy JSON result dari GPT ke variable
$dummyRecipes = [
  // paste JSON array di sini
];

// Get first user ID untuk assign recipes
$userId = App\Models\User::first()->id;

// Get all bahan IDs untuk mapping
$bahanMapping = App\Models\Bahan::pluck('id', 'name')->toArray();

// Import ke database
foreach($dummyRecipes as $recipeData) {
    // Create recipe
    $recipe = App\Models\Recipe::create([
        'user_id' => $userId,
        'title' => $recipeData['title'],
        'description' => $recipeData['description'],
        'cooking_time' => $recipeData['cooking_time'],
        'category' => $recipeData['category']
    ]);
    
    // Add ingredients
    foreach($recipeData['ingredients'] as $ingredient) {
        $bahanId = $bahanMapping[$ingredient['name']] ?? null;
        if ($bahanId) {
            $recipe->bahans()->attach($bahanId, [
                'amount' => $ingredient['amount'],
                'unit' => $ingredient['unit']
            ]);
        }
    }
}

echo "Successfully imported " . count($dummyRecipes) . " recipes";
```

## Alternative: Menggunakan Seeder

Buat file seeder baru:
```bash
php artisan make:seeder DummyRecipeSeeder
```

## Validasi Data:

Setelah import, cek dengan:
```php
// Cek total resep
App\Models\Recipe::count()

// Cek resep dengan bahan
App\Models\Recipe::with('bahans')->get()

// Cek distribusi kategori
App\Models\Recipe::selectRaw('category, COUNT(*) as count')
    ->groupBy('category')
    ->get()

// Test MOORA dengan data baru
// Pilih beberapa bahan dan test recommendation
```

## Tips untuk Prompt GPT:

1. Minta resep-resep populer Indonesia
2. Variasi tingkat kesulitan (mudah, sedang, sulit)
3. Kombinasi resep tradisional dan modern
4. Pastikan bahan-bahan realistis dan tersedia
5. Waktu memasak yang masuk akal
6. Deskripsi yang menarik tapi tidak terlalu panjang
