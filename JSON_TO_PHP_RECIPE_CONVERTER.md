# Converter JSON Recipe ke PHP Array

## Format JSON dari GPT:

```json
[
  {
    "title": "Nasi Goreng Kampung",
    "description": "Nasi goreng dengan bumbu tradisional dan sayuran segar",
    "cooking_time": 25,
    "category": "main_course",
    "ingredients": [
      {"name": "Beras", "amount": 300, "unit": "gram"},
      {"name": "Telur", "amount": 2, "unit": "buah"}
    ]
  }
]
```

## Convert ke PHP Array untuk Seeder:

```php
$recipes = [
    [
        "title" => "Nasi Goreng Kampung",
        "description" => "Nasi goreng dengan bumbu tradisional dan sayuran segar",
        "cooking_time" => 25,
        "category" => "main_course",
        "ingredients" => [
            ["name" => "Beras", "amount" => 300, "unit" => "gram"],
            ["name" => "Telur", "amount" => 2, "unit" => "buah"]
        ]
    ]
];
```

## Quick Converter Script:

Buat file `convert-recipe.php`:

```php
<?php
// Paste JSON result dari GPT di sini
$jsonString = '
[
  {
    "title": "Nasi Goreng Kampung", 
    "description": "Nasi goreng dengan bumbu tradisional",
    "cooking_time": 25,
    "category": "main_course",
    "ingredients": [
      {"name": "Beras", "amount": 300, "unit": "gram"}
    ]
  }
]
';

$data = json_decode($jsonString, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg() . "\n";
    exit;
}

echo "Copy this to DummyRecipeSeeder.php:\n\n";
echo '$recipes = ';
echo var_export($data, true);
echo ';';
?>
```

Jalankan: `php convert-recipe.php`

## Bahan yang Tersedia (Update sesuai database):

Current ingredients in database:
- Beras, Telur, Minyak goreng, Garam, Gula pasir
- Bawang merah, Bawang putih, Cabai merah, Cabai hijau
- Kecap manis, dan lainnya...

Check full list: `php artisan tinker` → `App\Models\Bahan::pluck('name')`
