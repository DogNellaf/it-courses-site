# Courses Programm

> 🇬🇧 English | [🇷🇺 Русский](README.ru.md)

A Laravel application for managing programming courses: browse the catalog, submit course applications, and manage everything from a personal dashboard.

## Features

- Course catalog with category filtering
- Detailed course pages
- Course application (enrollment) form
- Personal dashboard with your submitted applications
- Course creation for authenticated users
- Application deletion
- Session-based authentication via Laravel UI
- Responsive UI built with Bootstrap 5

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Frontend | Bootstrap 5 |
| Database | MySQL 8 |
| Authentication | Laravel UI (session-based) |
| Testing | PHPUnit 10 |

## Requirements

- PHP 8.1+
- Composer
- MySQL 8 (or a compatible database)

## Installation

```bash
# Clone the repository
git clone <repo-url>
cd courses-programm

# Install dependencies
composer install

# Set up the environment file
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials (see [Environment Variables](#environment-variables) below), then run the migrations and seed the initial data:

```bash
php artisan migrate
php artisan db:seed
```

Start the development server:

```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

## Environment Variables

| Variable | Description | Default |
|---|---|---|
| `DB_CONNECTION` | Database driver | `mysql` |
| `DB_HOST` | Database host | `127.0.0.1` |
| `DB_PORT` | Database port | `3306` |
| `DB_DATABASE` | Database name | `courses` |
| `DB_USERNAME` | Database username | `root` |
| `DB_PASSWORD` | Database password | _(empty)_ |

## Database Schema

```
categories
  id, title, timestamps

courses
  id, title, duration (int, hours), cost (decimal 10,2),
  description, image, category_id → categories, timestamps

applications
  id, full_name, email, course_id → courses,
  application_date (timestamp), status (pending|approved|rejected), timestamps

users
  id, name, email, password, remember_token,
  email_verified_at, timestamps
```

**Model relationships**

- `Category` → `hasMany` Course
- `Course` → `belongsTo` Category, `hasMany` Application
- `Application` → `belongsTo` Course
- `User` → `hasMany` Application

## Routes

| Method | URI | Name | Access | Description |
|---|---|---|---|---|
| GET | `/` | `index` | Public | Course catalog with category filter |
| GET | `/courses/{course}` | `detail` | Public | Course detail page |
| POST | `/applications` | `application.store` | Public | Submit a course application |
| GET | `/home` | `home.index` | Auth | My applications |
| GET | `/home/courses/create` | `home.course.create` | Auth | Add course form |
| POST | `/home/courses` | `home.course.store` | Auth | Save course |
| DELETE | `/home/applications/{application}` | `home.application.destroy` | Auth | Delete application |

## Running Tests

Tests run against an in-memory SQLite database — no separate database setup needed.

```bash
php artisan test
```

## Project Structure

```
courses-programm/
├── app/
│   ├── Models/            # Category, Course, Application, User
│   └── Http/
│       └── Controllers/   # Course, Application and Home controllers
├── database/
│   ├── migrations/        # categories, courses, applications, users tables
│   └── seeders/           # Initial data seeders
├── resources/
│   └── views/             # Blade templates (Bootstrap 5 UI)
├── routes/
│   └── web.php            # Application routes
├── tests/                 # PHPUnit test suite (SQLite in-memory)
└── .env.example
```

## License

[MIT](LICENSE)
