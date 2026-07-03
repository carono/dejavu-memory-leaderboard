# Dejavu Memory Benchmark — Leaderboard

A small [Yii2](https://www.yiiframework.com/) web application that collects
results of the `dejavu-memory-benchmark` from participants and shows a public
leaderboard ranked by total score.

- **Public page `/`** — leaderboard table (rank, submitter / model, version, total score, date), sorted by total score descending.
- **API `POST /api/submit`** — accepts a benchmark result as JSON and stores it.

Stack: Yii2 Basic · PHP 8.4 · MySQL 8.0 · Bootstrap 5.

[Русская версия — README.ru.md](README.ru.md)

## Requirements

- PHP 8.1+ (developed against 8.4)
- MySQL 8.0 (or a compatible server)
- [Composer](https://getcomposer.org/)

## Installation

```bash
# 1. Install dependencies
composer install

# 2. Create the database
mysql -u root -p -e "CREATE DATABASE dejavu_leaderboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Configure database credentials
cp config/db-local.php.example config/db-local.php
# edit config/db-local.php and set your username / password

# 4. Apply migrations
php yii migrate
```

### Database configuration

Credentials are resolved in this order:

1. Environment variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
2. `config/db-local.php` (gitignored) — overrides the values above.
3. Built-in defaults (`config/db.php`): host `mysql`, port `3306`, database `dejavu_leaderboard`.

### Running locally

```bash
php yii serve
# open http://localhost:8080
```

Point a web server (nginx + PHP-FPM) at the `web/` directory for production.
An example nginx virtual host is provided in [`docker/nginx/leaderboard.conf`](docker/nginx/leaderboard.conf).

## API

### `POST /api/submit`

Send a JSON body with `Content-Type: application/json`.

| Field            | Type            | Required | Description                                              |
|------------------|-----------------|----------|----------------------------------------------------------|
| `submitter_name` | string (≤128)   | yes      | Who submitted the run (person / team).                   |
| `model_name`     | string (≤128)   | yes      | Name of the evaluated model.                             |
| `model_version`  | string (≤64)    | no       | Model version / tag.                                     |
| `score_total`    | number          | yes      | Total benchmark score (higher is better).                |
| `score_per_case` | object          | no       | Per-case scores, a map of `case → score`.                |
| `notes`          | string          | no       | Free-form notes about the run.                           |

`submitted_at` is set automatically by the server.

#### Example request

```bash
curl -X POST https://<your-host>/api/submit \
  -H 'Content-Type: application/json' \
  -d '{
    "submitter_name": "Alice",
    "model_name": "gpt-mini",
    "model_version": "1.2",
    "score_total": 87.5,
    "score_per_case": { "case1": 0.9, "case2": 0.85 },
    "notes": "first run"
  }'
```

#### Success response — `201 Created`

```json
{
  "success": true,
  "id": 1,
  "submitted_at": "2026-07-03 18:35:28"
}
```

#### Validation error — `422 Unprocessable Entity`

```json
{
  "success": false,
  "error": "Validation failed.",
  "errors": {
    "submitter_name": ["Submitter Name cannot be blank."],
    "score_total": ["Score Total cannot be blank."]
  }
}
```

A malformed or empty body returns `400 Bad Request`.

## Database schema

Table `submissions`:

| Column           | Type            | Notes                              |
|------------------|-----------------|------------------------------------|
| `id`             | pk              | auto-increment                     |
| `submitter_name` | varchar(128)    | not null                           |
| `model_name`     | varchar(128)    | not null                           |
| `model_version`  | varchar(64)     | nullable                           |
| `score_total`    | decimal(10,4)   | not null, indexed                  |
| `score_per_case` | json            | nullable                           |
| `submitted_at`   | timestamp       | default `CURRENT_TIMESTAMP`        |
| `notes`          | text            | nullable                           |

## License

BSD-3-Clause (inherited from the Yii2 Basic Application Template).
