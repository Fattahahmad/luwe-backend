# 🌐 Cross-Device API Access Setup Guide

## 📱 Problem: "Error Failed to Fetch Beda Device"

Ketika mengakses API dari device yang berbeda (misalnya dari Flutter app di HP ke Laravel API di laptop), sering muncul error "failed to fetch". Berikut panduan lengkap untuk mengatasi masalah ini.

## 🔍 Step-by-Step Troubleshooting

### 1. **Cek IP Address Server**

**Windows (Command Prompt):**

```cmd
ipconfig
```

**Mac/Linux (Terminal):**

```bash
ifconfig
```

**Cari IP address yang aktif, contoh:**

-   `192.168.1.100` (WiFi)
-   `192.168.0.50` (LAN)

### 2. **Update Base URL**

**❌ Jangan gunakan:**

-   `http://localhost/luwe/public`
-   `http://127.0.0.1/luwe/public`

**✅ Gunakan IP yang benar:**

-   `http://192.168.1.100/luwe/public`
-   `http://192.168.0.50/luwe/public`

### 3. **Test Koneksi dari Browser**

Buka browser di device yang berbeda dan akses:

```
http://192.168.1.100/luwe/public/debug-cors
```

**Jika berhasil**, akan tampil:

```json
{
    "status": "CORS working",
    "server_ip": "192.168.1.100",
    "client_ip": "192.168.1.101",
    "timestamp": "2025-01-16T10:30:00Z"
}
```

### 4. **Test API Endpoint**

Test endpoint API:

```
http://192.168.1.100/luwe/public/api/debug
```

**Response sukses:**

```json
{
    "status": "API working",
    "timestamp": "2025-01-16T10:30:00Z",
    "server_info": {
        "server_ip": "192.168.1.100",
        "client_ip": "192.168.1.101"
    }
}
```

## 🔧 XAMPP Configuration

### 1. **Apache Configuration**

Edit file `C:\xampp\apache\conf\httpd.conf`:

**Cari baris:**

```apache
Listen 80
```

**Pastikan tidak ada:**

```apache
Listen 127.0.0.1:80
```

### 2. **Virtual Host (Optional)**

Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/luwe/public"
    ServerName luwe.local
    <Directory "C:/xampp/htdocs/luwe/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 3. **Restart Apache**

Restart Apache dari XAMPP Control Panel.

## 🔥 Firewall Settings

### Windows Firewall

1. Buka **Windows Defender Firewall**
2. Klik **Allow an app or feature through Windows Defender Firewall**
3. Cari **Apache HTTP Server** atau tambah aplikasi baru
4. Centang **Public** dan **Private**
5. Atau disable firewall sementara untuk testing

### Router/Network

-   Pastikan kedua device (server dan client) terhubung ke WiFi/LAN yang sama
-   Cek apakah router memblokir komunikasi antar device
-   Beberapa router memiliki setting "AP Isolation" yang harus dimatikan

## 📱 Flutter App Configuration

**Ganti base URL di Flutter app:**

```dart
// ❌ Jangan gunakan
const String baseUrl = 'http://localhost/luwe/public';

// ✅ Gunakan IP server
const String baseUrl = 'http://192.168.1.100/luwe/public';
```

**Jika masih error, tambahkan ke AndroidManifest.xml:**

```xml
<application
    android:usesCleartextTraffic="true"
    ...>
```

## 🔍 Debug Endpoints

### 1. **Basic Connectivity Test**

```
GET http://192.168.1.100/luwe/public/api/debug
```

### 2. **CORS Test**

```
GET http://192.168.1.100/luwe/public/debug-cors
```

### 3. **Test dari Postman**

Import collection `Luwe_API.postman_collection.json` dan:

1. Set environment variable `base_url` = `http://192.168.1.100/luwe/public`
2. Test folder **Debug Routes**
3. Jika debug test sukses, lanjut test endpoint lain

## ❗ Common Issues & Solutions

### Issue 1: "Connection Refused"

**Penyebab:** Server tidak accessible dari network
**Solusi:**

-   Cek Apache configuration
-   Restart XAMPP
-   Test dari browser dulu

### Issue 2: "CORS Error"

**Penyebab:** CORS headers tidak benar
**Solusi:**

-   Cek file `app/Http/Middleware/Cors.php`
-   Sudah dikonfigurasi dengan benar di project ini

### Issue 3: "Timeout"

**Penyebab:** Firewall atau network blocking
**Solusi:**

-   Disable firewall sementara
-   Cek router settings
-   Pastikan kedua device di network yang sama

### Issue 4: "Failed to Fetch"

**Penyebab:** Base URL salah atau network issue
**Solusi:**

-   Double-check IP address
-   Test dengan debug endpoints
-   Cek dari browser dulu

## 📊 Network Testing Commands

### Test Ping

```cmd
ping 192.168.1.100
```

### Test Port

```cmd
telnet 192.168.1.100 80
```

### Check Network

```cmd
netstat -an | findstr :80
```

## 🎯 Quick Checklist

-   [ ] Server IP address benar (`ipconfig`)
-   [ ] Base URL menggunakan IP, bukan localhost
-   [ ] Kedua device di network yang sama
-   [ ] Apache accessible dari network
-   [ ] Firewall tidak blocking
-   [ ] Debug endpoints berhasil diakses
-   [ ] CORS headers dikonfigurasi dengan benar

## 📞 Support

Jika masih ada masalah:

1. Coba akses debug endpoint dari browser
2. Screenshot error message
3. Berikan informasi:
    - IP address server
    - Network configuration
    - Error message lengkap
    - Device yang digunakan (HP, laptop, etc.)

## 🔄 Testing Flow

1. **Server Setup**: Start XAMPP, cek IP
2. **Network Test**: Ping dari device lain
3. **Browser Test**: Akses debug endpoint
4. **Postman Test**: Import collection, test debug routes
5. **App Integration**: Update base URL di Flutter app
6. **Final Test**: Test semua endpoints dari app

Success! 🎉 Sekarang API bisa diakses dari device mana saja di network yang sama.
