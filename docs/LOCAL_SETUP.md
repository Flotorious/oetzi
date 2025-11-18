# Local Development Setup for "Oetzi"

This guide describes how to run the project locally **for development only**.
All credentials in this document and in `.env.example` are **dev-only** and must never be used in production.

---

## 1. Prerequisites

- Git
- Docker / Docker Desktop
- Docker Compose (if not integrated with Docker)

You do **not** need a local PHP, Composer, Node, or MySQL installation – everything runs inside Docker containers.

---

## 2. Clone the repository

```bash
git clone git@github.com:Flotorious/oetzi.git
cd oetzi
```

---

## 3. Create your local `.env` from the example

An example environment file for **development** is provided as `.env.example`.

Create your local `.env`:

```bash
cp .env.example .env
```

The relevant part looks like this:

```env
APP_ENV=dev
APP_DEBUG=1

DB_NAME=energyviz
DB_USER=oetzi_dev
DB_PASS=oetzi_dev_pass
DB_ROOT_PASS=oetzi_dev_root

MARIADB_VERSION=10.11
DATABASE_URL="mysql://oetzi_dev:oetzi_dev_pass@database:3306/energyviz?serverVersion=10.11&charset=utf8mb4"

MESSENGER_TRANSPORT_DSN=doctrine://default
```

> These values are meant **only for local Docker containers**.  
> They are not real production credentials.

You can change the usernames/passwords if you like – just make sure `DB_USER` / `DB_PASS` match both the `DATABASE_URL` and the values used by the `database` service in `docker-compose.yml` (via environment variables).

---

## 4. Start Docker containers

```bash
docker compose up -d
```

Check that everything is running:

```bash
docker compose ps
```

You should see at least:

- `database`
- `app`
- `caddy`

in state `Up`.

---

## 5. Install PHP dependencies

```bash
docker compose exec app composer install
```

---

## 6. Import the development database

A SQL dump with a working schema and sample data should live at:

```text
docs/dev-dump.sql
```

Import it into the local Docker database:

```bash
docker compose exec -T database mysql   -uoetzi_dev   -poetzi_dev_pass   energyviz < docs/dev-dump.sql
```

Verify:

```bash
docker compose exec database mysql -uoetzi_dev -poetzi_dev_pass energyviz -e "SHOW TABLES;"
```

You should see tables like `device`, `user`, `user_energy_snapshot`, etc.

If `docs/dev-dump.sql` is missing, ask a teammate or generate one from a working local instance using:

```bash
docker compose exec database mysqldump   -uoetzi_dev -poetzi_dev_pass   energyviz > docs/dev-dump.sql
```

---

## 7. Build frontend assets

Install Node dependencies inside the app container:

```bash
docker compose exec app npm install
```

For development:

```bash
docker compose exec app npm run dev
```

or, for a one-time production-style build:

```bash
docker compose exec app npm run build
```

---

## 8. Access the application in the browser

Ensure you have the following entry in your hosts file:

**Linux/macOS** – `/etc/hosts`  
**Windows** – `C:\Windows\System32\drivers\etc\hosts`

Add:

```text
127.0.0.1   energyviz.localhost
```

Then open:

```text
http://energyviz.localhost
```

---

## 9. Useful Docker commands

View logs:

```bash
docker compose logs app --tail=50
docker compose logs database --tail=50
docker compose logs caddy --tail=50
```

Stop containers:

```bash
docker compose down
```

Reset everything including volumes and the database (dangerous – deletes all local DB data):

```bash
docker compose down -v
```
