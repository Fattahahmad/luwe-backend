# Dokumentasi Sistem Rekomendasi Resep Menggunakan Metode MOORA

## 1. Pendahuluan

### 1.1 Latar Belakang
Sistem Pendukung Keputusan (SPK) untuk rekomendasi resep masakan menggunakan metode **MOORA (Multi-Objective Optimization by Ratio Analysis)** untuk membantu pengguna menemukan resep terbaik berdasarkan bahan yang tersedia dan efisiensi waktu memasak.

### 1.2 Tujuan
- Memberikan rekomendasi resep yang optimal berdasarkan multiple criteria
- Memaksimalkan penggunaan bahan yang tersedia
- Mempertimbangkan efisiensi waktu memasak
- Menggunakan pendekatan matematis yang objektif dalam pengambilan keputusan

## 2. Metode MOORA

### 2.1 Definisi
MOORA (Multi-Objective Optimization by Ratio Analysis) adalah metode pengambilan keputusan multi-kriteria yang dikembangkan oleh Brauers dan Zavadskas (2006). Metode ini mengoptimalkan dua atau lebih atribut yang saling bertentangan secara bersamaan.

### 2.2 Keunggulan Metode MOORA
- **Sederhana**: Perhitungan yang relatif mudah dipahami
- **Stabil**: Hasil yang konsisten dengan perubahan kecil pada data
- **Robust**: Tahan terhadap outlier dan noise dalam data
- **Fleksibel**: Dapat menangani berbagai jenis kriteria (benefit/cost)

## 3. Implementasi MOORA untuk Rekomendasi Resep

### 3.1 Kriteria yang Digunakan
1. **Ingredient Match Ratio** (Benefit - 70% bobot)
   - Persentase bahan yang tersedia untuk membuat resep
   - Semakin tinggi semakin baik
   
2. **Time Efficiency** (Benefit - 30% bobot)
   - Efisiensi waktu berdasarkan cooking time
   - Waktu lebih singkat = nilai efisiensi lebih tinggi

### 3.2 Formula Matematis

#### 3.2.1 Ingredient Match Ratio
```
Ingredient_Match_Ratio = (Jumlah_Bahan_Tersedia / Total_Bahan_Resep) × 100
```

**Contoh:**
- Resep membutuhkan: [Bawang merah, Cabai, Garam, Minyak goreng, Telur] = 5 bahan
- Bahan tersedia: [Bawang merah, Cabai, Garam, Minyak goreng] = 4 bahan
- Ingredient Match Ratio = (4/5) × 100 = 80%

#### 3.2.2 Time Efficiency
```
Time_Efficiency = (Max_Cooking_Time - Cooking_Time_Resep) / Max_Cooking_Time × 100
```

**Contoh:**
- Max cooking time dalam dataset: 120 menit
- Cooking time resep: 30 menit
- Time Efficiency = (120-30)/120 × 100 = 75%

### 3.3 Langkah-langkah Perhitungan MOORA

#### Langkah 1: Normalisasi Matriks Keputusan
```
r_ij = x_ij / √(Σ(x_ij²))
```

Dimana:
- `r_ij` = nilai normalisasi untuk alternatif i kriteria j
- `x_ij` = nilai alternatif i pada kriteria j

#### Langkah 2: Pembobotan
```
y_ij = w_j × r_ij
```

Dimana:
- `w_j` = bobot kriteria j
- `w_1` = 0.7 (Ingredient Match)
- `w_2` = 0.3 (Time Efficiency)

#### Langkah 3: Optimalisasi
```
Y_i = Σ(y_ij_benefit) - Σ(y_ij_cost)
```

Dalam kasus ini, semua kriteria adalah benefit, sehingga:
```
Y_i = y_i1 + y_i2
```

## 4. Implementasi dalam Kode

### 4.1 Struktur Kelas MooraRecommendationService

```php
class MooraRecommendationService 
{
    private $ingredientWeight = 0.7;    // 70% untuk ingredient match
    private $timeWeight = 0.3;          // 30% untuk time efficiency
    private $threshold = 0.3;           // Minimum score threshold
    private $maxResults = 10;           // Maximum hasil rekomendasi
}
```

### 4.2 Pre-filtering

Sebelum perhitungan MOORA, sistem melakukan pre-filtering:

```php
public function getEligibleRecipes($availableIngredientIds)
{
    return Recipe::whereHas('bahans', function ($query) use ($availableIngredientIds) {
        $query->whereIn('bahan_id', $availableIngredientIds);
    })->with(['bahans', 'user'])->get();
}
```

**Tujuan**: Hanya memproses resep yang memiliki minimal 1 bahan yang tersedia.

### 4.3 Perhitungan Skor MOORA

```php
private function calculateMooraScore($recipe, $availableIngredientIds)
{
    // 1. Hitung Ingredient Match Ratio
    $totalIngredients = $recipe->bahans->count();
    $availableCount = $recipe->bahans->whereIn('id', $availableIngredientIds)->count();
    $ingredientRatio = $totalIngredients > 0 ? $availableCount / $totalIngredients : 0;

    // 2. Hitung Time Efficiency
    $maxCookingTime = 120; // Asumsi maksimal 120 menit
    $timeEfficiency = ($maxCookingTime - $recipe->cooking_time) / $maxCookingTime;
    $timeEfficiency = max(0, min(1, $timeEfficiency)); // Clamp antara 0-1

    // 3. Normalisasi (menggunakan Vector Normalization)
    $ingredientNorm = $ingredientRatio / sqrt($ingredientRatio * $ingredientRatio + $timeEfficiency * $timeEfficiency);
    $timeNorm = $timeEfficiency / sqrt($ingredientRatio * $ingredientRatio + $timeEfficiency * $timeEfficiency);

    // 4. Pembobotan dan Optimalisasi
    $mooraScore = ($this->ingredientWeight * $ingredientNorm) + ($this->timeWeight * $timeNorm);

    return [
        'score' => $mooraScore,
        'ingredient_ratio' => $ingredientRatio,
        'time_efficiency' => $timeEfficiency,
        'available_ingredients' => $availableCount,
        'total_ingredients' => $totalIngredients
    ];
}
```

