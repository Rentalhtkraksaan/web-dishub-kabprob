#!/bin/bash
echo "Menjalankan Auto-Push ke GitHub setiap 30 detik..."
echo "Tekan CTRL+C untuk berhenti."

while true
do
    git add .
    git commit -m "Auto-commit: update file otomatis"
    git push
    sleep 30
done
