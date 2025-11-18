# Local Development Setup for "Oetzi"

This project uses Docker (PHP-FPM, MariaDB, Caddy) and relies on a prepared example database for a working development environment.

## Prerequisites

- Git
- Docker / Docker Desktop
- Docker Compose (if not integrated)

## 1. Clone the repository

```bash
git clone git@github.com:Flotorious/oetzi.git
cd oetzi
```

## 2. Prepare the `.env` file for local development

```bash
cp .env.dev .env
```

Ensure the following values are present:

```env
APP_ENV=dev
APP_DEBUG=1

DB_NAME=energyviz
DB_USER=dbproduser
DB_PASS=dbprodpw

DATABASE_URL="mysql://dbproduser:dbprodpw@database:3306/energyviz?serverVersion=10.11&charset=utf8mb4"

MESSENGER_TRANSPORT_DSN=doctrine://default
```

## 3. Start Docker containers

```bash
docker compose up -d
```

## 4. Install PHP dependencies

```bash
docker compose exec app composer install
```

## 5. Import the development database

```bash
docker compose exec -T database mysql   -udbproduser   -pdbprodpw   energyviz < docs/dev-dump.sql
```

## 6. Build frontend assets

```bash
docker compose exec app npm install
docker compose exec app npm run dev
```

## 7. Access the application

Add to `/etc/hosts`:

```
127.0.0.1   energyviz.localhost
```

Open:

```
http://energyviz.localhost
```
