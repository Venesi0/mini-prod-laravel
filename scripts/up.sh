#!/usr/bin/env bash
set -e

COMPOSE_FILE="infra/compose/docker-compose.yml"

echo "[INFO] Démarrage de la stack..."
docker compose -f "$COMPOSE_FILE" up -d

echo
echo "[INFO] État des services :"
docker compose -f "$COMPOSE_FILE" ps

VM_IP="$(hostname -I | awk '{print $1}')"

echo
echo "[INFO] Accès à l'application"
echo "Local :  http://localhost"

if [ -n "$VM_IP" ]; then
  echo "Réseau : http://$VM_IP"
else
  echo "Réseau : IP non détectée automatiquement"
fi
