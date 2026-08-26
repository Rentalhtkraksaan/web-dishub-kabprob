@echo off
echo Menjalankan Auto-Push ke GitHub setiap 30 detik...
echo Tekan CTRL+C untuk berhenti.

:loop
git add .
:: Hanya commit jika ada perubahan untuk mencegah error
git commit -m "Auto-commit: update file otomatis"
git push
:: Menunggu selama 30 detik sebelum mengecek lagi
timeout /t 30 >nul
goto loop
