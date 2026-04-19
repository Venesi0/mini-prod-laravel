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

# Phase 5 - CI/CD et qualité de livraison

Statut : à faire

Objectif :
faire évoluer le projet d'une simple stack exploitable vers une chaîne de livraison plus crédible en contexte DevOps.

Contenu prévu :

- GitHub Actions pour exécuter les contrôles automatiquement
- validation de la syntaxe et de la configuration
- tests Laravel automatisés
- quality gate avant merge ou déploiement
- structuration des scripts liés à la CI
- documentation du workflow de livraison

Livrables attendus :

- workflow CI versionné dans le dépôt
- exécution automatique sur push / pull request
- statut lisible dans GitHub
- documentation des contrôles effectués
- runbook mis à jour avec la lecture des échecs CI

---

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
