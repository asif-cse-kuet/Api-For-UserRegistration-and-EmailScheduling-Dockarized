# Laravel User Registration API

A RESTful Laravel API that registers users by email and sends a welcome message via SMTP (Gmail-compatible).

## Features

- Email-only user registration (`POST /api/register`)
- Welcome email dispatched asynchronously via queue worker
- PostgreSQL database with Docker Compose
- Per-email rate limiting (5 requests/minute)
- Health check endpoint (`GET /api/health`)

## Architecture

```
RegisterUserRequest → UserController → WelcomeEmailJob → GmailService → Mail (SMTP)
```

See [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) for a detailed flow diagram.

## Prerequisites

- Docker and Docker Compose
- (For local development without Docker) PHP 8.1+, Composer, PostgreSQL

## Quick Start

1. Clone the repository.
2. Copy the environment file:

   ```bash
   cp .env.example .env
   ```

3. Set `APP_KEY` and mail credentials in `.env`:

   ```env
   APP_KEY=base64:...          # run: php artisan key:generate
   MAIL_USERNAME=your@gmail.com
   MAIL_PASSWORD=your-app-password
   ```

   For Gmail, use an [App Password](https://support.google.com/accounts/answer/185833) with SMTP settings already configured in `.env.example`.

4. Start the stack:

   ```bash
   docker compose up --build
   ```

5. The API is available at `http://localhost:8000`.

## API Endpoints

### Health Check

- **URL:** `GET /api/health`
- **Response (200):**

  ```json
  {
    "status": "ok",
    "database": "connected"
  }
  ```

### Register a User

- **URL:** `POST /api/register`
- **Request body:**

  ```json
  {
    "email": "user@example.com"
  }
  ```

- **Success response (201):**

  ```json
  {
    "message": "User registration successful.",
    "data": {
      "email": "user@example.com"
    }
  }
  ```

- **Validation error (422):**

  ```json
  {
    "message": "Validation failed.",
    "errors": {
      "email": ["This email address is already registered."]
    }
  }
  ```

## Docker Services

| Service | Description |
|---------|-------------|
| `web`   | Laravel app (`php artisan serve` on port 8000) |
| `queue` | Queue worker processing `WelcomeEmailJob` |
| `db`    | PostgreSQL 15 |

Migrations run automatically on container startup via `docker/entrypoint.sh`.

## Queue

The default `QUEUE_CONNECTION` is `database`. The `queue` service must be running for welcome emails to send asynchronously. For synchronous sending during development, set `QUEUE_CONNECTION=sync` in `.env`.

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_KEY` | Laravel encryption key |
| `DB_*` | PostgreSQL connection (defaults target the `db` service) |
| `QUEUE_CONNECTION` | `database` (default) or `sync` |
| `MAIL_*` | SMTP settings for sending welcome emails |

## Running Tests

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan test
```

Tests use SQLite in-memory (`phpunit.xml`).

## License

MIT
