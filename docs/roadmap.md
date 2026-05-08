# Roadmap

## ✅ Phase 1 - Hôte Ubuntu / Docker

Statut : terminé

- [x] VM Ubuntu Server installée
- [x] Docker installé
- [x] Test `docker run hello-world`

## ✅ Phase 2 - Structure du projet

Statut : terminé

- [x] Création du repo principal
- [x] Import de l'application Laravel
- [x] Création du dépôt GitHub
- [x] Push initial
- [x] Clone sur Ubuntu Server

## ✅ Phase 3 - Conteneurisation applicative

Statut : terminé

- [x] Définition de l'architecture cible
- [x] Création du conteneur Laravel/PHP-FPM
- [x] Configuration Nginx
- [x] Création de la stack Docker Compose
- [x] Adaptation des variables d'environnement
- [x] Lancement de la stack
- [x] Migration vers PostgreSQL
- [x] Exécution des seeders

## ✅ Phase 4 - Stabilisation exploitation et runbook (terminée)

- [x] Runbook d'exploitation structuré et consolidé
- [x] Procédures de lancement / arrêt / redémarrage documentées
- [x] Vérifications de santé Docker / HTTP / PostgreSQL documentées
- [x] Logs utiles et diagnostics de premier niveau formalisés
- [x] Scripts d'exploitation ajoutés : `up.sh`, `down.sh`, `healthcheck.sh`
- [x] Script de remédiation ajouté : `fix-laravel-runtime.sh`
- [x] Script de maintenance locale ajouté : `fix-git-permissions.sh`
- [x] Incident HTTP 500 analysé et documenté
- [x] Correction structurelle du runtime Laravel intégrée via entrypoint
- [x] Préparation de la phase 5 monitoring sans implémentation immédiate
- [ ] Sécurité HTTPS (certificat TLS auto-signé ici)

## 🚧 Phase 5 - CI/CD et qualité de livraison

Statut : en cours

Objectif :
faire évoluer le projet d'une simple stack exploitable vers une chaîne d'intégration et de livraison continue plus crédible en contexte DevOps, en s'alignant sur une structure de pipeline de type entreprise.

### 5.1 - CI socle GitHub Actions

- [x] Création du workflow CI versionné dans `.github/workflows/ci.yml`
- [x] Déclenchement automatique sur `push`
- [x] Déclenchement automatique sur `pull_request`
- [x] Validation des fichiers Composer
- [x] Préparation du runtime Laravel minimal pour la CI
- [x] Vérification de syntaxe PHP
- [x] Validation du fichier Docker Compose
- [x] Statut CI lisible dans GitHub Actions

### 5.2 - Build Docker dans le pipeline

- [x] Ajout d'un job dédié au build Docker
- [x] Construction de l'image applicative dans GitHub Actions
- [x] Vérification que le build passe sans erreur
- [x] Génération d'un tag basé sur le commit SHA
- [x] Mise en place du cache de build GitHub Actions

### 5.3 - Tests automatisés simples

- [ ] Ajout d'un test Laravel minimal exécutable en CI
- [ ] Préparation d'un environnement de test simple
- [ ] Exécution automatique des tests dans le pipeline
- [ ] Échec du pipeline si le test échoue

### 5.4 - Scan sécurité des images

- [ ] Intégration de Trivy dans le workflow
- [ ] Scan de l'image Docker construite par le pipeline
- [ ] Détection des vulnérabilités critiques
- [ ] Échec du pipeline si une vulnérabilité critique est détectée

### 5.5 - Livraison / déploiement automatique (bonus)

- [ ] Déclenchement d'une livraison automatique sur merge vers `main`
- [ ] Mise à jour contrôlée de la VM Ubuntu via SSH
- [ ] Relance de la stack Docker Compose côté cible
- [ ] Vérification de santé post-déploiement

### 5.6 - Documentation de la phase

- [ ] Mise à jour du README avec le fonctionnement CI/CD
- [ ] Mise à jour du runbook avec lecture et diagnostic des échecs CI
- [ ] Mise à jour de l'architecture si nécessaire
- [ ] Consolidation finale de la roadmap de phase 5

# Phase 6 - Observabilité et monitoring

Statut : à faire

Objectif :
ajouter une couche de supervision simple et lisible pour rendre l'exploitation observable.

Contenu prévu :

- Prometheus
- Grafana
- node_exporter
- métriques système VM
- métriques HTTP et PostgreSQL
- tableaux de bord de base

Livrables attendus :

- services de monitoring intégrés au docker-compose
- accès Grafana fonctionnel
- cibles Prometheus valides
- dashboards de base CPU / RAM / HTTP / DB
- runbook enrichi avec contrôle du monitoring

---

# Phase 7 - Déploiement contrôlé et exploitation avancée

Statut : à faire

Objectif :
montrer une logique de production plus complète en ajoutant la livraison contrôlée, la remédiation et la présentation finale du projet.

Contenu prévu :

- déploiement contrôlé depuis GitHub vers la VM
- procédure de mise à jour applicative standardisée
- rollback simple
- alertes de premier niveau
- enrichissement final README / architecture / runbook
- préparation à la démonstration en entretien

Livrables attendus :

- procédure de déploiement documentée
- procédure de retour arrière documentée
- exploitation post-déploiement clarifiée
- documentation finale portfolio-ready
