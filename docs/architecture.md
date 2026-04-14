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

## Phase 3 - Architecture conteneurisée initiale

Services :

- nginx : exposition HTTP, reverse proxy vers PHP-FPM
- app : conteneur Laravel / PHP-FPM
- db : PostgreSQL

Flux :

- client -> nginx:80
- nginx -> app:9000
- app -> db:5432

Principes :

- séparation des rôles par service
- communication interne via réseau Docker Compose
- persistance des données PostgreSQL via volume Docker
- code applicatif monté ou copié dans le conteneur app selon le besoin
