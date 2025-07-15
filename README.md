# 🍽️ Luwe - Recipe Management API

**Luwe** adalah aplikasi backend untuk manajemen resep makanan yang dibangun dengan Laravel 11. API ini dirancang khusus untuk mendukung aplikasi mobile Flutter dengan sistem autentikasi dan manajemen profil pengguna.

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [API Endpoints](#api-endpoints)
- [Struktur Database](#struktur-database)
- [Testing](#testing)
- [Deployment](#deployment)
- [Kontribusi](#kontribusi)

## 🚀 Fitur Utama

### ✅ Sudah Diimplementasikan

- **Autentikasi Pengguna**
  - Registrasi pengguna baru
  - Login dengan email dan password
  - Logout dengan token invalidation
  - API token menggunakan Laravel Sanctum

- **Manajemen Profil**
  - Lihat profil pengguna
  - Update profil (nama, email, password)
  - Upload dan update profile picture
  - Profile picture default untuk pengguna baru

- **Sistem Gambar**
  - Upload gambar profil dengan validasi
  - Serve gambar melalui API endpoint
  - Optimasi caching untuk performa

### 🔄 Akan Datang

- Manajemen resep (CRUD)
- Kategori resep
- Favorit resep
- Rating dan review
- Pencarian resep

## 🛠️ Teknologi yang Digunakan

- **Backend Framework:** Laravel 11
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **File Storage:** Local Storage (public disk)
- **Image Processing:** Built-in PHP
- **API Format:** JSON REST API
- **Frontend Testing:** Blade Templates (untuk testing)

## 📦 Instalasi

### Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- XAMPP/WAMP/MAMP (untuk development)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd luwe
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   # Buat database MySQL
   mysql -u root -p -e "CREATE DATABASE luwe;"
   
   # Jalankan migration
   php artisan migrate
   
   # Seed dummy data
   php artisan db:seed
   ```

5. **Storage Setup**
   ```bash
   php artisan storage:link
   ```

6. **Run Application**
   ```bash
   php artisan serve
   ```

## ⚙️ Konfigurasi

### Environment Variables

Konfigurasi file `.env`:

```env
# Application
APP_NAME=Luwe
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=luwe
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

### Struktur Direktori

```
luwe/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php      # API Autentikasi
│   │   │   └── ImageController.php     # Serve gambar
│   │   ├── RegisterController.php      # Register web
│   │   └── UserController.php          # Manajemen user
│   └── Models/
│       └── User.php                    # Model user
├── database/
│   ├── migrations/                     # Database migrations
│   ├── factories/                      # Data factories
│   └── seeders/                        # Database seeders
├── public/
│   └── images/
│       └── profiles/                   # Upload profil picture
├── resources/views/                    # Testing interfaces
└── routes/
    ├── api.php                         # API routes
    └── web.php                         # Web routes
```

## 🔗 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Format Request
**API ini mendukung dua format request:**

1. **Raw JSON** (application/json)
2. **Form-data** (multipart/form-data) - **Wajib untuk upload file**

### Autentikasi

#### Register
**Raw JSON:**
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Form-data (Postman):**
```
Method: POST
URL: http://localhost:8000/api/register
Body: form-data
- name: John Doe
- email: john@example.com
- password: password123
- password_confirmation: password123
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "profile_picture": "http://localhost:8000/api/image/default.svg",
      "created_at": "2025-07-06T10:30:00.000000Z"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

#### Login
**Raw JSON:**
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Form-data (Postman):**
```
Method: POST
URL: http://localhost:8000/api/login
Body: form-data
- email: john@example.com
- password: password123
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "profile_picture": "http://localhost:8000/api/image/default.svg",
      "created_at": "2025-07-06T10:30:00.000000Z"
    },
    "token": "2|def456...",
    "token_type": "Bearer"
  }
}
```

### Profil Pengguna (Memerlukan Autentikasi)

#### Get Profile
```http
GET /api/profile
Authorization: Bearer {token}
```

#### Update Profile
**Raw JSON (tanpa file):**
```http
POST /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Updated",
  "email": "john.updated@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Form-data (dengan file - Postman):**
```
Method: POST
URL: http://localhost:8000/api/profile
Headers:
- Authorization: Bearer {token}
Body: form-data
- name: John Updated
- email: john.updated@example.com
- password: newpassword123
- password_confirmation: newpassword123
- profile_picture: [file] (pilih file gambar)
```

#### Logout
**Raw JSON:**
```http
POST /api/logout
Authorization: Bearer {token}
```

**Form-data (Postman):**
```
Method: POST
URL: http://localhost:8000/api/logout
Headers:
- Authorization: Bearer {token}
Body: form-data
(kosong, tidak perlu parameter)
```

### Gambar

#### Get Image
```http
GET /api/image/{filename}
```

## 🗄️ Struktur Database

### Tabel Users

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Nama pengguna |
| email | varchar(255) | Email (unique) |
| email_verified_at | timestamp | Waktu verifikasi email |
| password | varchar(255) | Password (hashed) |
| profile_picture | varchar(255) | Nama file gambar profil |
| remember_token | varchar(100) | Token remember me |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### Tabel Personal Access Tokens (Laravel Sanctum)

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| tokenable_type | varchar(255) | Model type |
| tokenable_id | bigint | Model ID |
| name | varchar(255) | Token name |
| token | varchar(64) | Token hash |
| abilities | text | Token abilities |
| last_used_at | timestamp | Waktu terakhir digunakan |
| expires_at | timestamp | Waktu expire |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## 🧪 Testing

### Web Interface Testing

1. **API Tester**
   ```
   http://localhost:8000/api-tester
   ```
   Interface lengkap untuk test semua endpoint API

2. **User Management**
   ```
   http://localhost:8000/users
   ```
   Melihat daftar semua pengguna

3. **Register Form**
   ```
   http://localhost:8000/register
   ```
   Form registrasi pengguna baru

4. **Login Form**
   ```
   http://localhost:8000/login
   ```
   Form login dengan test JavaScript

### Command Line Testing

```bash
# Test Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Test Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Test Profile (with token)
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Postman Collection

Import file `Luwe_API.postman_collection.json` untuk testing dengan Postman.

## 📋 Validasi dan Error Handling

### Validasi Register
- **name:** required, string, max 255 karakter
- **email:** required, email, unique, max 255 karakter
- **password:** required, min 8 karakter, confirmed

### Validasi Login
- **email:** required, email format
- **password:** required

### Validasi Update Profile
- **name:** optional, string, max 255 karakter
- **email:** optional, email, unique (kecuali email sendiri)
- **password:** optional, min 8 karakter, confirmed
- **profile_picture:** optional, image, max 2MB, format: jpeg,png,jpg,gif,svg

### Error Response Format
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

## 🔐 Security Features

- **Password Hashing:** Menggunakan bcrypt
- **API Token:** Laravel Sanctum untuk autentikasi
- **CSRF Protection:** Untuk web forms
- **Input Validation:** Validasi semua input
- **File Upload Security:** Validasi tipe dan ukuran file
- **SQL Injection Prevention:** Eloquent ORM

## 📈 Performance Optimization

- **Image Caching:** Cache headers untuk gambar (1 tahun)
- **Database Indexing:** Index pada kolom email
- **Token Management:** Auto-delete old tokens saat login
- **File Storage:** Optimasi penyimpanan gambar

## 🚀 Deployment

### Production Environment

1. **Environment Setup**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

2. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

3. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Storage Permissions**
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

## 📖 API Documentation

### Response Format

Semua response API menggunakan format JSON yang konsisten:

```json
{
  "success": true/false,
  "message": "Response message",
  "data": {
    // Response data
  },
  "errors": {
    // Validation errors (jika ada)
  }
}
```

### HTTP Status Codes

- **200:** Success
- **201:** Created
- **401:** Unauthorized
- **422:** Validation Error
- **500:** Server Error

## 🤝 Kontribusi

### Development Workflow

1. **Fork repository**
2. **Create feature branch**
   ```bash
   git checkout -b feature/new-feature
   ```
3. **Commit changes**
   ```bash
   git commit -m "Add new feature"
   ```
4. **Push to branch**
   ```bash
   git push origin feature/new-feature
   ```
5. **Create Pull Request**

### Code Style

- Follow PSR-12 coding standards
- Use meaningful variable names
- Add comments for complex logic
- Write tests for new features

## 📞 Support

Jika Anda memiliki pertanyaan atau menemukan bug, silakan:

1. Buat issue di repository
2. Hubungi tim development
3. Cek dokumentasi API

## 📝 Changelog

### Version 1.0.0
- ✅ Sistem autentikasi lengkap
- ✅ Manajemen profil pengguna
- ✅ Upload profile picture
- ✅ API documentation
- ✅ Testing interface

---

**Dibuat dengan ❤️ untuk Flutter Developer**
