#!/usr/bin/env bash
set -e

COMPOSE_FILE="infra/compose/docker-compose.yml"
URL="http://localhost"

echo "[INFO] État Docker Compose"
docker compose -f "$COMPOSE_FILE" ps

echo
echo "[INFO] Test HTTP"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$URL")
echo "HTTP code: $HTTP_CODE"

if [ "$HTTP_CODE" != "200" ]; then
    echo "[ERROR] Réponse HTTP inattendue"
    exit 1
fi

echo
echo "[INFO] Test PostgreSQL"
docker compose -f "$COMPOSE_FILE" exec db pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"

echo
echo "[INFO] Contrôle rapide OK"