# Local Development Setup

This guide explains how to get the **Oetzi** application running locally in a clean, reproducible way.

It assumes you have:

- Docker & Docker Compose installed
- Git installed
- Node.js + npm available (inside the container the image already has what it needs)
- Composer available inside the `app` container (already part of the image)

---

## 1. Clone the repository

```bash
git clone https://github.com/Flotorious/oetzi.git
cd oetzi
```

If you use SSH:

```bash
git clone git@github.com:Flotorious/oetzi.git
cd oetzi
```

---

## 2. Create your local `.env`

The repository contains a **safe development template** in `.env.example`.

Create your own local `.env` from it:

```bash
cp .env.example .env
```

Notes:

- `.env` is **not** committed to Git.
- The values in `.env.example` are *dev-only* credentials (not used in production).
- Each developer can adjust their local `.env` if necessary.

---

## 3. Start the Docker stack

```bash
docker compose up -d
```

This starts:

- `database` (MariaDB)
- `app` (Symfony/PHP-FPM)
- `caddy` (web server / reverse proxy)
- `mailer` (Mailpit)
- `messenger-consumer` (background worker)

Check status:

```bash
docker compose ps
```

The `database` service should be `Up` and `healthy`.

---

## 4. Install PHP dependencies (Composer)

Run Composer inside the `app` container:

```bash
docker compose exec app composer install
```

This installs all Symfony / PHP dependencies required for development.

---

## 5. Install Node dependencies

```bash
docker compose exec app npm install
```

---

## 6. Build frontend assets (development)

For local development:

```bash
docker compose exec app npm run dev
```

If you want a production-like build locally:

```bash
docker compose exec app npm run build
```

---

## 7. Import the development database dump

The repository contains a **development database dump** at `docs/dev-dump.sql`.  
It sets up the schema and some example data.

Import it into the dev database using the credentials from `.env.example`:

```bash
docker compose exec -T database mysql   -uoetzi_dev   -poetzi_dev_pass   energyviz < docs/dev-dump.sql
```

Verify that tables exist:

```bash
docker compose exec database mysql   -uoetzi_dev   -poetzi_dev_pass   energyviz -e "SHOW TABLES;"
```

You should see tables like:

- `device`
- `device_usage_log`
- `user`
- `user_energy_snapshot`
- `price_rate_period`
- `messenger_messages`

---

## 8. Clear Symfony cache (optional but helpful)

```bash
docker compose exec app php bin/console cache:clear
```

---

## 9. Open the app in the browser

For the default local setup, open:

```text
http://localhost
```

If you are using a development hostname (e.g. `energyviz.localhost`) configured in Caddy or `/etc/hosts`, use that instead.

---

## 10. Stopping and cleaning up

To stop the containers but **keep** the data:

```bash
docker compose down
```

To stop containers and **remove all data volumes** (fresh start next time):

```bash
docker compose down -v
```

---

## Notes on environment files

- `.env.example`  
  - Tracked in Git  
  - Contains **development-only** credentials and defaults  
  - Safe to share with other developers

- `.env`  
  - **Not** tracked in Git  
  - Created per developer from `.env.example`  
  - Used by Symfony to load environment variables for local dev

- Production configuration  
  - Uses a **separate** `.env` on the server (not in Git)  
  - With real production secrets and passwords  
  - See `DEPLOYMENT.md` for details
