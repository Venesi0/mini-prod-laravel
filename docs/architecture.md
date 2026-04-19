# Architecture

## Objectif

Mettre à disposition une application Laravel dans un mini environnement de production personnel, sur Ubuntu Server, avec une architecture Docker Compose simple, lisible et documentée.

## Composants cibles

- Nginx : point d’entrée HTTP de l’application, certificat TLS monté dans le conteneur Nginx
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

## Stabilisation d'exploitation

À l'issue de la phase 4, l'architecture applicative de base `nginx / app / db` est considérée comme stabilisée pour l'exploitation courante :

- démarrage, arrêt et redémarrage documentés
- contrôles de santé formalisés
- scripts utilitaires d'exploitation ajoutés
- correction structurelle du runtime Laravel intégrée au démarrage du conteneur `app`

La suite du projet ne vise pas à remettre en cause cette base, mais à l'enrichir progressivement selon une logique plus proche d'un environnement DevOps réel :

1. fiabiliser la chaîne de livraison,
2. rendre l'exploitation observable,
3. préparer un déploiement contrôlé.

## Extensions prévues après la phase 4

### Phase 5 - Chaîne CI/CD

Ajout d'une couche d'intégration continue autour du dépôt GitHub :

- exécution automatique de contrôles sur push / pull request
- vérification de la qualité minimale avant intégration
- tests automatisés Laravel
- validation de la configuration et des scripts critiques

Cette phase complète l'architecture existante sans modifier les composants métier `nginx`, `app` et `db`.
Elle ajoute une brique de contrôle autour du cycle de changement.

### Phase 6 - Observabilité

Ajout des composants suivants :

- Prometheus : collecte des métriques
- Grafana : visualisation
- node_exporter : métriques système de la VM

Cette extension ajoute une couche d'observation de l'infrastructure et des services déjà en place.
Elle sert l'exploitation, le diagnostic et la démonstration de maturité technique.

### Phase 7 - Déploiement contrôlé et exploitation avancée

Ajout d'une logique de livraison et d'exploitation plus complète :

- déclenchement contrôlé d'une mise à jour
- standardisation du workflow de déploiement sur la VM
- possibilité de rollback simple
- enrichissement des procédures post-déploiement et incident

Cette dernière phase vise à faire passer le projet d'une stack stable et observable à un mini environnement personnel réellement démontrable en contexte entretien / CV / GitHub.
