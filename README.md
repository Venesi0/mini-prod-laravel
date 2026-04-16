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

Phase 4 terminée :

- application Laravel importée dans `app/laravel/`
- image PHP-FPM dédiée construite pour l’application
- Nginx configuré comme frontal HTTP
- PostgreSQL intégré à la stack Docker Compose
- variables d’environnement adaptées à la cible conteneurisée
- stack lancée et validée
- accès à l’application via la VM
- base initialisée et seeders exécutés
- runbook d’exploitation rédigé et consolidé
- scripts d’exploitation ajoutés (`up.sh`, `down.sh`, `healthcheck.sh`)
- incident runtime Laravel analysé et corrigé
- entrypoint de démarrage ajouté pour fiabiliser `storage/` et `bootstrap/cache`

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

## Pré-requis

Pour lancer le projet depuis un clone, il faut disposer au minimum de :

- Git
- Docker
- Docker Compose v2

Sous Windows, le lancement est prévu via Docker Desktop avec WSL2, puis depuis un terminal Ubuntu WSL. Docker Desktop intègre Docker Compose et s’appuie sur le backend WSL2 pour les workflows Linux.

## Démarrage rapide depuis un clone

ATTENTION : Les scripts doivent IMPERATIVEMENT se lancer depuis la racine du projet

Depuis la racine du projet :

```bash
git clone <repo>
cd mini-prod-laravel
./scripts/up.sh
./scripts/healthcheck.sh
```

[INFO] : L'application web n'est pas encore aboutie, mais si vous voulez vous balader un peu dedans, utilisez les identifiants suivants pour vous connecter :

        - email : admin@projecta.com
        - mot de passe : admin00

Ce que fait `./scripts/up.sh`
Le script up.sh prépare un premier lancement reproductible :

- Crée app/laravel/.env à partir de .env.example si nécessaire

- Génère une APP_KEY Laravel

- Aligne la configuration applicative avec les identifiants PostgreSQL attendus par Docker Compose

- Lance la stack Docker Compose

- Installe les dépendances PHP via Composer

- Nettoie les caches Laravel

- Exécute les migrations

- Exécute les seeders

- Affiche les URL d’accès et l’état des services

## Lancement de la stack

Depuis la racine du projet :

```bash
./scripts/up.sh
```

Ou en commande longue :

```bash
docker compose -f infra/compose/docker-compose.yml up -d
```

Vérifier l’état des conteneurs :

```bash
docker compose -f infra/compose/docker-compose.yml ps
```

## Commandes utiles

Contrôle rapide de la stack :

```bash
./scripts/healthcheck.sh
```

Arrêt propre :

```bash
./scripts/down.sh
```

Réparation en cas d'erreur 500 du au Runtime Laravel :

```bash
./scripts/fix-laravel-runtime.sh
```

Remise des permissions locales pour les opérations Git sur la VM :

```bash
./scripts/fix-git-permissions.sh
```

Lancer les migrations manuellement :

```bash
docker compose -f infra/compose/docker-compose.yml exec app php artisan migrate
```

Exécuter les seeders manuellement :

```bash
docker compose -f infra/compose/docker-compose.yml exec app php artisan db:seed
```

Réinitialiser complètement la base de test :

```bash
docker compose -f infra/compose/docker-compose.yml exec app php artisan migrate:fresh --seed
```

## Accès

L'adresse de l'application vous sera normalement donné lors de l'éxécution de `./script/up.sh`

- Depuis une machine lançant directement Docker Compose : http://localhost

- Depuis la machine hôte d’une VM : selon la configuration réseau VirtualBox, soit via l’IP de la VM, soit via une redirection de port

## Résultat attendu après un clone

Après exécution de `./scripts/up.sh`, on attend :

- Les services nginx, app et db en état Up

- Une réponse HTTP cohérente sur http://localhost

- Une base initialisée

- Un contrôle rapide valide via `./scripts/healthcheck.sh`

- Un accès fonctionnel à l’application et à l’authentification

## Points d’attention

Les scripts doivent IMPERATIVEMENT se lancer depuis la racine du projet.

Le script init-env.sh ne modifie pas un .env déjà présent.

Les identifiants Laravel (DB\_\*) doivent rester cohérents avec les variables PostgreSQL du service db.

PostgreSQL persiste ses données dans un volume Docker ; un ancien volume peut donc conserver un état incohérent avec une nouvelle configuration. La persistance PostgreSQL via volume fait partie de l’architecture retenue.

En cas de reconstruction propre du test local :

```bash
docker compose -f infra/compose/docker-compose.yml down -v
./scripts/up.sh
```

## Limites actuelles

Cette phase vise un socle d’exploitation simple.
Le monitoring, l’observabilité, l’automatisation de déploiement et le durcissement plus poussé sont prévus dans les phases suivantes.

## Suite prévue

- Phase 5 : monitoring avec Prometheus / Grafana / node_exporter
- Phase 6 : amélioration de la qualité de déploiement et de la documentation d’exploitation
