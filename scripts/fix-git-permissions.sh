#!/usr/bin/env bash
set -e

echo "[INFO] Remise des permissions Git pour noxy..."

# Seulement sur les fichiers suivis par Git
sudo find . -path './app/laravel/storage' -type f -exec chown noxy:noxy {} \; || true
sudo find . -path './app/laravel/bootstrap/cache' -type f -exec chown noxy:noxy {} \; || true

# Remet les répertoires principaux pour Git
sudo chown -R noxy:noxy app/laravel/storage app/laravel/bootstrap/cache

echo "[INFO] Git permissions restaurées"
echo "[INFO] Tu peux maintenant faire git status / git pull / git push"
