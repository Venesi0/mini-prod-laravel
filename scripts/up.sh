#!/usr/bin/env bash
set -e

COMPOSE_FILE="infra/compose/docker-compose.yml"
APP_ENV_FILE="app/laravel/.env"

echo "[INFO] Vérification de l'environnement Laravel"
if [ ! -f "$APP_ENV_FILE" ]; then
  echo "[INFO] Aucun fichier .env détecté pour Laravel"
  ./scripts/init-env.sh
else
  echo "[INFO] Fichier .env Laravel déjà présent"
fi

echo "[INFO] Arrêt des conteneurs existant"
docker compose -f "$COMPOSE_FILE" down

echo "[INFO] Reconstruction de l'image sans cache"
docker compose -f "$COMPOSE_FILE" build --no-cache

echo "[INFO] Lancement de la stack Docker Compose"
docker compose -f "$COMPOSE_FILE" up -d 

echo "[INFO] Attente de l'initialisation des services"
sleep 8

echo "[INFO] Installation des dépendances PHP via Composer"
docker compose -f "$COMPOSE_FILE" exec app composer install --no-interaction --prefer-dist

echo "[INFO] Nettoyage des caches Laravel"
docker compose -f "$COMPOSE_FILE" exec app php artisan config:clear
docker compose -f "$COMPOSE_FILE" exec app php artisan route:clear
docker compose -f "$COMPOSE_FILE" exec app php artisan view:clear

echo "[INFO] Initialisation de la base Laravel"
docker compose -f "$COMPOSE_FILE" exec app php artisan migrate --force
docker compose -f "$COMPOSE_FILE" exec app php artisan db:seed --force

echo "[INFO] État Docker Compose"
docker compose -f "$COMPOSE_FILE" ps

echo
echo "[INFO] Accès local attendu : http://localhost"

VM_IP=$(hostname -I | awk '{print $1}')
if [ -n "$VM_IP" ]; then
  echo "[INFO] Accès réseau possible : http://$VM_IP"
fi

echo
echo "[INFO] Vérification recommandée :"
echo "  ./scripts/healthcheck.sh"
