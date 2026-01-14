# 👥 Panduan Kolaborasi Tim - Git Workflow

## ✅ Status: Data sudah di-push ke GitHub
- **Repository**: https://github.com/arkannz/projectzip.git
- **Branch**: `main`
- **Commit terbaru**: Merge fitur Data Angkutan, Activity Log, dan perbaikan RAB Type 60-107

---

## 📥 UNTUK ANGGOTA TIM: Cara Pull Data Terbaru

### STEP 1: Pastikan Anda di Branch Main

```bash
# Cek branch saat ini
git branch

# Jika tidak di main, switch ke main
git checkout main
```

### STEP 2: Pull Data Terbaru dari GitHub

```bash
# Pull data terbaru dari remote
git pull origin main
```

**PENTING**: Jika ada conflict, jangan panic! Ikuti langkah di bagian "Resolve Conflict" di bawah.

### STEP 3: Update Dependencies

Setelah pull, pastikan semua dependencies ter-update:

```bash
# Install/update Composer dependencies
composer install

# Install/update NPM dependencies (jika ada)
npm install

# Run migrations (jika ada migration baru)
php artisan migrate

# Run seeders (jika ada seeder baru)
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 🔄 WORKFLOW KERJA BERSAMA

### ✅ Workflow yang BENAR (Recommended)

#### 1. **Sebelum Mulai Bekerja**

```bash
# 1. Pastikan di branch main
git checkout main

# 2. Pull data terbaru
git pull origin main

# 3. Buat branch baru untuk fitur Anda
git checkout -b fitur/nama-fitur-anda
```

#### 2. **Saat Bekerja**

```bash
# Lakukan perubahan di branch fitur Anda
# ... edit file ...

# Commit perubahan secara berkala
git add .
git commit -m "Deskripsi perubahan yang jelas"
```

#### 3. **Sebelum Push**

```bash
# 1. Update branch main dulu
git checkout main
git pull origin main

# 2. Kembali ke branch fitur
git checkout fitur/nama-fitur-anda

# 3. Merge atau rebase main ke branch fitur (untuk update)
git merge main
# ATAU
git rebase main

# 4. Resolve conflict jika ada (lihat bagian di bawah)

# 5. Push branch fitur ke GitHub
git push origin fitur/nama-fitur-anda
```

#### 4. **Setelah Selesai**

- Buat Pull Request (PR) di GitHub
- Atau minta team lead untuk merge ke main

---

## ⚠️ RESOLVE CONFLICT (Jika Ada)

### Jika ada conflict saat pull:

```bash
# 1. Git akan memberitahu file yang conflict
# 2. Buka file yang conflict, cari tanda:
<<<<<<< HEAD
# Kode dari remote (GitHub)
=======
# Kode dari local (Anda)
>>>>>>> branch-name

# 3. Pilih kode yang benar atau gabungkan keduanya
# 4. Hapus tanda conflict markers (<<<<<<<, =======, >>>>>>>)

# 5. Setelah selesai, add file yang sudah di-resolve
git add nama-file-yang-conflict.php

# 6. Commit resolve
git commit -m "Resolve conflict: nama file"

# 7. Lanjutkan pull
git pull origin main
```

### Jika conflict terlalu banyak dan ingin menggunakan versi remote:

```bash
# HATI-HATI! Ini akan menghapus perubahan local Anda
git fetch origin
git reset --hard origin/main
```

---

## 🚫 YANG TIDAK BOLEH DILAKUKAN

### ❌ JANGAN:

1. **Jangan langsung push ke main tanpa pull dulu**
   ```bash
   # SALAH:
   git push origin main
   
   # BENAR:
   git pull origin main
   git push origin main
   ```

2. **Jangan force push ke main**
   ```bash
   # JANGAN LAKUKAN INI:
   git push -f origin main
   ```

3. **Jangan commit file `.env`**
   - File `.env` sudah ada di `.gitignore`
   - Pastikan tidak ter-commit dengan `git status`

4. **Jangan commit file `vendor/` atau `node_modules/`**
   - Folder ini sudah ada di `.gitignore`

---

## 📋 CHECKLIST SEBELUM PUSH

Sebelum push perubahan, pastikan:

- [ ] Sudah pull data terbaru dari main
- [ ] Tidak ada conflict
- [ ] File `.env` tidak ter-commit
- [ ] File `vendor/` tidak ter-commit
- [ ] File `node_modules/` tidak ter-commit
- [ ] Commit message jelas dan deskriptif
- [ ] Kode sudah di-test (jika memungkinkan)

---

## 🔍 COMMAND YANG SERING DIGUNAKAN

### Cek Status
```bash
# Cek status repository
git status

# Cek branch yang aktif
git branch

# Cek semua branch (termasuk remote)
git branch -a

# Cek commit history
git log --oneline -10
```

### Update dari Remote
```bash
# Pull data terbaru
git pull origin main

# Atau fetch dulu, lalu merge
git fetch origin
git merge origin/main
```

### Switch Branch
```bash
# Switch ke branch lain
git checkout nama-branch

# Buat branch baru dan switch
git checkout -b nama-branch-baru
```

### Commit & Push
```bash
# Add semua perubahan
git add .

# Commit dengan pesan
git commit -m "Deskripsi perubahan"

# Push ke remote
git push origin nama-branch
```

---

## 🆘 TROUBLESHOOTING

### Error: "Your branch is behind 'origin/main'"

```bash
# Solusi: Pull dulu
git pull origin main
```

### Error: "Merge conflict"

Lihat bagian "Resolve Conflict" di atas.

### Error: "Permission denied"

```bash
# Pastikan Anda sudah login ke GitHub
# Atau setup SSH key
```

### Error: "fatal: refusing to merge unrelated histories"

```bash
# Jika memang perlu merge, gunakan flag:
git pull origin main --allow-unrelated-histories
```

---

## 📞 BANTUAN

Jika ada masalah atau pertanyaan:
1. Cek dokumentasi ini dulu
2. Tanyakan ke team lead
3. Cek commit history: `git log --oneline -20`

---

## ✅ SETUP AWAL (Untuk Member Baru)

Jika ini pertama kali clone project:

```bash
# 1. Clone repository
git clone https://github.com/arkannz/projectzip.git
cd projectzip

# 2. Install dependencies
composer install
npm install

# 3. Copy .env file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Setup database di .env
# Edit file .env dan isi:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=projectzip
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Run seeders (jika perlu)
php artisan db:seed

# 8. Create storage link
php artisan storage:link

# 9. Run development server
php artisan serve
```

---

**Selamat bekerja! 🚀**

*Last updated: Setelah push fitur Data Angkutan dan Activity Log*




