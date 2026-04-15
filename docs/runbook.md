# Runbook d'exploitation

## 1. Objet

Ce document décrit les opérations courantes d'exploitation de la stack personnelle de déploiement :

- Nginx en frontal
- application Laravel via PHP-FPM
- PostgreSQL
- orchestration via Docker Compose

Ce runbook couvre uniquement l'exploitation courante :

- démarrage ;
- arrêt ;
- redémarrage ;
- vérifications de santé ;
- consultation des logs ;
- premiers diagnostics en cas d'incident ;
- usage des scripts utilitaires d'exploitation.

Il ne couvre pas :

- le développement de l'application Laravel ;
- les évolutions d'architecture ;
- le monitoring avancé (prévu en phase 5).

## 2. Périmètre et conventions

### Environnement concerné

- Hôte : VM Ubuntu Server
- Source de vérité : dépôt GitHub
- Usage de la VM : déploiement et test d'exploitation
- Développement applicatif : machine personnelle, hors VM

### Répertoires du projet

- `app/laravel/` : code applicatif Laravel
- `infra/compose/` : orchestration Docker Compose
- `infra/nginx/` : configuration Nginx
- `infra/php/` : image, Dockerfile et entrypoint PHP
- `infra/postgres/` : éléments liés à PostgreSQL
- `docs/` : documentation projet
- `scripts/` : scripts utilitaires d'exploitation

### Fichier Compose de référence

Toutes les commandes d'exploitation utilisent :

```bash
infra/compose/docker-compose.yml
```

## 3. Prérequis d'exploitation

Avant toute action :

- être connecté en SSH sur la VM Ubuntu Server ;
- être placé à la racine du projet ;
- vérifier que Docker est disponible ;
- vérifier que Docker Compose fonctionne.

Commandes de contrôle :

```bash
pwd
docker --version
docker compose version
```

## 4. Lancement de la stack

### Démarrage standard

```bash
docker compose -f infra/compose/docker-compose.yml up -d
```

### Démarrage via script

```bash
./scripts/up.sh
```

Le script `up.sh` :

- lance la stack ;
- affiche l'état des services ;
- affiche l'URL locale `http://localhost` ;
- affiche l'URL réseau de la VM si elle est détectée automatiquement.

### Vérification immédiate après démarrage

```bash
docker compose -f infra/compose/docker-compose.yml ps
```

Résultat attendu :

- les conteneurs principaux sont présents ;
- les services sont en état `Up` ;
- aucun conteneur ne redémarre en boucle.

## 5. Arrêt de la stack

### Arrêt simple

```bash
docker compose -f infra/compose/docker-compose.yml stop
```

### Arrêt via script

```bash
./scripts/down.sh
```

Usage :

- arrêt temporaire ;
- maintenance courte ;
- conservation des conteneurs existants.

### Arrêt complet avec suppression des conteneurs

```bash
docker compose -f infra/compose/docker-compose.yml down
```

Usage :

- reconstruction propre ;
- redéploiement contrôlé ;
- nettoyage de la stack.

Attention :

- ne pas utiliser `down` sans raison si l'objectif est seulement de stopper temporairement l'environnement ;
- bien distinguer `stop` et `down`.

## 6. Redémarrage de la stack

### Redémarrage complet

```bash
docker compose -f infra/compose/docker-compose.yml restart
```

### Redémarrage d'un service spécifique

```bash
docker compose -f infra/compose/docker-compose.yml restart nginx
docker compose -f infra/compose/docker-compose.yml restart app
docker compose -f infra/compose/docker-compose.yml restart db
```

Usage :

- appliquer un changement mineur de configuration ;
- relancer un service bloqué ;
- vérifier le comportement d'un composant sans redéployer tout l'ensemble.

## 7. Vérifications de santé

### Contrôle rapide via script

```bash
./scripts/healthcheck.sh
```

Le script vérifie :

- l'état Docker Compose ;
- la réponse HTTP ;
- la disponibilité PostgreSQL.

### État global des conteneurs

```bash
docker compose -f infra/compose/docker-compose.yml ps
```

À vérifier :

- tous les services attendus sont présents ;
- statut `Up` ;
- pas de redémarrages répétés.

### Vérification HTTP

```bash
curl -s -o /dev/null -w "%{http_code}\n" http://localhost
curl -I http://localhost
```

Interprétation simple :

- `200` : frontal HTTP fonctionnel ;
- `502` : suspicion sur l'application PHP-FPM ou le lien Nginx -> app ;
- `500` : suspicion d'erreur applicative côté Laravel ;
- absence de réponse : suspicion sur Nginx ou sur l'exposition du service.

### Vérification depuis le réseau local

Adapter selon l'IP courante de la VM :

```bash
curl -I http://IP_DE_LA_VM
curl http://IP_DE_LA_VM
```

### Vérification PostgreSQL

```bash
docker compose -f infra/compose/docker-compose.yml exec db pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"
```

Résultat attendu :

- PostgreSQL répond comme service disponible.

## 8. Logs utiles

### Logs de tous les services

