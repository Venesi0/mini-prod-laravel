#!/usr/bin/env bash
set -e

APP_ENV_FILE="app/laravel/.env"
APP_ENV_EXAMPLE="app/laravel/.env.example"

DB_PASSWORD_DEFAULT="laravelpassword"

if [ -f "$APP_ENV_FILE" ]; then
  echo "[INFO] $APP_ENV_FILE existe déjà, aucune initialisation nécessaire."
  exit 0
fi

if [ ! -f "$APP_ENV_EXAMPLE" ]; then
  echo "[ERREUR] Fichier $APP_ENV_EXAMPLE introuvable."
  exit 1
fi

echo "[INFO] Création de $APP_ENV_FILE à partir de .env.example"
cp "$APP_ENV_EXAMPLE" "$APP_ENV_FILE"

echo "[INFO] Remplacement du mot de passe PostgreSQL par une valeur cohérente avec Docker Compose"
sed -i "s/^DB_PASSWORD=change_me$/DB_PASSWORD=$DB_PASSWORD_DEFAULT/" "$APP_ENV_FILE"

echo "[INFO] Génération d'une APP_KEY Laravel"
APP_KEY="base64:$(openssl rand -base64 32 | tr -d '\n')"
sed -i "s|^APP_KEY=$|APP_KEY=$APP_KEY|" "$APP_ENV_FILE"

echo "[OK] Fichier .env initialisé : $APP_ENV_FILE"
echo "[INFO] Vérifie si besoin les variables DB_* avant un usage hors démo locale."
