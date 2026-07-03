# Dejavu Memory Benchmark — таблица лидеров

Небольшое [Yii2](https://www.yiiframework.com/)-приложение, которое собирает
результаты `dejavu-memory-benchmark` от участников и показывает публичную
таблицу лидеров, отсортированную по суммарному баллу.

- **Публичная страница `/`** — таблица лидеров (место, участник / модель, версия, общий балл, дата), сортировка по общему баллу по убыванию.
- **API `POST /api/submit`** — принимает результат бенчмарка в формате JSON и сохраняет его.

Стек: Yii2 Basic · PHP 8.4 · MySQL 8.0 · Bootstrap 5.

[English version — README.md](README.md)

## Требования

- PHP 8.1+ (разрабатывалось на 8.4)
- MySQL 8.0 (или совместимый сервер)
- [Composer](https://getcomposer.org/)

## Установка

```bash
# 1. Установить зависимости
composer install

# 2. Создать базу данных
mysql -u root -p -e "CREATE DATABASE dejavu_leaderboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Настроить доступ к БД
cp config/db-local.php.example config/db-local.php
# отредактируйте config/db-local.php — укажите логин / пароль

# 4. Применить миграции
php yii migrate
```

### Настройка базы данных

Параметры подключения берутся в таком порядке:

1. Переменные окружения `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
2. `config/db-local.php` (в `.gitignore`) — переопределяет значения выше.
3. Значения по умолчанию (`config/db.php`): хост `mysql`, порт `3306`, база `dejavu_leaderboard`.

### Локальный запуск

```bash
php yii serve
# откройте http://localhost:8080
```

Для продакшена направьте веб-сервер (nginx + PHP-FPM) на каталог `web/`.
Пример виртуального хоста nginx — в [`docker/nginx/leaderboard.conf`](docker/nginx/leaderboard.conf).

## API

### `POST /api/submit`

Отправьте JSON-тело с заголовком `Content-Type: application/json`.

| Поле             | Тип             | Обязательно | Описание                                            |
|------------------|-----------------|-------------|-----------------------------------------------------|
| `submitter_name` | string (≤128)   | да          | Кто отправил прогон (человек / команда).            |
| `model_name`     | string (≤128)   | да          | Название оцениваемой модели.                        |
| `model_version`  | string (≤64)    | нет         | Версия / тег модели.                                |
| `score_total`    | number          | да          | Суммарный балл (больше — лучше).                     |
| `score_per_case` | object          | нет         | Баллы по кейсам, карта `кейс → балл`.               |
| `notes`          | string          | нет         | Произвольные заметки о прогоне.                     |

`submitted_at` проставляется сервером автоматически.

#### Пример запроса

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

#### Успех — `201 Created`

```json
{
  "success": true,
  "id": 1,
  "submitted_at": "2026-07-03 18:35:28"
}
```

#### Ошибка валидации — `422 Unprocessable Entity`

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

Некорректное или пустое тело запроса возвращает `400 Bad Request`.

## Схема базы данных

Таблица `submissions`:

| Столбец          | Тип             | Примечания                         |
|------------------|-----------------|------------------------------------|
| `id`             | pk              | автоинкремент                      |
| `submitter_name` | varchar(128)    | not null                           |
| `model_name`     | varchar(128)    | not null                           |
| `model_version`  | varchar(64)     | nullable                           |
| `score_total`    | decimal(10,4)   | not null, индекс                   |
| `score_per_case` | json            | nullable                           |
| `submitted_at`   | timestamp       | по умолчанию `CURRENT_TIMESTAMP`   |
| `notes`          | text            | nullable                           |

## Лицензия

BSD-3-Clause (унаследовано от шаблона Yii2 Basic Application Template).
