# Cara Mengupdate File ke GitHub

## Langkah-langkah Update File ke GitHub

### 1. Cek Status Perubahan
```powershell
git status
```
Menampilkan file yang diubah, ditambah, atau dihapus.

### 2. Tambahkan File ke Staging Area
```powershell
# Tambahkan file tertentu
git add nama-file.php

# Tambahkan semua file yang berubah
git add .

# Tambahkan file tertentu di folder
git add app/Models/User.php
```

### 3. Commit Perubahan
```powershell
git commit -m "Pesan commit yang menjelaskan perubahan"
```

**Contoh pesan commit yang baik:**
- `git commit -m "feat: Add user registration feature"`
- `git commit -m "fix: Fix database connection error"`
- `git commit -m "update: Update dependencies"`
- `git commit -m "docs: Update README.md"`

### 4. Push ke GitHub
```powershell
git push origin main
```

Atau jika branch lain:
```powershell
git push origin nama-branch
```

## Contoh Lengkap

```powershell
# 1. Cek status
git status

# 2. Tambahkan file
git add .

# 3. Commit
git commit -m "feat: Add new feature"

# 4. Push ke GitHub
git push origin main
```

## Workflow Lengkap untuk File Baru

```powershell
# 1. Buat/edit file baru
# (misalnya: app/Models/Product.php)

# 2. Cek status
git status

# 3. Tambahkan file baru
git add app/Models/Product.php

# 4. Commit
git commit -m "feat: Add Product model"

# 5. Push ke GitHub
git push origin main
```

## Tips

1. **Selalu cek status** sebelum commit
2. **Gunakan pesan commit yang jelas** dan deskriptif
3. **Commit sering** dengan perubahan kecil daripada satu commit besar
4. **Pull dulu** jika bekerja dalam tim: `git pull origin main`
5. **Jangan commit file sensitif** seperti `.env`, password, dll

## Mengatasi Konflik

Jika ada konflik saat push:
```powershell
# 1. Pull dulu
git pull origin main

# 2. Selesaikan konflik di file
# 3. Tambahkan file yang sudah diperbaiki
git add .

# 4. Commit
git commit -m "fix: Resolve merge conflicts"

# 5. Push lagi
git push origin main
```

## Branch Protection (Main Dilindungi)

Jika branch `main` dilindungi dan tidak bisa langsung push:

### Solusi: Gunakan Branch Baru + Pull Request

```powershell
# 1. Buat branch baru
git checkout -b nama-branch-baru

# 2. Lakukan perubahan dan commit
git add .
git commit -m "feat: Add new feature"

# 3. Push ke branch baru
git push origin nama-branch-baru

# 4. Buka GitHub dan buat Pull Request
# 5. Merge Pull Request untuk menggabungkan ke main
```

### Contoh Workflow dengan Branch Protection

```powershell
# 1. Buat branch untuk fitur baru
git checkout -b feature/tambah-fitur

# 2. Edit file
# ... buat perubahan ...

# 3. Commit
git add .
git commit -m "feat: Tambah fitur baru"

# 4. Push ke branch
git push origin feature/tambah-fitur

# 5. Di GitHub: Buat Pull Request dari feature/tambah-fitur ke main
# 6. Review dan merge Pull Request
```

