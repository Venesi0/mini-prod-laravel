#!/bin/sh
set -e

echo "[ENTRYPOINT] Initialisation runtime Laravel..."

# Créer les répertoires runtime s'ils n'existent pas
mkdir -p storage/framework/cache/data \
            storage/framework/sessions \
            storage/framework/views \
            storage/logs \
            bootstrap/cache

# Permissions pour le processus web (www-data)
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Nettoyer les anciens fichiers runtime (si présents)
rm -f storage/framework/cache/data/* \
      storage/framework/sessions/* \
      storage/framework/views/* \
      bootstrap/cache/*.php 2>/dev/null || true

# Créer les .gitignore runtime
printf "*\n!.gitignore\n" > storage/framework/cache/.gitignore
printf "*\n!.gitignore\n" > storage/framework/sessions/.gitignore
printf "*\n!.gitignore\n" > storage/framework/views/.gitignore

echo "[ENTRYPOINT] Nettoyage des caches Laravel..."
php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true
php artisan cache:clear >/dev/null 2>&1 || true

echo "[ENTRYPOINT] Démarrage PHP-FPM..."
exec "$@"
