# Shortcut: .\artisan.ps1 <perintah>  (mis. .\artisan.ps1 migrate)
$php = "$env:USERPROFILE\php84\php.exe"
& $php artisan @args
