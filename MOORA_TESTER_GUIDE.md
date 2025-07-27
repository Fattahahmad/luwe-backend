# MOORA API Tester - User Guide

## 🚀 Cara Menggunakan MOORA Tester

### 1. Akses Tester
Buka file `moora-tester.html` di browser web Anda:
```
file:///c:/xampp/htdocs/luwe/moora-tester.html
```

### 2. Konfigurasi API
- **API Base URL**: Pastikan URL mengarah ke Laravel API Anda
  - Default: `http://localhost/luwe/public/api`
  - Sesuaikan dengan konfigurasi server Anda

### 3. Load Ingredients
- Tester otomatis load ingredients dari endpoint `/bahans`
- Jika gagal, akan menggunakan sample data sebagai fallback
- Total ingredients akan ditampilkan di stats panel

### 4. Pilih Ingredients
**Quick Select Options:**
- **Random 3**: Pilih 3 ingredient secara random
- **Random 5**: Pilih 5 ingredient secara random  
- **Random 10**: Pilih 10 ingredient secara random
- **Select All**: Pilih semua ingredients
- **Clear All**: Hapus semua pilihan

**Manual Select:**
- Centang checkbox ingredient yang diinginkan
- Counter "Selected" akan update otomatis

### 5. Test API
- Klik tombol "🚀 Test MOORA API"
- Response JSON akan muncul di panel kanan
- Response time akan ditampilkan di stats

### 6. Analyze Response
Response JSON memiliki struktur:
```json
{
  "success": true,
  "data": [
    {
      "id": 12,
      "title": "Nama Resep",
      "moora_score": 0.8234,
      "ingredient_match_percentage": 80.0,
      "time_efficiency_percentage": 87.5,
      "cooking_time": 15,
      "category": "appetizer"
    }
  ],
  "metadata": {
    "total_eligible_recipes": 8,
    "total_recommended": 6
  }
}
```

## 📋 Testing Scenarios untuk Flutter

### Scenario 1: Perfect Match
```
Input: Ingredients yang tersedia di banyak resep
Expected: High MOORA scores (> 0.7)
```

### Scenario 2: Limited Ingredients  
```
Input: 2-3 ingredients saja
Expected: Lower scores tapi masih ada recommendations
```

### Scenario 3: No Match
```
Input: Ingredients yang jarang digunakan
Expected: Empty results atau very low scores
```

### Scenario 4: Performance Test
```
Input: Many ingredients (10+)
Expected: Response time < 200ms
```

## 🔧 Troubleshooting

### Error: Failed to load ingredients
**Cause**: API endpoint tidak accessible
**Solution**: 
1. Pastikan Laravel server running
2. Check CORS configuration
3. Verify API URL

### Error: MOORA API failed
**Cause**: Endpoint atau validation error
**Solution**:
1. Check endpoint URL `/recommendations/moora`
2. Verify request payload format
3. Check server logs

### Empty Response
**Cause**: No matching recipes
**Solution**:
1. Try different ingredient combinations
2. Check if recipes exist in database
3. Verify ingredient IDs are valid

## 📱 Flutter Integration Notes

### HTTP Request Format
```dart
final response = await http.post(
  Uri.parse('$baseUrl/recommendations/moora'),
  headers: {'Content-Type': 'application/json'},
  body: jsonEncode({'ingredient_ids': [1, 2, 3, 4, 5]}),
);
```

### Response Parsing
```dart
if (response.statusCode == 200) {
  final data = jsonDecode(response.body);
  if (data['success']) {
    final recipes = data['data'] as List;
    // Process recipes...
  }
}
```

### Error Handling
```dart
try {
  final response = await http.post(...);
  // Handle response
} on SocketException {
  // Handle network error
} on FormatException {
  // Handle JSON parsing error
} catch (e) {
  // Handle other errors
}
```

## 🎯 Key Validation Points

### ✅ Request Validation
- `ingredient_ids` harus array
- Minimal 1 ingredient ID
- ID harus valid (exists di database)

### ✅ Response Structure
- `success` field (boolean)
- `data` array dengan recipe objects
- `metadata` dengan additional info

### ✅ Performance Metrics
- Response time < 200ms untuk good UX
- Consistent results dengan input yang sama
- Proper error messages untuk debugging

## 📊 Expected Response Examples

### Success Response
```json
{
  "success": true,
  "data": [
    {
      "id": 12,
      "title": "Tumis Kangkung Sederhana",
      "description": "Kangkung segar ditumis...",
      "cooking_time": 15,
      "category": "appetizer",
      "moora_score": 0.8234,
      "ingredient_match_percentage": 80.0,
      "time_efficiency_percentage": 87.5,
      "available_ingredients_count": 4,
      "total_ingredients_count": 5,
      "user": {
        "id": 1,
        "name": "Admin User"
      }
    }
  ],
  "metadata": {
    "total_eligible_recipes": 8,
    "total_recommended": 6,
    "threshold_used": 0.3,
    "weights": {
      "ingredient_weight": 0.7,
      "time_weight": 0.3
    }
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "ingredient_ids": [
      "The ingredient ids field is required."
    ]
  }
}
```

---

**Happy Testing! 🧪**

Gunakan tester ini untuk memvalidasi API response sebelum implementasi di Flutter. Pastikan semua scenario berjalan dengan baik untuk user experience yang optimal.
