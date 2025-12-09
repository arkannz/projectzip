# Script untuk mengatasi PowerShell Execution Policy
# Jalankan dengan: .\fix-execution-policy.ps1

Write-Host "`n=== Mengatasi PowerShell Execution Policy ===" -ForegroundColor Cyan

# Set execution policy untuk current process (tidak permanen, aman)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope Process -Force

Write-Host "✓ Execution Policy sudah di-set untuk session ini" -ForegroundColor Green
Write-Host "`nUntuk set permanen (opsional):" -ForegroundColor Yellow
Write-Host "  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser" -ForegroundColor White
Write-Host "`nSekarang npm dan script PowerShell lainnya bisa dijalankan!" -ForegroundColor Green
Write-Host "`n" -ForegroundColor White


