# Architecture

## Objectif

Mettre à disposition une application Laravel dans un mini environnement de production personnel, sur Ubuntu Server, avec une architecture Docker Compose simple, lisible et documentée.

## Composants cibles

- Nginx : point d’entrée HTTP de l’application
- PHP-FPM / Laravel : exécution de l’application
- PostgreSQL : base de données relationnelle
- Prometheus : collecte de métriques
- Grafana : visualisation des métriques
- node_exporter : métriques système de la VM

## Principes

- séparation claire des rôles par service
- configuration via variables d’environnement
- persistance des données PostgreSQL via volume Docker
- déploiement reproductible via Docker Compose
- documentation d’exploitation minimale

## Objectif de la phase 3

Construire une première architecture conteneurisée simple, cohérente et présentable pour une application Laravel.

## Vue d’ensemble

La stack repose sur trois services principaux :

- `nginx` : point d’entrée HTTP
- `app` : exécution Laravel via PHP-FPM
- `db` : base de données PostgreSQL

## Flux

```text
Navigateur
    |
    v
Nginx (port 80)
    |
    v
PHP-FPM / Laravel (port 9000 en interne)
    |
    v
PostgreSQL (port 5432 en interne)
```

## Rôle des composants

### Nginx

Nginx sert de frontal HTTP.
Il reçoit les requêtes web, sert les fichiers publics et transmet les requêtes PHP à PHP-FPM.

### App

Le service `app` contient l’application Laravel et PHP-FPM.
Il exécute le code applicatif et dialogue avec PostgreSQL.

### PostgreSQL

Le service `db` fournit la persistance des données.
Il remplace l’ancien fonctionnement local basé sur SQLite.

## Réseau

Les services communiquent via le réseau Docker Compose.
Les noms de services (`app`, `db`) servent de points de communication internes.

## Volumes et persistance

La base PostgreSQL doit reposer sur un volume Docker pour conserver les données entre redémarrages.
Les fichiers de l’application sont montés ou copiés dans le conteneur `app` selon le choix d’implémentation retenu.

## Choix techniques

### Séparation Nginx / PHP-FPM

Le serveur web et l’exécution PHP sont séparés pour refléter une architecture web classique et maintenable.

### PostgreSQL

Le projet migre de SQLite vers PostgreSQL afin de se rapprocher d’un usage plus réaliste côté production et exploitation.

### Docker Compose

Docker Compose permet de décrire la stack, de la relancer facilement et de garder un environnement reproductible sur la VM.

## État de la phase

Phase 3 terminée :

- stack conteneurisée opérationnelle
- communication entre services validée
- application accessible
- base initialisée
- seeders exécutés

## Extension prévue

Les composants suivants sont prévus pour la suite mais non implémentés dans cette phase :

- Prometheus
- Grafana
- node_exporter
