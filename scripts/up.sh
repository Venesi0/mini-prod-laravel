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

echo "[INFO] Lancement de la stack Docker Compose"
docker compose -f "$COMPOSE_FILE" up -d --build

echo "[INFO] Attente de quelques secondes pour l'initialisation"
sleep 5

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
