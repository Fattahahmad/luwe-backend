# Converter JSON ke PHP Array

## Jika GPT memberikan format JSON seperti ini:

```json
[
  {
    "name": "Bawang merah",
    "units": ["buah", "siung", "gram", "ons"]
  },
  {
    "name": "Cabai merah",
    "units": ["buah", "gram", "ons", "potong"]
  }
]
```

## Convert ke format PHP array untuk seeder:

```php
$bahans = [
    [
        "name" => "Bawang merah",
        "units" => ["buah", "siung", "gram", "ons"]
    ],
    [
        "name" => "Cabai merah", 
        "units" => ["buah", "gram", "ons", "potong"]
    ]
];
```

## Online Converter Tools:

1. **JSON to PHP Array Online**: https://www.convert-json-to-php.com/
2. **Manual Conversion**: 
   - Ganti `{` dengan `[`
   - Ganti `}` dengan `]`
   - Tambahkan `=>` setelah key
   - Pastikan string menggunakan double quotes

## Script Auto-Convert (Optional):

Buat file helper untuk convert JSON:

```php
// helper-convert.php
$jsonString = '
[
  // paste JSON dari GPT di sini
]
';

$data = json_decode($jsonString, true);
$phpArray = var_export($data, true);

echo "Copy this to seeder:\n\n";
echo '$bahans = ' . $phpArray . ';';
```

Jalankan dengan: `php helper-convert.php`
