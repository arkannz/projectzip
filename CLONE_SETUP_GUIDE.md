# 📋 Panduan Clone dan Setup Project

Panduan lengkap untuk clone dan setup project Laravel ini di laptop masing-masing anggota tim.

---

## 📌 Prerequisites (Persyaratan)

Sebelum memulai, pastikan laptop Anda sudah terinstall:

### 1. **Git**
- Download: https://git-scm.com/downloads
- Verifikasi: `git --version`

### 2. **PHP 8.2 atau lebih tinggi**
- Download: https://www.php.net/downloads.php
- Atau gunakan Laragon (Windows): https://laragon.org/
- Verifikasi: `php -v`

### 3. **Composer**
- Download: https://getcomposer.org/download/
- Verifikasi: `composer --version`

### 4. **Node.js & NPM**
- Download: https://nodejs.org/
- Verifikasi: `node -v` dan `npm -v`

### 5. **Database (MySQL/MariaDB)**
- MySQL: https://dev.mysql.com/downloads/
- MariaDB: https://mariadb.org/download/
- Atau gunakan Laragon yang sudah include MySQL

### 6. **Code Editor (Opsional)**
- Visual Studio Code: https://code.visualstudio.com/
- PHPStorm: https://www.jetbrains.com/phpstorm/

---

## 🚀 Langkah-langkah Clone dan Setup

### **Langkah 1: Clone Repository dari GitHub**

