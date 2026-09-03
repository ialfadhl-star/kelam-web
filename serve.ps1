# Menjalankan KELAM dengan PHP 8.4 (XAMPP di mesin ini masih PHP 8.0, terlalu tua untuk Laravel 13).
$php = "$env:USERPROFILE\php84\php.exe"
if (-not (Test-Path $php)) { Write-Host "PHP 8.4 tidak ditemukan di $php" -ForegroundColor Red; exit 1 }
& $php artisan serve --host 127.0.0.1 --port 8000
