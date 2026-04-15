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
- premiers diagnostics en cas d'incident.

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
- `infra/php/` : image et configuration PHP
- `infra/postgres/` : éléments liés à PostgreSQL
- `docs/` : documentation projet
- `scripts/` : scripts utilitaires d'exploitation

### Fichier Compose de référence

Toutes les commandes d'exploitation utilisent le fichier :
`infra/compose/docker-compose.yml`

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

- ne pas utiliser cette commande sans raison si l'objectif est seulement de stopper temporairement l'environnement ;
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

### État global des conteneurs

```bash
docker compose -f infra/compose/docker-compose.yml ps
```

À vérifier :

- tous les services attendus sont présents ;
- statut `Up` ;
- pas de redémarrages répétés.

### Vérification des logs récents

```bash
docker compose -f infra/compose/docker-compose.yml logs --tail=50
```

### Vérification de l'accès HTTP via Nginx

```bash
curl -I http://localhost

curl -I http://IP_DE_LA_VM
```

```bash
curl -s -o /dev/null -w "%{http_code}\n" http://localhost
curl -I http://localhost
```

Interprétation simple :

- `200` : frontal HTTP fonctionnel ;
- `502` : suspicion sur l'application PHP-FPM ou le lien Nginx -> app ;
- `500` : suspicion d'erreur applicative côté Laravel ;
- absence de réponse : suspicion sur Nginx ou sur l'exposition du service.

À vérifier :

- réponse HTTP cohérente ;
- absence d'erreur 502 ou 500.

### HTTP 200

- le frontal répond correctement ;
- la stack est au moins partiellement opérationnelle.

### HTTP 502

- Nginx répond mais n'arrive pas à joindre correctement l'application ;
- vérifier prioritairement `app` et la configuration Nginx.

### HTTP 500

- l'application répond avec une erreur interne ;
- vérifier prioritairement les logs Laravel / PHP.

### PostgreSQL indisponible

- l'application peut devenir partiellement ou totalement inutilisable ;
- vérifier `db`, puis la configuration de connexion applicative.

### Vérification applicative Laravel

Adapter selon la page disponible :

```bash
curl http://localhost

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
- problème d'accès base de données ;
- erreur interne de l'application.

Commandes utiles :

```bash
docker compose -f infra/compose/docker-compose.yml logs --tail=100 app
docker compose -f infra/compose/docker-compose.yml exec app php artisan about
```

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

## 10. Principes d'exploitation retenus

- garder les commandes simples et explicites ;
- éviter l'automatisation inutile à ce stade ;
- distinguer clairement lancement, arrêt, redémarrage et diagnostic ;
- privilégier les vérifications observables ;
- préparer une exploitation lisible avant d'ajouter du monitoring.

## 11. Suite prévue

La phase suivante portera sur l'observabilité et le monitoring :

- Prometheus ;
- Grafana ;
- node_exporter.

Cette phase ne sera engagée qu'une fois l'exploitation courante stabilisée et documentée.

## 12. Scripts utilitaires

Les scripts dans `scripts/` simplifient les opérations courantes.

### Usage des scripts

```bash
# Lancement (équivalent up -d)
./scripts/up.sh

# Arrêt propre (équivalent stop)
./scripts/down.sh

# Vérifications santé
./scripts/healthcheck.sh
```

### Contenu des scripts

**`up.sh`** : `docker compose -f infra/compose/docker-compose.yml up -d`
**`down.sh`** : `docker compose -f infra/compose/docker-compose.yml stop`
**`healthcheck.sh`** : enchaîne `ps`, `curl -I`, `pg_isready`

### Avantages

- raccourcis mémorisables
- commandes testées et validées
- exécutables (+x) et versionnés Git

**Précaution** : toujours vérifier le contenu avant exécution :

```bash
cat scripts/up.sh
```

## Annexe - Commandes rapides

| Action   | Commande longue            | Script                     |
| -------- | -------------------------- | -------------------------- |
| Démarrer | `docker compose ... up -d` | `./scripts/up.sh`          |
| Arrêter  | `docker compose ... stop`  | `./scripts/down.sh`        |
| Santé    | `docker compose ps + curl` | `./scripts/healthcheck.sh` |
| Logs     | `docker compose logs -f`   | `docker compose logs -f`   |

**Fin du runbook**