## 5. Contoh Perhitungan Lengkap

### 5.1 Data Input
**Bahan Tersedia**: [1, 2, 3, 4, 5] (ID bahan)

**Resep Kandidat**:
- Resep A: Butuh bahan [1,2,3,4,5,6] (6 bahan), cooking time 15 menit
- Resep B: Butuh bahan [1,2,7,8] (4 bahan), cooking time 30 menit  
- Resep C: Butuh bahan [1,2,3] (3 bahan), cooking time 45 menit

### 5.2 Perhitungan Step-by-Step

#### Resep A:
1. **Ingredient Match**: 5/6 = 0.833 (83.3%)
2. **Time Efficiency**: (120-15)/120 = 0.875 (87.5%)
3. **Normalisasi**: 
   - Ingredient: 0.833/√(0.833² + 0.875²) = 0.689
   - Time: 0.875/√(0.833² + 0.875²) = 0.724
4. **MOORA Score**: (0.7 × 0.689) + (0.3 × 0.724) = 0.699

#### Resep B:
1. **Ingredient Match**: 2/4 = 0.500 (50%)
2. **Time Efficiency**: (120-30)/120 = 0.750 (75%)
3. **Normalisasi**:
   - Ingredient: 0.500/√(0.500² + 0.750²) = 0.555
   - Time: 0.750/√(0.500² + 0.750²) = 0.832
4. **MOORA Score**: (0.7 × 0.555) + (0.3 × 0.832) = 0.638

#### Resep C:
1. **Ingredient Match**: 3/3 = 1.000 (100%)
2. **Time Efficiency**: (120-45)/120 = 0.625 (62.5%)
3. **Normalisasi**:
   - Ingredient: 1.000/√(1.000² + 0.625²) = 0.848
   - Time: 0.625/√(1.000² + 0.625²) = 0.530
4. **MOORA Score**: (0.7 × 0.848) + (0.3 × 0.530) = 0.752

### 5.3 Ranking Hasil
1. **Resep C**: 0.752 (Terbaik - 100% bahan tersedia)
2. **Resep A**: 0.699 (Kedua - Cepat tapi butuh bahan tambahan)
3. **Resep B**: 0.638 (Ketiga - Hanya 50% bahan tersedia)

## 6. Validasi dan Filtering

### 6.1 Threshold Filtering
```php
$filteredResults = array_filter($rankedRecipes, function($result) {
    return $result['moora_score'] >= $this->threshold;
});
```

**Tujuan**: Menghilangkan resep dengan skor terlalu rendah (< 0.3).

### 6.2 Limit Results
```php
return array_slice($filteredResults, 0, $this->maxResults);
```

**Tujuan**: Membatasi maksimal 10 rekomendasi untuk user experience yang optimal.

## 7. Output Format

### 7.1 Struktur Response
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Nama Resep",
      "description": "Deskripsi resep",
      "cooking_time": 15,
      "category": "appetizer",
      "moora_score": 0.752,
      "ingredient_match_percentage": 100,
      "time_efficiency_percentage": 62.5,
      "available_ingredients_count": 3,
      "total_ingredients_count": 3,
      "user": {
        "name": "User Name"
      }
    }
  ],
  "metadata": {
    "total_eligible_recipes": 8,
    "total_recommended": 5,
    "threshold_used": 0.3,
    "weights": {
      "ingredient_weight": 0.7,
      "time_weight": 0.3
    }
  }
}
```

## 8. Pengujian dan Validasi

### 8.1 Test Cases
1. **Empty Input**: Tidak ada bahan → Return empty array
2. **Perfect Match**: Semua bahan tersedia → Skor tinggi
3. **Partial Match**: Sebagian bahan tersedia → Skor sedang
4. **Time Factor**: Resep cepat vs lambat → Pengaruh pada ranking

### 8.2 Database Test Data
- **13 resep** total (9 appetizer, 3 main course, 1 dessert)
- **45 unique ingredients** setelah cleaning duplikasi
- **Consistent seeding** untuk reproducible testing

## 9. Kesimpulan

### 9.1 Keunggulan Implementasi
- **Objektif**: Menggunakan perhitungan matematis yang terstandar
- **Balanced**: Mempertimbangkan availability dan efficiency
- **Scalable**: Mudah menambah kriteria baru
- **User-friendly**: Output yang mudah dipahami

### 9.2 Potensi Pengembangan
- **Dynamic Weighting**: User bisa adjust bobot sesuai preferensi
- **Additional Criteria**: Nutrition, difficulty level, cost
- **Machine Learning**: Learning dari user behavior
- **Seasonal Ingredients**: Pertimbangan musiman bahan

---

## Referensi
1. Brauers, W. K. M., & Zavadskas, E. K. (2006). The MOORA method and its application to privatization in a transition economy. Control and cybernetics, 35(2), 445-469.
2. Stanujkic, D., Djordjevic, B., & Karabasevic, D. (2015). Selection of candidates in the mining industry based on the application of the SWARA and the MULTIMOORA methods. Acta Montanistica Slovaca, 20(2).
