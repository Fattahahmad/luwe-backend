<?php
/**
 * Helper script untuk convert JSON recipe dari GPT ke PHP array
 * Usage: php convert-recipe.php
 */

echo "=== Recipe JSON to PHP Array Converter ===\n\n";

// TODO: Paste JSON result dari GPT di sini
$jsonString = '
[
  {
    "title": "Tumis Kangkung Sederhana",
    "description": "Kangkung segar ditumis dengan bawang putih dan cabai, cocok sebagai lauk pendamping.",
    "cooking_time": 15,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Kangkung",
        "amount": 1,
        "unit": "ikat"
      },
      {
        "name": "Bawang putih",
        "amount": 3,
        "unit": "siung"
      },
      {
        "name": "Cabai merah",
        "amount": 2,
        "unit": "buah"
      },
      {
        "name": "Minyak goreng",
        "amount": 2,
        "unit": "sendok makan"
      },
      {
        "name": "Garam",
        "amount": 0.5,
        "unit": "sendok teh"
      }
    ]
  },
  {
    "title": "Perkedel Kentang",
    "description": "Perkedel kentang lembut dan gurih, cocok untuk camilan atau pelengkap nasi.",
    "cooking_time": 25,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Kentang",
        "amount": 500,
        "unit": "gram"
      },
      {
        "name": "Telur",
        "amount": 1,
        "unit": "butir"
      },
      {
        "name": "Bawang merah",
        "amount": 3,
        "unit": "siung"
      },
      {
        "name": "Garam",
        "amount": 0.5,
        "unit": "sendok teh"
      },
      {
        "name": "Minyak goreng",
        "amount": 300,
        "unit": "mililiter"
      }
    ]
  },
  {
    "title": "Tahu Isi Pedas",
    "description": "Tahu goreng isi sayuran pedas, renyah di luar dan lembut di dalam.",
    "cooking_time": 30,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Tahu",
        "amount": 10,
        "unit": "buah"
      },
      {
        "name": "Wortel",
        "amount": 1,
        "unit": "buah"
      },
      {
        "name": "Cabai merah",
        "amount": 3,
        "unit": "buah"
      },
      {
        "name": "Bawang putih",
        "amount": 2,
        "unit": "siung"
      },
      {
        "name": "Tepung terigu",
        "amount": 100,
        "unit": "gram"
      },
      {
        "name": "Minyak goreng",
        "amount": 300,
        "unit": "mililiter"
      }
    ]
  },
  {
    "title": "Telur Dadar Bumbu Iris",
    "description": "Telur dadar dengan irisan bawang dan cabai, menu praktis penuh rasa.",
    "cooking_time": 10,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Telur",
        "amount": 2,
        "unit": "butir"
      },
      {
        "name": "Bawang merah",
        "amount": 2,
        "unit": "siung"
      },
      {
        "name": "Cabai hijau",
        "amount": 2,
        "unit": "buah"
      },
      {
        "name": "Garam",
        "amount": 0.5,
        "unit": "sendok teh"
      },
      {
        "name": "Minyak goreng",
        "amount": 2,
        "unit": "sendok makan"
      }
    ]
  },
  {
    "title": "Bakwan Sayur",
    "description": "Gorengan sayur renyah berisi wortel dan kol, cocok untuk semua suasana.",
    "cooking_time": 20,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Wortel",
        "amount": 1,
        "unit": "buah"
      },
      {
        "name": "Tepung terigu",
        "amount": 150,
        "unit": "gram"
      },
      {
        "name": "Bawang putih",
        "amount": 2,
        "unit": "siung"
      },
      {
        "name": "Garam",
        "amount": 1,
        "unit": "sendok teh"
      },
      {
        "name": "Minyak goreng",
        "amount": 300,
        "unit": "mililiter"
      }
    ]
  },
  {
    "title": "Tempe Mendoan",
    "description": "Tempe goreng tipis berbalut tepung, nikmat disantap dengan sambal kecap.",
    "cooking_time": 15,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Tempe",
        "amount": 200,
        "unit": "gram"
      },
      {
        "name": "Tepung terigu",
        "amount": 100,
        "unit": "gram"
      },
      {
        "name": "Daun bawang",
        "amount": 1,
        "unit": "batang"
      },
      {
        "name": "Minyak goreng",
        "amount": 300,
        "unit": "mililiter"
      }
    ]
  },
  {
    "title": "Sayur Bening Bayam",
    "description": "Sayur kuah bening yang menyegarkan, kaya akan zat besi dan rendah kalori.",
    "cooking_time": 20,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Bayam",
        "amount": 1,
        "unit": "ikat"
      },
      {
        "name": "Bawang merah",
        "amount": 3,
        "unit": "siung"
      },
      {
        "name": "Garam",
        "amount": 1,
        "unit": "sendok teh"
      },
      {
        "name": "Air",
        "amount": 750,
        "unit": "mililiter"
      }
    ]
  },
  {
    "title": "Acar Timun",
    "description": "Acar segar dari timun, wortel, dan cabai sebagai pelengkap makanan berminyak.",
    "cooking_time": 10,
    "category": "appetizer",
    "ingredients": [
      {
        "name": "Timun",
        "amount": 2,
        "unit": "buah"
      },
      {
        "name": "Wortel",
        "amount": 1,
        "unit": "buah"
      },
      {
        "name": "Cabai merah",
        "amount": 2,
        "unit": "buah"
      },
      {
        "name": "Cuka",
        "amount": 3,
        "unit": "sendok makan"
      },
      {
        "name": "Gula pasir",
        "amount": 1,
        "unit": "sendok makan"
      }
    ]
  }
]
';

// Remove the placeholder if JSON is provided
if (trim($jsonString) === '') {
    echo "❌ Please paste the JSON result from GPT in the \$jsonString variable above.\n";
    echo "\nSteps:\n";
    echo "1. Copy JSON output from GPT\n";
    echo "2. Paste it in \$jsonString variable (replace the example)\n";
    echo "3. Run: php convert-recipe.php\n";
    echo "4. Copy the PHP array output to DummyRecipeSeeder.php\n";
    exit;
}

$data = json_decode($jsonString, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "❌ JSON Error: " . json_last_error_msg() . "\n";
    echo "Please check the JSON format.\n";
    exit;
}

if (!is_array($data) || empty($data)) {
    echo "❌ Invalid data format. Expected array of recipes.\n";
    exit;
}

echo "✅ Successfully parsed " . count($data) . " recipes\n\n";

// Show preview
echo "Preview of first recipe:\n";
echo "- Title: " . ($data[0]['title'] ?? 'N/A') . "\n";
echo "- Category: " . ($data[0]['category'] ?? 'N/A') . "\n";
echo "- Ingredients: " . count($data[0]['ingredients'] ?? []) . "\n\n";

echo "=== Copy this PHP array to DummyRecipeSeeder.php ===\n\n";
echo '$recipes = ';
echo var_export($data, true);
echo ";\n\n";

echo "=== Instructions ===\n";
echo "1. Copy the PHP array above\n";
echo "2. Paste it in database/seeders/DummyRecipeSeeder.php (\$recipes variable)\n";
echo "3. Uncomment the foreach loop in the seeder\n";
echo "4. Run: php artisan db:seed --class=DummyRecipeSeeder\n";

// Validation summary
$categories = array_count_values(array_column($data, 'category'));
echo "\nCategory distribution:\n";
foreach ($categories as $category => $count) {
    echo "- $category: $count recipes\n";
}
?>
