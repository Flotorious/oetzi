# Production Deployment Guide (oetzistrom.cosylab.at)

This document describes how to deploy the **Oetzi** application to the production server.

> Important  
> All concrete passwords, tokens, and secrets must *only* be stored on the server (e.g. in `.env` or as environment variables), never in the Git repository.

---

## 1. Server overview

- Host: `oetzistrom.cosylab.at`
- Application directory: `/opt/oetzi`
- Stack:
  - Docker + Docker Compose
  - Caddy (reverse proxy / web server)
  - PHP-FPM (Symfony app)
  - MariaDB (application database)
  - Mailpit (for outgoing mail in non-prod-like setups)

---

## 2. Environment configuration (on the server only)

On the production server, the main configuration lives in `/opt/oetzi/.env`.

A template (do *not* copy exact values into Git!) looks like this:

```env
APP_ENV=prod
APP_DEBUG=0

DB_NAME=energyviz
DB_USER=<PROD_DB_USER>
DB_PASS=<PROD_DB_PASSWORD>
DB_ROOT_PASS=<PROD_DB_ROOT_PASSWORD>
MARIADB_VERSION=10.11

DATABASE_URL="mysql://<PROD_DB_USER>:<PROD_DB_PASSWORD>@database:3306/energyviz?serverVersion=10.11&charset=utf8mb4"

# Messenger transport for production
MESSENGER_TRANSPORT_DSN=doctrine://default
```

Rules:

- Replace all `<...>` placeholders with real values **on the server**.
- Never commit the real values into the repository.
- If credentials ever appear in Git by mistake, rotate them (change passwords / secrets) on the server.

---

## 3. First-time setup (already done once, documented for reference)

These steps are usually done only once when provisioning a new server.

### 3.1 Clone the repository

On the server:

```bash
cd /opt
git clone https://github.com/Flotorious/oetzi.git
cd oetzi
```

### 3.2 Create `.env` and set production credentials

Create `/opt/oetzi/.env` and fill in the production values as shown in section 2.  
Do **not** use the development credentials from `.env.example`.

### 3.3 Start the Docker stack

```bash
docker compose up -d
```

Check that all containers are running:

```bash
docker compose ps
```

You should see:

- `database`
- `app`
- `caddy`
- `mailer`
- `messenger-consumer` (may restart until queues/config are correct)

### 3.4 Initialize the database

You have two options:

1. Use a prepared SQL dump (recommended if you want to mirror an existing, working instance):

   ```bash
   docker compose exec -T database mysql      -u<PROD_DB_USER>      -p<PROD_DB_PASSWORD>      <PROD_DB_NAME> < /path/to/prod-dump.sql
   ```

2. Or run Doctrine migrations (only if the migration set is known to be in sync):

   ```bash
   docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction
   ```

In the current setup, a working schema is usually restored from a dump rather than migrations.

### 3.5 Build frontend assets

```bash
docker compose exec app npm install
docker compose exec app npm run build
```

### 3.6 Fix cache permissions (if needed)

If Symfony logs errors like `mkdir(): Permission denied` under `var/cache`, run:

```bash
docker compose exec -u root app chown -R www-data:www-data var
```

---

## 4. Regular deployment (updating the app)

For each new release / commit you want to deploy to production:

### 4.1 SSH to the server

```bash
ssh <your-user>@oetzistrom.cosylab.at
cd /opt/oetzi
```

### 4.2 Run the deployment script

If you use a `deploy.sh` file in the project root with contents similar to:

```bash
#!/usr/bin/env bash
set -e

APP_DIR="/opt/oetzi"

cd "$APP_DIR"

echo ">>> Pulling latest code"
git pull

echo ">>> Building Docker images"
docker compose build app messenger-consumer

echo ">>> Starting containers"
docker compose up -d

echo ">>> Installing PHP dependencies"
docker compose exec app composer install --no-dev --optimize-autoloader

echo ">>> Building frontend assets"
docker compose exec app npm install
docker compose exec app npm run build

echo ">>> Clearing Symfony cache"
docker compose exec app php bin/console cache:clear

echo ">>> Deployment finished. Current status:"
docker compose ps
```

Then you can simply run:

```bash
chmod +x deploy.sh    # only once
./deploy.sh
```

This will:

1. Pull the latest code from Git
2. Build updated Docker images
3. Start/refresh the containers
4. Install/update PHP dependencies (without dev packages)
5. Install Node dependencies and build frontend assets
6. Clear the Symfony cache

---

## 5. Rotating database credentials (best practice)

If credentials were ever stored in Git or otherwise exposed, treat them as compromised and rotate them.

Example: rotate the application DB user inside the `database` container:

```bash
cd /opt/oetzi
docker compose exec database mysql -uroot -p<PROD_DB_ROOT_PASSWORD>
```

In the MariaDB shell:

```sql
ALTER USER '<PROD_DB_USER>'@'%' IDENTIFIED BY '<NEW_STRONG_PASSWORD>';
FLUSH PRIVILEGES;
EXIT;
```

Then update `/opt/oetzi/.env`:

```env
DB_PASS=<NEW_STRONG_PASSWORD>
DATABASE_URL="mysql://<PROD_DB_USER>:<NEW_STRONG_PASSWORD>@database:3306/energyviz?serverVersion=10.11&charset=utf8mb4"
```

Finally, restart the stack:

```bash
cd /opt/oetzi
docker compose down
docker compose up -d
```

---

## 6. Troubleshooting

### Check container status

```bash
docker compose ps
```

### View logs

```bash
docker compose logs app --tail=50
docker compose logs database --tail=50
docker compose logs caddy --tail=50
```

### Common issues

- HTTP 500 + Twig error about `entrypoints.json`  
  → Frontend assets were not built. Run:
  ```bash
  docker compose exec app npm install
  docker compose exec app npm run build
  ```

- mkdir(): Permission denied under var/cache  
  → Fix permissions:
  ```bash
  docker compose exec -u root app chown -R www-data:www-data var
  ```

- Database connection "Access denied for user ..."  
  → Check:
    - DB credentials in `.env`
    - Corresponding MariaDB user/password
    - That `database` container is `healthy` (`docker compose ps`)

---

## 7. Golden rules

- Never commit real passwords, tokens, or secrets to Git.
- Use `.env.example` for development-only default values.
- Keep the real `.env` only on the server (and in secure backups / secret managers).
- If in doubt, rotate credentials instead of trusting that “no one has seen them”.
