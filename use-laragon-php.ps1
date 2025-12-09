# Script untuk menggunakan PHP Laragon
# Jalankan dengan: .\use-laragon-php.ps1

# Set PATH untuk menggunakan PHP Laragon
$env:Path = "C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64;$env:Path"

# Verifikasi PHP version
Write-Host "`n=== PHP Version ===" -ForegroundColor Cyan
php -v

Write-Host "`n✓ PHP Laragon 8.3.16 sudah aktif!" -ForegroundColor Green
Write-Host "Sekarang Anda bisa menjalankan:" -ForegroundColor Yellow
Write-Host "  - php artisan ..." -ForegroundColor White
Write-Host "  - composer ..." -ForegroundColor White
Write-Host "`n" -ForegroundColor White

