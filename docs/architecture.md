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
