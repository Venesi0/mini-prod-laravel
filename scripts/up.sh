#!/usr/bin/env bash
set -e

COMPOSE_FILE="infra/compose/docker-compose.yml"

echo "[INFO] Démarrage de la stack..."
docker compose -f "COMPOSE_FILE" up -d

echo
echo "[INFO] État des services :"
docker compose -f "$COMPOSE_FILE" ps