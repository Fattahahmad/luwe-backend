# API Documentation - MOORA Recipe Recommendation System

## Endpoint Overview

Base URL: `http://localhost/luwe/public/api`

## 1. MOORA Recipe Recommendations

### Endpoint
```
POST /recipes/moora-recommendations
```

### Description
Mendapatkan rekomendasi resep menggunakan algoritma MOORA berdasarkan bahan yang tersedia.

### Request Headers
```
Content-Type: application/json
Accept: application/json
```

### Request Body
```json
{
  "ingredient_ids": [1, 2, 3, 4, 5]
}
```

#### Parameters
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| ingredient_ids | array | Yes | Array of ingredient IDs yang tersedia |

### Response Format

#### Success Response (200 OK)
```json
{
  "success": true,
  "data": [
    {
      "id": 12,
      "title": "Tumis Kangkung Sederhana",
      "description": "Kangkung segar ditumis dengan bawang putih dan cabai, cocok sebagai lauk pendamping.",
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
    },
    {
      "id": 13,
      "title": "Perkedel Kentang",
      "description": "Perkedel kentang lembut dan gurih, cocok untuk camilan atau pelengkap nasi.",
      "cooking_time": 25,
      "category": "appetizer",
      "moora_score": 0.7456,
      "ingredient_match_percentage": 100.0,
      "time_efficiency_percentage": 79.2,
      "available_ingredients_count": 5,
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

#### Error Response (400 Bad Request)
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

#### Error Response (422 Unprocessable Entity)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "ingredient_ids": [
      "The ingredient ids must be an array.",
      "The ingredient ids field must contain at least 1 items."
    ]
  }
}
```

### Example Usage

#### cURL
```bash
curl -X POST http://localhost/luwe/public/api/recipes/moora-recommendations \
  -H "Content-Type: application/json" \
  -d '{
    "ingredient_ids": [1, 2, 3, 4, 5]
  }'
```

#### JavaScript (Fetch)
```javascript
const response = await fetch('http://localhost/luwe/public/api/recipes/moora-recommendations', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    ingredient_ids: [1, 2, 3, 4, 5]
  })
});

const data = await response.json();
console.log(data);
```

#### PHP
```php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/luwe/public/api/recipes/moora-recommendations',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode([
    'ingredient_ids' => [1, 2, 3, 4, 5]
  ]),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Accept: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
```

## 2. Get Available Ingredients

### Endpoint
```
GET /ingredients
```

### Description
Mendapatkan daftar semua bahan yang tersedia dalam database.

### Response Format
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Bawang merah",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Bawang putih",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

## 3. Response Field Descriptions

### Recipe Object Fields

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Unique identifier resep |
| title | string | Nama resep |
| description | string | Deskripsi resep |
| cooking_time | integer | Waktu memasak dalam menit |
| category | string | Kategori resep (appetizer, main_course, dessert) |
| moora_score | float | Skor MOORA (0.0 - 1.0), semakin tinggi semakin baik |
| ingredient_match_percentage | float | Persentase bahan yang tersedia (0.0 - 100.0) |
| time_efficiency_percentage | float | Persentase efisiensi waktu (0.0 - 100.0) |
| available_ingredients_count | integer | Jumlah bahan yang tersedia |
| total_ingredients_count | integer | Total bahan yang dibutuhkan resep |
| user | object | Informasi pembuat resep |

### Metadata Object Fields

| Field | Type | Description |
|-------|------|-------------|
| total_eligible_recipes | integer | Total resep yang memenuhi syarat (minimal 1 bahan tersedia) |
| total_recommended | integer | Total resep yang direkomendasikan (skor >= threshold) |
| threshold_used | float | Nilai threshold yang digunakan untuk filtering |
| weights | object | Bobot yang digunakan dalam perhitungan MOORA |

## 4. Error Handling

### Validation Rules

#### ingredient_ids
- **Required**: Field harus ada
- **Array**: Harus berupa array
- **Min Items**: Minimal 1 item dalam array
- **Integer**: Setiap item harus berupa integer
- **Exists**: Setiap ID harus ada dalam database ingredients

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success - Request berhasil |
| 400 | Bad Request - Format request salah |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error - Error pada server |

## 5. Performance Considerations

### Caching
- Hasil MOORA dapat di-cache untuk kombinasi ingredient_ids yang sama
- Cache duration: 1 hour (dapat dikonfigurasi)

### Database Optimization
- Index pada kolom yang sering di-query
- Lazy loading untuk relasi yang tidak selalu dibutuhkan
- Pagination untuk result set yang besar

### Rate Limiting
- Max 100 requests per minute per IP
- Burst limit: 10 requests per second

## 6. Testing Examples

### Test Case 1: Perfect Match
```json
// Request
{
  "ingredient_ids": [1, 2, 3, 4, 5]
}

// Expected: Resep dengan semua bahan tersedia mendapat skor tertinggi
```

### Test Case 2: Partial Match
```json
// Request  
{
  "ingredient_ids": [1, 2]
}

// Expected: Resep dengan 2 bahan ini mendapat skor berdasarkan ingredient ratio
```

### Test Case 3: No Match
```json
// Request
{
  "ingredient_ids": [999]
}

// Expected: Empty recommendations atau low-score recipes
```

### Test Case 4: Invalid Input
```json
// Request
{
  "ingredient_ids": "not_an_array"
}

// Expected: 422 Validation Error
```

## 7. Integration Examples

### Frontend Integration (React)
```jsx
import { useState, useEffect } from 'react';

function RecipeRecommendations({ selectedIngredients }) {
  const [recommendations, setRecommendations] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchRecommendations = async () => {
    if (selectedIngredients.length === 0) return;
    
    setLoading(true);
    try {
      const response = await fetch('/api/recipes/moora-recommendations', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ingredient_ids: selectedIngredients })
      });
      
      const data = await response.json();
      if (data.success) {
        setRecommendations(data.data);
      }
    } catch (error) {
      console.error('Failed to fetch recommendations:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchRecommendations();
  }, [selectedIngredients]);

  return (
    <div>
      {loading && <div>Loading recommendations...</div>}
      {recommendations.map(recipe => (
        <div key={recipe.id} className="recipe-card">
          <h3>{recipe.title}</h3>
          <p>Score: {recipe.moora_score.toFixed(3)}</p>
          <p>Ingredient Match: {recipe.ingredient_match_percentage}%</p>
          <p>Cooking Time: {recipe.cooking_time} minutes</p>
        </div>
      ))}
    </div>
  );
}
```

---

**Last Updated**: July 27, 2025  
**Version**: 1.0  
**API Version**: v1
