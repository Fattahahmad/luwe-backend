# 🍳 Dummy Data Generator untuk Aplikasi Resep

## 📁 File Template yang Tersedia

### **Dummy Bahan (Ingredients)**
- 📝 `DUMMY_BAHAN_TEMPLATE.md` - Template lengkap
- 🤖 `GPT_PROMPT_BAHAN.txt` - Prompt siap pakai untuk GPT
- ⚙️ `database/seeders/DummyBahanSeeder.php` - Seeder template
- 🔄 `JSON_TO_PHP_CONVERTER.md` - Converter guide

### **Dummy Resep (Recipes)**  
- 📝 `DUMMY_RECIPE_TEMPLATE.md` - Template lengkap
- 🤖 `GPT_PROMPT_RECIPE.txt` - Prompt siap pakai untuk GPT
- ⚙️ `database/seeders/DummyRecipeSeeder.php` - Seeder template
- 🔄 `JSON_TO_PHP_RECIPE_CONVERTER.md` - Converter guide
- 🛠️ `convert-recipe.php` - Helper script untuk convert

---

## 🚀 Quick Start Guide

### **1. Generate Dummy Bahan (Optional)**

```bash
# 1. Copy prompt
cat GPT_PROMPT_BAHAN.txt

# 2. Paste ke ChatGPT/GPT dan dapatkan JSON

# 3. Convert ke PHP array dan paste ke DummyBahanSeeder.php

# 4. Run seeder
php artisan db:seed --class=DummyBahanSeeder
```

### **2. Generate Dummy Resep (Required)**

```bash
# 1. Copy prompt  
cat GPT_PROMPT_RECIPE.txt

# 2. Paste ke ChatGPT/GPT dan dapatkan JSON

# 3. Convert menggunakan helper script
# Edit convert-recipe.php, paste JSON, lalu:
php convert-recipe.php

# 4. Copy hasil PHP array ke DummyRecipeSeeder.php

# 5. Run seeder
php artisan db:seed --class=DummyRecipeSeeder
```

---

## 📋 Template Prompt GPT

### **Untuk Dummy Bahan:**
```
Buatkan 50 dummy data bahan makanan Indonesia dalam format JSON...
[Copy full prompt dari GPT_PROMPT_BAHAN.txt]
```

### **Untuk Dummy Resep:**
```  
Buatkan 30 dummy data resep masakan Indonesia dalam format JSON...
[Copy full prompt dari GPT_PROMPT_RECIPE.txt]
```

---

## 🔧 Struktur Data

### **Bahan (Ingredients)**
```json
{
  "name": "Bawang merah",
  "units": ["buah", "siung", "gram", "ons"]
}
```

### **Resep (Recipes)**
```json
{
  "title": "Nasi Goreng Kampung",
  "description": "Nasi goreng dengan bumbu tradisional",
  "cooking_time": 25,
  "category": "main_course",
  "ingredients": [
    {"name": "Beras", "amount": 300, "unit": "gram"},
    {"name": "Telur", "amount": 2, "unit": "buah"}
  ]
}
```

---

## ✅ Validation Commands

```bash
# Cek jumlah bahan
php artisan tinker --execute="echo App\Models\Bahan::count() . ' bahan'"

# Cek jumlah resep  
php artisan tinker --execute="echo App\Models\Recipe::count() . ' resep'"

# Cek resep dengan bahan
php artisan tinker --execute="echo App\Models\Recipe::with('bahans')->get()->map(fn(\$r) => \$r->title . ': ' . \$r->bahans->count() . ' bahan')->implode(PHP_EOL)"

# Cek distribusi kategori resep
php artisan tinker --execute="App\Models\Recipe::selectRaw('category, COUNT(*) as count')->groupBy('category')->get()->each(fn(\$item) => print(\$item->category . ': ' . \$item->count . PHP_EOL))"

# Test MOORA recommendation
curl -X POST "http://127.0.0.1:8000/api/recommendations/moora" \
  -H "Content-Type: application/json" \
  -d '{"available_ingredients":[1,2,3],"min_cooking_time":10,"max_cooking_time":30}'
```

---

## 🎯 Expected Output

### **Setelah Seeding Berhasil:**
- ✅ 45+ bahan unik dengan units yang sesuai
- ✅ 30+ resep dengan kategori: appetizer, main_course, dessert  
- ✅ Setiap resep memiliki 3-8 bahan dengan amount & unit
- ✅ Waktu memasak yang realistis (10-90 menit)
- ✅ MOORA recommendation system dapat bekerja dengan data lengkap

### **Testing MOORA:**
1. Buka `/moora-tester`
2. Pilih beberapa bahan  
3. Set rentang waktu
4. Dapatkan rekomendasi resep berdasarkan algoritma MOORA

---

## 🔍 Troubleshooting

**❓ "No users found"**
```bash
php artisan tinker --execute="App\Models\User::factory()->create()"
```

**❓ "Bahan not found"**  
- Pastikan nama bahan di resep sesuai dengan yang ada di database
- Cek: `App\Models\Bahan::pluck('name')`

**❓ "JSON Error"**
- Pastikan JSON dari GPT valid
- Gunakan online JSON validator
- Gunakan `convert-recipe.php` helper script

**❓ "No valid ingredients"**
- Seeder akan skip resep yang tidak memiliki bahan valid
- Check warning messages saat seeding
- Update nama bahan sesuai database

---

## 📞 Support

Jika ada masalah:
1. Check error messages di console
2. Validate JSON format
3. Ensure bahan names match database
4. Test dengan sample data kecil dulu

Selamat generating dummy data! 🎉
