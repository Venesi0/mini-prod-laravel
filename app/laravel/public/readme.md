# ProjectA (Laravel) — Gestion clients, projets, tickets

Application Laravel de gestion de clients, projets et tickets, avec 2 espaces :

- **Admin** : pilotage global (clients, projets, tickets, collaborateurs).
- **User (client)** : consultation côté client (projets, tickets, profil, settings).

## Stack

- PHP >= 8.2
- Laravel 12
- Base de données : **SQLite** (par défaut dans `.env.example`)
- Front : Vite + TailwindCSS
- Auth : Laravel Breeze (login/register + reset password)

---

## Démarrage rapide

### 1) Installer les dépendances

```bash
composer install
npm install
```

### 2) Configurer l’environnement

Copie `.env.example` vers `.env`, puis :

```bash
php artisan key:generate
```

### 3) Base de données (SQLite)

Par défaut : `DB_CONNECTION=sqlite`.

Le fichier :

- `database/database.sqlite`

Executer les migrations + données de démo :

```bash
php artisan migrate
php artisan db:seed
```

### 4) Lancer l’app

Backend :

```bash
php artisan serve
```

Frontend (assets) :

```bash
npm run dev
```

---

## Comptes de démonstration (seeding)

Le seeder crée automatiquement :

- **Admin**
    - Email : `admin@projecta.com`
    - Mot de passe : `admin00`
    - Rôle : `admin`
- **Users (clients)**
    - Un compte `user` est créé pour chaque client qui a un email
    - Mot de passe commun : `client00`

Le champ `users.role` pilote l’accès admin via le middleware `isAdmin`.

---

## Modèle de données (Database)

### Table `users`

Migration : table Laravel standard + ajout d’un rôle.

- `id` (PK)
- `name`
- `email` (unique)
- `email_verified_at` (nullable)
- `password`
- `remember_token`
- `role` (default: `user`)
- `created_at`, `updated_at`

### Table `clients`

PK non standard : `id_client`.

- `id_client` (PK, auto-increment)
- `name` (max 15)
- `email` (nullable)
- `password_hash` (nullable)
- `status` (enum: `Standard|Premium`)
- `date` (date)
- `projectsNb` (int, défaut 0)
- `openedTickets` (int, défaut 0)
- `totalHours` (int, défaut 0)
- `avatarColor` (hex, défaut `#919090`)

### Table `projects`

- `id` (PK, auto-increment)
- `name` (max 15)
- `client_id` (index) — référence logique vers `clients.id_client`
- `status` (string 15)
- `contractHours` (int)
- `usedHours` (int, défaut 0)
- `openTickets` (int)
- `collaborator_ids` (JSON nullable) — liste d’ids collaborateurs

### Table `tickets`

⚠️ Attention : plusieurs migrations “rebuild” drop/recréent la table. Le schéma final est celui du dernier rebuild.
PK non standard : `id_ticket`.

- `id_ticket` (PK, auto-increment)
- `code` (string 20)
- `title` (string 100)
- `description` (text nullable)
- `client_id` (index) — référence logique vers `clients.id_client`
- `project_id` (index) — référence logique vers `projects.id`
- `status` (string 20 nullable)
- `priority` (string 20 nullable)
- `type` (string 20 nullable) — ex: Included/Billable (seed)
- `time_est` (string 10 nullable) — ex: `"6h"`
- `time_real` (string 10 nullable) — ex: `"4h"`
- `created_at` (date nullable)

### Table `collaborators`

PK non standard : `id_collab`.

- `id_collab` (PK, auto-increment)
- `full_name`
- `email` (unique)
- `position`
- `work_status` (enum: `Available|On project|Vacation / away`)
- `projects_count` (int)
- `tickets_count` (int)
- `rating` (decimal(2,1) nullable)
- `avatar_color` (hex)
- `created_at`, `updated_at`

### Tables “infra” (Laravel)

Selon les migrations de base :

- `sessions` (session driver database)
- `password_reset_tokens` (reset password)
- `cache`, `cache_locks` (cache store database)
- `jobs`, `job_batches`, `failed_jobs` (queue connection database)

---

## Relations (logiques)

- Un **client** possède plusieurs **projects** (`projects.client_id -> clients.id_client`)
- Un **project** possède plusieurs **tickets** (`tickets.project_id -> projects.id`)
- Un **ticket** est rattaché à un **client** et à un **project**
- Un **project** peut avoir des collaborateurs via `projects.collaborator_ids` (JSON d’ids `collaborators.id_collab`)

---

## Compteurs & métriques (important)

Les champs suivants sont **des métriques “dénormalisées”** recalculées :

- `projects.usedHours` et `projects.openTickets`
- `clients.projectsNb`, `clients.openedTickets`, `clients.totalHours`

Le recalcul est fait via une classe utilitaire (recalcule à partir des tickets/projets).

---

## Routes principales (aperçu)

- Espace user (auth) : `/user/projects`, `/user/project/{project}`, `/user/tickets`, `/user/settings`, etc.
- Espace admin (auth + isAdmin) : `/admin/dashboard`, `/admin/clients`, `/admin/projects`, `/admin/tickets`, `/collaborators`, etc.

### API (exemple)

- `POST /api/collaborators` : création d’un collaborateur (JSON), utilisée pour de l’ajout dynamique côté front.

---

## Commandes utiles

- Migrations + seed (reset complet) :

```bash
php artisan migrate:fresh --seed
```

## Notes / conventions

- Plusieurs tables utilisent des **PK non standard** (`id_client`, `id_collab`, `id_ticket`) : côté Eloquent, les modèles configurent `protected $primaryKey` en conséquence.
- La plupart des tables “métier” (clients/projects/tickets) n’utilisent pas `created_at/updated_at` (sauf collaborators).

---
