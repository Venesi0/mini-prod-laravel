#!/usr/bin/env bash
set -e

COMPOSE_FILE="infra/compose/docker-compose.yml"
APP_SERVICE="app"

echo "[INFO] Réparation du runtime Laravel..."

echo "[INFO] Création des répertoires runtime nécessaires"
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" sh -lc '
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache
'

echo "[INFO] Remise des permissions pour le processus web"
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" sh -lc '
chown -R www-data:www-data storage bootstrap/cache &&
chmod -R 775 storage bootstrap/cache
'

echo "[INFO] Nettoyage des fichiers runtime"
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" sh -lc '
rm -rf storage/framework/cache/data/* \
       storage/framework/sessions/* \
       storage/framework/views/* \
       bootstrap/cache/*.php
'

echo "[INFO] Réinitialisation des .gitignore runtime"
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" sh -lc '
printf "*\n!.gitignore\n" > storage/framework/cache/.gitignore &&
printf "*\n!.gitignore\n" > storage/framework/sessions/.gitignore &&
printf "*\n!.gitignore\n" > storage/framework/views/.gitignore
'

echo "[INFO] Nettoyage des caches Laravel"
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" php artisan config:clear
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" php artisan route:clear
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" php artisan view:clear
docker compose -f "$COMPOSE_FILE" exec "$APP_SERVICE" php artisan cache:clear

echo "[INFO] Vérification finale"
./scripts/healthcheck.sh

echo "[OK] Runtime Laravel réparé et healthcheck relancé."
