# Roadmap

## Phase 1 - Hôte Ubuntu / Docker

Statut : terminé

- [x] VM Ubuntu Server installée
- [x] Docker installé
- [x] Test `docker run hello-world`

## Phase 2 - Structure du projet

Statut : terminé

- [x] Création du repo principal
- [x] Import de l'application Laravel
- [x] Création du dépôt GitHub
- [x] Push initial
- [x] Clone sur Ubuntu Server

## Phase 3 - Conteneurisation applicative

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

**Livraison** : 15 avril 2026

- [x] Runbook d'exploitation complet
- [x] Procédures lancement/arrêt/redémarrage documentées
- [x] Vérifications de santé (conteneurs/HTTP/PostgreSQL)
- [x] Diagnostics pannes courantes (502/500/DB)
- [x] Nettoyage Git : suppression suivi runtime Laravel
- [x] Structure .gitignore standard Laravel
- [x] Scripts utilitaires d'exploitation (+x)
- [x] Ownership projet corrigé sur VM

## Phase 5 - Monitoring

- [ ] Prometheus
- [ ] Grafana
- [ ] node_exporter

## Phase 6 - Documentation finale

- [ ] README enrichi
- [ ] Schéma d'architecture visuel
- [ ] Portfolio-ready
