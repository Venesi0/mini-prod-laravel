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

## Phase 5 - Monitoring

- [ ] Prometheus
- [ ] Grafana
- [ ] node_exporter

## Phase 6 - Documentation finale

- [ ] README enrichi
- [ ] Schéma d'architecture visuel
- [ ] Portfolio-ready
