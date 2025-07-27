<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOORA Recipe Recommendation Tester</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .ingredient-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .ingredient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .ingredient-card.selected {
            border-color: #0d6efd;
            background-color: #e7f3ff;
        }
        .moora-score {
            font-size: 1.2em;
            font-weight: bold;
            color: #0d6efd;
        }
        .recipe-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }
        .recipe-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .score-breakdown {
            font-size: 0.9em;
        }
        .missing-ingredients {
            color: #dc3545;
            font-size: 0.85em;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .method-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .loading {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card method-info">
                    <div class="card-body text-center">
                        <h1 class="card-title mb-3">
                            <i class="fas fa-brain me-2"></i>
                            MOORA Recipe Recommendation Tester
                        </h1>
                        <p class="card-text">
                            Multi-Objective Optimization Ratio Analysis untuk Sistem Penunjang Keputusan Rekomendasi Resep
                        </p>
                        <small>Kriteria: Kecocokan Bahan (70%) + Efisiensi Waktu (30%)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Form -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-sliders-h me-2"></i>
                            Parameter Testing
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Time Range -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="minTime" class="form-label">
                                    <i class="fas fa-clock me-1"></i>
                                    Waktu Minimum (menit)
                                </label>
                                <input type="number" class="form-control" id="minTime" value="15" min="1">
                            </div>
                            <div class="col-md-6">
                                <label for="maxTime" class="form-label">
                                    <i class="fas fa-clock me-1"></i>
                                    Waktu Maksimum (menit)
                                </label>
                                <input type="number" class="form-control" id="maxTime" value="45" min="1">
                            </div>
                        </div>

                        <!-- Available Ingredients -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-carrot me-1"></i>
                                Bahan yang Tersedia (klik untuk pilih)
                            </label>
                            <div id="ingredientsList" class="row g-2">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Selected Ingredients Display -->
                        <div class="mb-3">
                            <label class="form-label">Bahan Terpilih:</label>
                            <div id="selectedIngredients" class="alert alert-info">
                                Belum ada bahan yang dipilih
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="button" class="btn btn-primary me-md-2" onclick="getMooraRecommendations()">
                                <i class="fas fa-search me-1"></i>
                                Dapatkan Rekomendasi MOORA
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearSelections()">
                                <i class="fas fa-eraser me-1"></i>
                                Reset
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="testConnection()">
                                <i class="fas fa-wifi me-1"></i>
                                Test Koneksi
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="getMethodExplanation()">
                                <i class="fas fa-info-circle me-1"></i>
                                Penjelasan Metode
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Panel -->
            <div class="col-md-4">
                <div id="statisticsPanel" class="card stats-card" style="display: none;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-1"></i>
                            Statistik Analisis
                        </h6>
                    </div>
                    <div class="card-body" id="statisticsContent">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div class="text-center loading" id="loadingIndicator">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Menghitung rekomendasi MOORA...</p>
        </div>

        <!-- Results -->
        <div id="resultsContainer">
            <!-- Will be populated by JavaScript -->
        </div>

        <!-- JSON Output Display (similar to recipe-tester.blade.php) -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-code me-2"></i>
                    Raw JSON Output
                    <button class="btn btn-sm btn-outline-secondary float-end" onclick="copyJsonToClipboard()">
                        <i class="fas fa-copy me-1"></i>Copy JSON
                    </button>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Request JSON:</h6>
                        <pre id="requestDisplay" class="response-area" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; max-height: 400px; overflow-y: auto; font-size: 0.85em;">No request sent yet</pre>
                    </div>
                    <div class="col-md-6">
                        <h6>Response JSON:</h6>
                        <pre id="responseDisplay" class="response-area" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; max-height: 400px; overflow-y: auto; font-size: 0.85em;">No response yet</pre>
                    </div>
                </div>
                
                <!-- Flutter Code Generator -->
                <div class="mt-3">
                    <button class="btn btn-info btn-sm" onclick="generateFlutterCode()" id="generateFlutterBtn" style="display: none;">
                        <i class="fab fa-flutter me-1"></i>
                        Generate Flutter Code
                    </button>
                </div>
                
                <!-- Generated Flutter Code -->
                <div id="flutterCodeContainer" style="display: none;" class="mt-3">
                    <h6>Generated Flutter Code:</h6>
                    <pre id="flutterCodeDisplay" class="response-area" style="background-color: #263238; color: #eeffff; border: 1px solid #37474f; border-radius: 5px; padding: 15px; max-height: 400px; overflow-y: auto; font-size: 0.8em;"></pre>
                    <button class="btn btn-sm btn-success mt-2" onclick="copyFlutterCode()">
                        <i class="fas fa-copy me-1"></i>Copy Flutter Code
                    </button>
                </div>
            </div>
        </div>

        <!-- Method Explanation Modal -->
        <div class="modal fade" id="methodModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Penjelasan Metode MOORA
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="methodExplanationContent">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedIngredients = [];
        let availableIngredients = [];

        // Load ingredients when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            console.log('Current URL:', window.location.href);
            loadIngredients();
        });

        // Load available ingredients
        async function loadIngredients() {
            console.log('Loading ingredients...');
            
            try {
                const response = await fetch('/api/bahans', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('API Response:', data);
                
                if (data.success && data.data) {
                    availableIngredients = data.data;
                    console.log('Loaded ingredients:', availableIngredients.length);
                    displayIngredients();
                } else {
                    console.error('API returned unsuccessful response:', data);
                    document.getElementById('ingredientsList').innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                API Response: ${data.message || 'Unknown error'}
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Fetch error:', error);
                document.getElementById('ingredientsList').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            Error: ${error.message}
                            <br><small>Pastikan server Laravel berjalan dan API endpoint tersedia</small>
                        </div>
                    </div>
                `;
            }
        }

        // Display ingredients as selectable cards
        function displayIngredients() {
            console.log('Displaying ingredients:', availableIngredients.length);
            const container = document.getElementById('ingredientsList');
            container.innerHTML = '';

            if (!availableIngredients || availableIngredients.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tidak ada data bahan yang tersedia
                        </div>
                    </div>
                `;
                return;
            }

            availableIngredients.forEach((ingredient, index) => {
                console.log(`Ingredient ${index + 1}:`, ingredient);
                
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6 mb-2';
                
                // Parse units safely
                let unitsText = '';
                try {
                    if (ingredient.units) {
                        const units = JSON.parse(ingredient.units);
                        unitsText = `<br><small class="text-muted">Units: ${units.slice(0, 3).join(', ')}${units.length > 3 ? '...' : ''}</small>`;
                    }
                } catch (e) {
                    console.warn('Error parsing units for ingredient:', ingredient.name, e);
                }
                
                col.innerHTML = `
                    <div class="card ingredient-card h-100" onclick="toggleIngredient(${ingredient.id})" data-ingredient-id="${ingredient.id}">
                        <div class="card-body text-center p-2">
                            <small class="fw-bold">${ingredient.name}</small>
                            <br>
                            <span class="badge bg-secondary">${ingredient.category || 'Umum'}</span>
                            ${unitsText}
                        </div>
                    </div>
                `;
                
                container.appendChild(col);
            });

            // Update info text
            const infoText = document.createElement('div');
            infoText.className = 'col-12 mt-2';
            infoText.innerHTML = `
                <small class="text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Berhasil memuat ${availableIngredients.length} bahan. Klik untuk memilih.
                </small>
            `;
            container.appendChild(infoText);
            
            console.log('Ingredients displayed successfully');
        }

        // Toggle ingredient selection
        function toggleIngredient(ingredientId) {
            // Find the card element that was clicked
            const card = document.querySelector(`[data-ingredient-id="${ingredientId}"]`);
            if (!card) return;
            
            const index = selectedIngredients.indexOf(ingredientId);
            
            if (index === -1) {
                selectedIngredients.push(ingredientId);
                card.classList.add('selected');
            } else {
                selectedIngredients.splice(index, 1);
                card.classList.remove('selected');
            }
            
            updateSelectedDisplay();
        }

        // Update selected ingredients display
        function updateSelectedDisplay() {
            const display = document.getElementById('selectedIngredients');
            
            if (selectedIngredients.length === 0) {
                display.innerHTML = 'Belum ada bahan yang dipilih';
                display.className = 'alert alert-info';
            } else {
                const selectedNames = selectedIngredients.map(id => {
                    const ingredient = availableIngredients.find(ing => ing.id === id);
                    return ingredient ? ingredient.name : '';
                }).filter(name => name);
                
                display.innerHTML = `
                    <strong>${selectedIngredients.length} bahan terpilih:</strong><br>
                    ${selectedNames.join(', ')}
                `;
                display.className = 'alert alert-success';
            }
        }

        // Test connection to API
        async function testConnection() {
            console.log('Testing connection...');
            
            try {
                const response = await fetch('/api/bahans', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    alert(`✅ Koneksi berhasil!\n\nStatus: ${response.status}\nJumlah bahan: ${data.data ? data.data.length : 0}`);
                    
                    // Reload ingredients if test successful
                    loadIngredients();
                } else {
                    alert(`❌ Koneksi gagal!\n\nStatus: ${response.status}\nResponse: ${await response.text()}`);
                }
            } catch (error) {
                alert(`❌ Error koneksi!\n\nError: ${error.message}\n\nPastikan server Laravel berjalan dan API endpoint tersedia`);
            }
        }

        // Clear all selections
        function clearSelections() {
            selectedIngredients = [];
            document.querySelectorAll('.ingredient-card').forEach(card => {
                card.classList.remove('selected');
            });
            updateSelectedDisplay();
            document.getElementById('resultsContainer').innerHTML = '';
            document.getElementById('statisticsPanel').style.display = 'none';
        }

        // Get MOORA recommendations
        async function getMooraRecommendations() {
            if (selectedIngredients.length === 0) {
                alert('Pilih minimal 1 bahan terlebih dahulu!');
                return;
            }

            const minTime = parseInt(document.getElementById('minTime').value);
            const maxTime = parseInt(document.getElementById('maxTime').value);

            if (minTime >= maxTime) {
                alert('Waktu maksimum harus lebih besar dari waktu minimum!');
                return;
            }

            // Show loading
            document.getElementById('loadingIndicator').style.display = 'block';
            document.getElementById('resultsContainer').innerHTML = '';

            const requestData = {
                available_ingredients: selectedIngredients,
                min_cooking_time: minTime,
                max_cooking_time: maxTime
            };

            try {
                console.log('Sending MOORA request:', requestData);

                // Display request JSON
                document.getElementById('requestDisplay').textContent = JSON.stringify(requestData, null, 2);

                const response = await fetch('/api/recommendations/moora', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(requestData)
                });

                console.log('MOORA Response status:', response.status);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }

                const data = await response.json();
                console.log('MOORA Response data:', data);
                
                // Display response JSON
                document.getElementById('responseDisplay').textContent = JSON.stringify(data, null, 2);
                
                // Show Flutter code generator button
                document.getElementById('generateFlutterBtn').style.display = 'inline-block';
                
                if (data.success) {
                    displayResults(data);
                    if (data.metadata) {
                        displayStatistics(data.metadata);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('MOORA Error:', error);
                
                // Display error in response
                const errorResponse = {
                    success: false,
                    error: error.message,
                    timestamp: new Date().toISOString()
                };
                document.getElementById('responseDisplay').textContent = JSON.stringify(errorResponse, null, 2);
                
                document.getElementById('resultsContainer').innerHTML = `
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
                        <p><strong>Gagal mendapatkan rekomendasi:</strong></p>
                        <p>${error.message}</p>
                        <small>Pastikan server berjalan dan endpoint tersedia di: <code>/api/recommendations/moora</code></small>
                    </div>
                `;
            } finally {
                document.getElementById('loadingIndicator').style.display = 'none';
            }
        }

        // Display results
        function displayResults(data) {
            const container = document.getElementById('resultsContainer');
            
            if (data.data.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Tidak Ada Rekomendasi</h5>
                        <p>Tidak ditemukan resep yang memenuhi kriteria dengan skor minimum 0.3.</p>
                        <p>Coba dengan:</p>
                        <ul>
                            <li>Menambah lebih banyak bahan</li>
                            <li>Memperluas rentang waktu</li>
                            <li>Memilih bahan yang lebih umum</li>
                        </ul>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Hasil Rekomendasi MOORA (${data.total_results} resep)
                        </h5>
                        <small class="text-muted">
                            Kriteria: ${data.search_criteria.ingredient_names.join(', ')} | 
                            Waktu: ${data.search_criteria.time_range}
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
            `;

            data.data.forEach((item, index) => {
                const recipe = item.recipe;
                const rankBadge = index < 3 ? `<span class="badge bg-warning text-dark">Top ${index + 1}</span>` : '';
                
                html += `
                    <div class="col-md-6">
                        <div class="card recipe-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">${recipe.title}</h6>
                                    ${rankBadge}
                                </div>
                                
                                <div class="moora-score mb-2">
                                    MOORA Score: ${item.moora_score}
                                </div>
                                
                                <div class="score-breakdown mb-2">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted">Bahan</small><br>
                                            <span class="badge bg-primary">${item.ingredient_score} (${item.match_percentage}%)</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Waktu</small><br>
                                            <span class="badge bg-info">${item.time_score}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <small><strong>Waktu Memasak:</strong> ${item.cooking_time} menit (${item.time_efficiency})</small><br>
                                    <small><strong>Bahan Cocok:</strong> ${item.matching_ingredients}/${item.total_ingredients}</small>
                                </div>
                                
                                ${item.missing_ingredients.length > 0 ? `
                                    <div class="missing-ingredients">
                                        <strong>Bahan yang dibutuhkan:</strong><br>
                                        ${item.missing_ingredients.join(', ')}
                                    </div>
                                ` : `
                                    <div class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Semua bahan tersedia!
                                    </div>
                                `}
                                
                                <div class="mt-2">
                                    <small class="text-muted">Oleh: ${recipe.user.name}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `
                        </div>
                    </div>
                </div>
            `;

            container.innerHTML = html;
        }

        // Display statistics
        function displayStatistics(stats) {
            const panel = document.getElementById('statisticsPanel');
            const content = document.getElementById('statisticsContent');
            
            content.innerHTML = `
                <div class="mb-3">
                    <h6>Data Analysis</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-utensils me-1"></i> ${stats.ingredient_stats.total_available} bahan tersedia</li>
                        <li><i class="fas fa-list me-1"></i> ${stats.ingredient_stats.recipes_analyzed} resep dianalisis</li>
                        <li><i class="fas fa-clock me-1"></i> ${stats.ingredient_stats.total_in_timerange} total dalam rentang waktu</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6>Score Distribution</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check me-1"></i> ${stats.score_distribution.qualifying_recipes} resep lolos threshold</li>
                        <li><i class="fas fa-calculator me-1"></i> Skor rata-rata: ${stats.score_distribution.avg_score}</li>
                        <li><i class="fas fa-arrow-up me-1"></i> Skor tertinggi: ${stats.score_distribution.max_score}</li>
                    </ul>
                </div>
                
                <div>
                    <h6>MOORA Config</h6>
                    <ul class="list-unstyled mb-0">
                        <li>Bobot Bahan: ${(stats.moora_config.ingredient_weight * 100)}%</li>
                        <li>Bobot Waktu: ${(stats.moora_config.time_weight * 100)}%</li>
                        <li>Threshold: ${stats.moora_config.minimum_threshold}</li>
                    </ul>
                </div>
            `;
            
            panel.style.display = 'block';
        }

        // Get method explanation
        async function getMethodExplanation() {
            try {
                const response = await fetch('/api/recommendations/method-explanation');
                const data = await response.json();
                
                if (data.success) {
                    displayMethodExplanation(data);
                    new bootstrap.Modal(document.getElementById('methodModal')).show();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Copy JSON to clipboard
        function copyJsonToClipboard() {
            const responseText = document.getElementById('responseDisplay').textContent;
            if (responseText && responseText !== 'No response yet') {
                navigator.clipboard.writeText(responseText).then(() => {
                    alert('JSON copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = responseText;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('JSON copied to clipboard!');
                });
            } else {
                alert('No JSON response to copy');
            }
        }

        // Generate Flutter code
        function generateFlutterCode() {
            const requestText = document.getElementById('requestDisplay').textContent;
            const responseText = document.getElementById('responseDisplay').textContent;
            
            if (!requestText || !responseText || responseText === 'No response yet') {
                alert('No valid JSON data to generate Flutter code');
                return;
            }

            try {
                const requestData = JSON.parse(requestText);
                const responseData = JSON.parse(responseText);
                
                let flutterCode = `
// MOORA API Integration - Generated Code
// Generated on: ${new Date().toISOString()}

import 'dart:convert';
import 'package:http/http.dart' as http;

class MooraApiService {
  static const String baseUrl = 'https://your-domain.com/api';
  
  // Request model for MOORA recommendations
  static Future<MooraResponse> getMooraRecommendations(
    List<int> availableIngredients, 
    int minCookingTime, 
    int maxCookingTime
  ) async {
    try {
      final request = {
        'available_ingredients': availableIngredients,
        'min_cooking_time': minCookingTime,
        'max_cooking_time': maxCookingTime,
      };
      
      print('Request: \${jsonEncode(request)}');
      
      final response = await http.post(
        Uri.parse('\$baseUrl/recommendations/moora'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode(request),
      );
      
      print('Response Status: \${response.statusCode}');
      print('Response Body: \${response.body}');
      
      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonData = jsonDecode(response.body);
        return MooraResponse.fromJson(jsonData);
      } else {
        throw Exception('HTTP \${response.statusCode}: \${response.body}');
      }
    } catch (e) {
      print('Error: \$e');
      rethrow;
    }
  }
}

// Data models for the response
class MooraResponse {
  final bool success;
  final List<RecipeRecommendation> data;
  final MooraMetadata? metadata;
  
  MooraResponse({
    required this.success,
    required this.data,
    this.metadata,
  });
  
  factory MooraResponse.fromJson(Map<String, dynamic> json) {
    return MooraResponse(
      success: json['success'] ?? false,
      data: (json['data'] as List<dynamic>?)
          ?.map((item) => RecipeRecommendation.fromJson(item))
          .toList() ?? [],
      metadata: json['metadata'] != null 
          ? MooraMetadata.fromJson(json['metadata']) 
          : null,
    );
  }
}

class RecipeRecommendation {
  final int id;
  final String title;
  final String description;
  final int cookingTime;
  final String category;
  final double mooraScore;
  final double ingredientMatchPercentage;
  final double timeEfficiencyPercentage;
  final int availableIngredientsCount;
  final int totalIngredientsCount;
  final RecipeUser user;
  
  RecipeRecommendation({
    required this.id,
    required this.title,
    required this.description,
    required this.cookingTime,
    required this.category,
    required this.mooraScore,
    required this.ingredientMatchPercentage,
    required this.timeEfficiencyPercentage,
    required this.availableIngredientsCount,
    required this.totalIngredientsCount,
    required this.user,
  });
  
  factory RecipeRecommendation.fromJson(Map<String, dynamic> json) {
    return RecipeRecommendation(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      cookingTime: json['cooking_time'] ?? 0,
      category: json['category'] ?? '',
      mooraScore: (json['moora_score'] ?? 0).toDouble(),
      ingredientMatchPercentage: (json['ingredient_match_percentage'] ?? 0).toDouble(),
      timeEfficiencyPercentage: (json['time_efficiency_percentage'] ?? 0).toDouble(),
      availableIngredientsCount: json['available_ingredients_count'] ?? 0,
      totalIngredientsCount: json['total_ingredients_count'] ?? 0,
      user: RecipeUser.fromJson(json['user'] ?? {}),
    );
  }
}

class RecipeUser {
  final int id;
  final String name;
  
  RecipeUser({
    required this.id,
    required this.name,
  });
  
  factory RecipeUser.fromJson(Map<String, dynamic> json) {
    return RecipeUser(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
    );
  }
}

class MooraMetadata {
  final int totalEligibleRecipes;
  final int totalRecommended;
  final double thresholdUsed;
  final MooraWeights weights;
  
  MooraMetadata({
    required this.totalEligibleRecipes,
    required this.totalRecommended,
    required this.thresholdUsed,
    required this.weights,
  });
  
  factory MooraMetadata.fromJson(Map<String, dynamic> json) {
    return MooraMetadata(
      totalEligibleRecipes: json['total_eligible_recipes'] ?? 0,
      totalRecommended: json['total_recommended'] ?? 0,
      thresholdUsed: (json['threshold_used'] ?? 0).toDouble(),
      weights: MooraWeights.fromJson(json['weights'] ?? {}),
    );
  }
}

class MooraWeights {
  final double ingredientWeight;
  final double timeWeight;
  
  MooraWeights({
    required this.ingredientWeight,
    required this.timeWeight,
  });
  
  factory MooraWeights.fromJson(Map<String, dynamic> json) {
    return MooraWeights(
      ingredientWeight: (json['ingredient_weight'] ?? 0).toDouble(),
      timeWeight: (json['time_weight'] ?? 0).toDouble(),
    );
  }
}

// Example usage in your Flutter widget:
/*
class MooraTestPage extends StatefulWidget {
  @override
  _MooraTestPageState createState() => _MooraTestPageState();
}

class _MooraTestPageState extends State<MooraTestPage> {
  List<int> selectedIngredients = ${JSON.stringify(requestData.available_ingredients)};
  int minCookingTime = ${JSON.stringify(requestData.min_cooking_time)};
  int maxCookingTime = ${JSON.stringify(requestData.max_cooking_time)};
  bool isLoading = false;
  MooraResponse? response;
  
  void getMooraRecommendations() async {
    setState(() {
      isLoading = true;
    });
    
    try {
      final result = await MooraApiService.getMooraRecommendations(
        selectedIngredients, 
        minCookingTime, 
        maxCookingTime
      );
      setState(() {
        response = result;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
      });
      // Show error message
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: \$e')),
      );
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('MOORA Recommendations')),
      body: Column(
        children: [
          // Your ingredient selection UI here
          ElevatedButton(
            onPressed: getMooraRecommendations,
            child: Text('Get Recommendations'),
          ),
          if (isLoading) CircularProgressIndicator(),
          if (response != null) 
            Expanded(
              child: ListView.builder(
                itemCount: response!.data.length,
                itemBuilder: (context, index) {
                  final recipe = response!.data[index];
                  return Card(
                    child: ListTile(
                      title: Text(recipe.title),
                      subtitle: Text('Score: \${recipe.mooraScore.toStringAsFixed(4)}'),
                      trailing: Text('\${recipe.cookingTime} min'),
                    ),
                  );
                },
              ),
            ),
        ],
      ),
    );
  }
}
*/
`;

                document.getElementById('flutterCodeDisplay').textContent = flutterCode;
                document.getElementById('flutterCodeContainer').style.display = 'block';
                
            } catch (error) {
                alert('Error generating Flutter code: ' + error.message);
            }
        }

        // Copy Flutter code to clipboard
        function copyFlutterCode() {
            const flutterCode = document.getElementById('flutterCodeDisplay').textContent;
            if (flutterCode) {
                navigator.clipboard.writeText(flutterCode).then(() => {
                    alert('Flutter code copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = flutterCode;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Flutter code copied to clipboard!');
                });
            }
        }

        // Display method explanation
        function displayMethodExplanation(data) {
            const content = document.getElementById('methodExplanationContent');
            
            content.innerHTML = `
                <div class="mb-4">
                    <h6>${data.method}</h6>
                    <p>${data.description}</p>
                </div>
                
                <div class="mb-4">
                    <h6>Kriteria Penilaian:</h6>
                    <div class="row">
                        ${data.criteria.map(criterion => `
                            <div class="col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <h6>${criterion.name}</h6>
                                        <span class="badge bg-primary">${criterion.weight}</span>
                                        <span class="badge bg-success">${criterion.type}</span>
                                        <p class="mt-2 mb-0 small">${criterion.description}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6>Langkah Perhitungan:</h6>
                    <ol>
                        ${data.calculation_steps.map(step => `<li>${step}</li>`).join('')}
                    </ol>
                </div>
                
                <div class="alert alert-info">
                    <h6>Parameter Kualitas:</h6>
                    <ul class="mb-0">
                        <li>Skor minimum: ${data.threshold.minimum_score}</li>
                        <li>Maksimal hasil: ${data.threshold.max_results}</li>
                        <li>Syarat: ${data.threshold.required_criteria}</li>
                    </ul>
                </div>
            `;
        }
    </script>
</body>
</html>
