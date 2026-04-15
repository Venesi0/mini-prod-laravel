#!/usr/bin/env bash
set -e

COMPOSE_FILE="infra/compose/docker-compose.yml"

echo "[INFO] Arrêt de la stack..."
docker compose -f "$COMPOSE_FILE" stop

echo
echo "[INFO] État des services après arrêt :"
docker compose -f "$COMPOSE_FILE" ps