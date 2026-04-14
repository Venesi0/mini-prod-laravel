# Mini Prod Laravel

Projet personnel orienté production informatique / DevOps visant à construire un mini environnement de production autour d'une application Laravel existante.

## Objectif

Mettre en place une stack simple, lisible et défendable, inspirée d’un déploiement web classique :

- Ubuntu Server (VM VirtualBox)
- Docker Compose
- Nginx en frontal
- Laravel / PHP-FPM
- PostgreSQL
- monitoring prévu plus tard avec Prometheus, Grafana et node_exporter

## Contexte

Le développement applicatif est réalisé sur la machine personnelle.
Le dépôt GitHub est la source de vérité.
La VM Ubuntu Server sert d’hôte de déploiement et de validation d’exploitation.

## État actuel

Phase 3 terminée :

- application Laravel importée dans `app/laravel/`
- image PHP-FPM dédiée construite pour l’application
- Nginx configuré comme frontal HTTP
- PostgreSQL intégré à la stack Docker Compose
- variables d’environnement adaptées à la cible conteneurisée
- stack lancée et validée
- accès à l’application via la VM
- base initialisée et seeders exécutés

## Structure du projet

```text
app/laravel/           code source Laravel
infra/compose/         définition Docker Compose
infra/nginx/           configuration Nginx
infra/php/             Dockerfile et configuration PHP
infra/postgres/        éléments liés à PostgreSQL
infra/prometheus/      réservé pour la suite
infra/grafana/         réservé pour la suite
docs/                  documentation projet
scripts/               scripts utilitaires
```

## Stack technique

- Nginx
- PHP-FPM
- Laravel
- PostgreSQL
- Docker Compose
- Ubuntu Server

## Lancement de la stack

Depuis la racine du projet :

```bash
docker compose -f infra/compose/docker-compose.yml up -d --build
```

Vérifier l’état des conteneurs :

```bash
docker compose -f infra/compose/docker-compose.yml ps
```

## Commandes utiles

Vider les caches Laravel :

```bash
docker compose -f infra/compose/docker-compose.yml exec app php artisan config:clear
docker compose -f infra/compose/docker-compose.yml exec app php artisan route:clear
docker compose -f infra/compose/docker-compose.yml exec app php artisan view:clear
```

Lancer les migrations :

```bash
docker compose -f infra/compose/docker-compose.yml exec app php artisan migrate
```

Exécuter les seeders :

```bash
docker compose -f infra/compose/docker-compose.yml exec app php artisan db:seed
```

## Accès

- Depuis la VM : `http://localhost`
- Depuis la machine hôte : selon la configuration réseau VirtualBox, soit via l’IP de la VM, soit via une redirection de port

## Limites actuelles

Cette phase vise un socle d’exploitation simple.
Le monitoring, l’observabilité, l’automatisation de déploiement et le durcissement plus poussé sont prévus dans les phases suivantes.

## Suite prévue

- Phase 4 : stabilisation exploitation et runbook
- Phase 5 : monitoring avec Prometheus / Grafana / node_exporter
- Phase 6 : amélioration de la qualité de déploiement et de la documentation d’exploitation