```bash
docker compose -f infra/compose/docker-compose.yml logs
```

### Logs d'un service spécifique

```bash
docker compose -f infra/compose/docker-compose.yml logs nginx
docker compose -f infra/compose/docker-compose.yml logs app
docker compose -f infra/compose/docker-compose.yml logs db
```

### Suivi temps réel

```bash
docker compose -f infra/compose/docker-compose.yml logs -f
```

### Suivi temps réel d'un service

```bash
docker compose -f infra/compose/docker-compose.yml logs -f nginx
docker compose -f infra/compose/docker-compose.yml logs -f app
docker compose -f infra/compose/docker-compose.yml logs -f db
```

## 9. Diagnostic de premier niveau

### Cas 1 : le site ne répond pas

Vérifications :

1. contrôler l'état des conteneurs ;
2. vérifier les logs Nginx ;
3. vérifier les logs de l'application ;
4. vérifier que PostgreSQL est disponible ;
5. tester l'accès HTTP local sur la VM.

### Cas 2 : erreur 502 Bad Gateway

Hypothèses :

- service `app` arrêté ;
- PHP-FPM inaccessible ;
- problème de réseau interne Docker ;
- configuration Nginx incorrecte.

Commandes utiles :

```bash
docker compose -f infra/compose/docker-compose.yml ps
docker compose -f infra/compose/docker-compose.yml logs --tail=100 nginx
docker compose -f infra/compose/docker-compose.yml logs --tail=100 app
```

### Cas 3 : erreur applicative 500

Hypothèses :

- variable d'environnement incorrecte ;
- cache Laravel incohérent ;
- permissions incorrectes sur `storage` ou `bootstrap/cache` ;
- problème d'accès base de données ;
- erreur interne de l'application.

Commandes utiles :

```bash
docker compose -f infra/compose/docker-compose.yml logs --tail=100 app
docker compose -f infra/compose/docker-compose.yml exec app php artisan about
docker compose -f infra/compose/docker-compose.yml exec app sh -lc 'ls -ld storage storage/logs storage/framework storage/framework/cache storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache'
```

### Réparation du runtime Laravel

En cas d'erreur 500 liée au cache, aux sessions, aux vues compilées ou aux permissions de `storage` / `bootstrap/cache`, exécuter :

```bash
./scripts/fix-laravel-runtime.sh
```

Ce script :

- recrée les répertoires runtime manquants ;
- remet les permissions nécessaires au processus web ;
- nettoie les caches runtime ;
- relance les commandes Laravel de nettoyage ;
- termine par un healthcheck.

### Correction structurelle en place

Le conteneur `app` utilise désormais un entrypoint personnalisé :

- `infra/php/docker-entrypoint.sh`

Son rôle est de :

- créer les répertoires runtime Laravel au démarrage ;
- remettre les permissions nécessaires sur `storage` et `bootstrap/cache` ;
- nettoyer les caches Laravel ;
- lancer ensuite `php-fpm`.

Objectif :

- éviter les erreurs 500 récurrentes après redémarrage de la stack.

### Cas 4 : base de données indisponible

Hypothèses :

- conteneur `db` arrêté ;
- identifiants incorrects ;
- base non prête ;
- volume ou initialisation en erreur.

Commandes utiles :

```bash
docker compose -f infra/compose/docker-compose.yml logs --tail=100 db
docker compose -f infra/compose/docker-compose.yml exec db pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"
```

## 10. Scripts utilitaires

Les scripts dans `scripts/` simplifient les opérations courantes.

### Scripts disponibles

| Script                             | Usage              | Action                                                          |
| ---------------------------------- | ------------------ | --------------------------------------------------------------- |
| `./scripts/up.sh`                  | Démarrage          | Lance la stack, affiche l'état Compose et les URL d'accès       |
| `./scripts/down.sh`                | Arrêt              | Stoppe proprement la stack                                      |
| `./scripts/healthcheck.sh`         | Contrôle           | Vérifie conteneurs, HTTP et PostgreSQL                          |
| `./scripts/fix-laravel-runtime.sh` | Remédiation        | Répare le runtime Laravel en cas d'erreur 500                   |
| `./scripts/fix-git-permissions.sh` | Maintenance locale | Redonne la main à l'utilisateur système pour les opérations Git |

### Workflow typique après incident Laravel

```bash
./scripts/fix-laravel-runtime.sh
./scripts/healthcheck.sh
./scripts/fix-git-permissions.sh
git pull --rebase origin main
```

## 11. Principes d'exploitation retenus

- garder les commandes simples et explicites ;
- distinguer clairement lancement, arrêt, contrôle et remédiation ;
- privilégier les vérifications observables ;
- documenter les incidents réels rencontrés ;
- préparer une exploitation lisible avant d'ajouter du monitoring.

## 12. Suite prévue

La phase suivante portera sur l'observabilité et le monitoring :

- Prometheus ;
- Grafana ;
- node_exporter.

Cette phase ne sera engagée qu'une fois l'exploitation courante stabilisée et documentée.