1. Buka terminal/command prompt di folder tempat Anda ingin menyimpan project (contoh: `C:\laragon\www\`)

2. Clone repository:
```bash
git clone https://github.com/arkannz/projectzip.git
```

3. Masuk ke folder project:
```bash
cd projectzip
```

---

### **Langkah 2: Install Dependencies PHP (Composer)**

1. Install semua dependencies PHP:
```bash
composer install
```

**Catatan:** Jika terjadi error, pastikan:
- PHP sudah terinstall dengan benar
- Composer sudah terinstall
- Internet connection stabil

---

### **Langkah 3: Setup File Environment (.env)**

1. Copy file `.env.example` menjadi `.env`:
```bash
# Windows (PowerShell)
Copy-Item .env.example .env

# Windows (CMD)
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

2. Buka file `.env` dengan text editor dan sesuaikan konfigurasi database:

```env
APP_NAME="Project ZIP"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projectzip
DB_USERNAME=root
DB_PASSWORD=

# Sesuaikan dengan konfigurasi database Anda
```

**Catatan:**
- `DB_DATABASE`: Nama database yang akan dibuat (bisa diganti sesuai keinginan)
- `DB_USERNAME`: Username database (default Laragon: `root`)
- `DB_PASSWORD`: Password database (default Laragon: kosong)

---

### **Langkah 4: Generate Application Key**

Generate application key untuk Laravel:
```bash
php artisan key:generate
```

---

### **Langkah 5: Buat Database**

1. Buka phpMyAdmin atau MySQL client:
   - Laragon: Klik "Database" di menu Laragon
   - Atau buka: http://localhost/phpmyadmin

2. Buat database baru:
   - Nama database: `projectzip` (atau sesuai yang ada di `.env`)
   - Collation: `utf8mb4_unicode_ci`

**Atau gunakan command line:**
```bash
mysql -u root -p
CREATE DATABASE projectzip CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

---

### **Langkah 6: Run Migrations dan Seeders**

1. Jalankan migrations untuk membuat tabel database:
```bash
php artisan migrate
```

2. Jalankan seeders untuk mengisi data awal:
```bash
php artisan db:seed
```

**Catatan:** Seeders akan mengisi database dengan data dummy untuk testing.

---

### **Langkah 7: Setup Storage Link**

Buat symbolic link untuk storage:
```bash
php artisan storage:link
```

---

### **Langkah 8: Install Dependencies Frontend (NPM)**

1. Install dependencies frontend:
```bash
npm install
```

2. Build assets (jika diperlukan):
```bash
npm run build
```

**Atau untuk development dengan watch mode:**
```bash
npm run dev
```

---

### **Langkah 9: Jalankan Server Development**

1. **Menggunakan Laragon:**
   - Pastikan Laragon sudah running
   - Project akan otomatis accessible di: `http://projectzip.test`

2. **Menggunakan PHP Built-in Server:**
```bash
php artisan serve
```
   - Buka browser: `http://localhost:8000`

3. **Menggunakan Laravel Sail (Docker):**
```bash
./vendor/bin/sail up
```

---

### **Langkah 10: Login ke Aplikasi**

1. Buka browser dan akses:
   - Laragon: `http://projectzip.test`
   - PHP Built-in: `http://localhost:8000`

2. Halaman akan redirect ke login page

3. **Untuk login pertama kali**, buat user baru melalui register atau jalankan seeder user:
```bash
php artisan db:seed --class=UserSeeder
```

**Catatan:** Jika belum ada seeder user, buat user manual melalui register atau phpMyAdmin.

---

## 🔧 Troubleshooting (Pemecahan Masalah)

### **Error: "Class not found" atau "Composer autoload"**
```bash
composer dump-autoload
```

### **Error: "Permission denied" pada storage atau cache**
```bash
# Windows (jika menggunakan Git Bash)
chmod -R 775 storage bootstrap/cache

# Atau set permission manual di Windows Explorer
```

### **Error: Database connection failed**
- Pastikan MySQL/MariaDB sudah running
- Cek konfigurasi di `.env` (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Pastikan database sudah dibuat

### **Error: "npm install" gagal**
- Pastikan Node.js sudah terinstall
- Coba hapus `node_modules` dan `package-lock.json`, lalu install ulang:
```bash
rm -rf node_modules package-lock.json
npm install
```

### **Error: "Vite manifest not found"**
```bash
npm run build
# atau
npm run dev
```

### **Error: Route tidak ditemukan**
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

---

## 📝 Checklist Setup

Gunakan checklist ini untuk memastikan semua langkah sudah dilakukan:

- [ ] Git terinstall dan terverifikasi
- [ ] PHP 8.2+ terinstall dan terverifikasi
- [ ] Composer terinstall dan terverifikasi
- [ ] Node.js & NPM terinstall dan terverifikasi
- [ ] Database (MySQL/MariaDB) terinstall dan running
- [ ] Repository berhasil di-clone
- [ ] `composer install` berhasil
- [ ] File `.env` sudah dibuat dan dikonfigurasi
- [ ] `php artisan key:generate` berhasil
- [ ] Database sudah dibuat
- [ ] `php artisan migrate` berhasil
- [ ] `php artisan db:seed` berhasil
- [ ] `php artisan storage:link` berhasil
- [ ] `npm install` berhasil
- [ ] `npm run build` atau `npm run dev` berhasil
- [ ] Server development berjalan
- [ ] Aplikasi bisa diakses di browser
- [ ] Login/Register berfungsi

---

## 🔄 Update Project (Pull Latest Changes)

Ketika ada perubahan dari tim lain, update project Anda:

1. **Pull latest changes:**
```bash
git pull origin main
```

2. **Install dependencies baru (jika ada):**
```bash
composer install
npm install
```

3. **Run migrations baru (jika ada):**
```bash
php artisan migrate
```

4. **Clear cache:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📚 Informasi Tambahan

### **Struktur Project:**
```
projectzip/
├── app/                    # Application logic
├── bootstrap/              # Bootstrap files
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── public/                 # Public assets
├── resources/              # Views, CSS, JS
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploads
├── tests/                  # Test files
├── vendor/                 # Composer dependencies
└── .env                    # Environment configuration
```

### **URL Repository:**
- GitHub: https://github.com/arkannz/projectzip.git
- Branch utama: `main`

### **Kontak Support:**
Jika mengalami masalah, hubungi:
- Team Lead atau
- Buat issue di GitHub repository

---

## ✅ Selesai!

Setelah semua langkah di atas selesai, project Anda sudah siap digunakan. Selamat coding! 🎉

---

**Catatan Penting:**
- Jangan commit file `.env` ke repository (sudah ada di `.gitignore`)
- Selalu pull sebelum mulai bekerja untuk mendapatkan perubahan terbaru
- Ikuti workflow Git yang sudah ditetapkan (lihat `COLLABORATION_GUIDE.md`)

