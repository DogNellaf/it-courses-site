# Courses Programm

> [🇬🇧 English](README.md) | 🇷🇺 Русский

Laravel-приложение для управления курсами программирования: просмотр каталога, подача заявок на курсы, работа в личном кабинете.

## Возможности

- Каталог курсов с фильтрацией по категориям
- Страницы с подробным описанием курса
- Форма подачи заявки на курс
- Личный кабинет со списком поданных заявок
- Добавление новых курсов для авторизованных пользователей
- Удаление заявок
- Аутентификация на основе сессий через Laravel UI
- Адаптивный интерфейс на Bootstrap 5

## Стек технологий

| Слой | Технология |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Frontend | Bootstrap 5 |
| База данных | MySQL 8 |
| Аутентификация | Laravel UI (сессии) |
| Тесты | PHPUnit 10 |

## Требования

- PHP 8.1+
- Composer
- MySQL 8 (или совместимая БД)

## Установка

```bash
# Клонирование репозитория
git clone <repo-url>
cd courses-programm

# Установка зависимостей
composer install

# Настройка окружения
cp .env.example .env
php artisan key:generate
```

Отредактируйте `.env`, указав параметры подключения к БД (см. раздел [Переменные окружения](#переменные-окружения) ниже), затем выполните миграции и засейте начальные данные:

```bash
php artisan migrate
php artisan db:seed
```

Запустите сервер для разработки:

```bash
php artisan serve
```

Приложение будет доступно по адресу `http://127.0.0.1:8000`.

## Переменные окружения

| Переменная | Описание | Значение по умолчанию |
|---|---|---|
| `DB_CONNECTION` | Драйвер базы данных | `mysql` |
| `DB_HOST` | Хост базы данных | `127.0.0.1` |
| `DB_PORT` | Порт базы данных | `3306` |
| `DB_DATABASE` | Имя базы данных | `courses` |
| `DB_USERNAME` | Имя пользователя БД | `root` |
| `DB_PASSWORD` | Пароль БД | _(пусто)_ |

## Структура базы данных

```
categories
  id, title, timestamps

courses
  id, title, duration (int, часы), cost (decimal 10,2),
  description, image, category_id → categories, timestamps

applications
  id, full_name, email, course_id → courses,
  application_date (timestamp), status (pending|approved|rejected), timestamps

users
  id, name, email, password, remember_token,
  email_verified_at, timestamps
```

**Связи моделей**

- `Category` → `hasMany` Course
- `Course` → `belongsTo` Category, `hasMany` Application
- `Application` → `belongsTo` Course
- `User` → `hasMany` Application

## Маршруты

| Метод | URI | Имя | Доступ | Описание |
|---|---|---|---|---|
| GET | `/` | `index` | Все | Каталог курсов с фильтром по категории |
| GET | `/courses/{course}` | `detail` | Все | Страница курса |
| POST | `/applications` | `application.store` | Все | Отправить заявку на курс |
| GET | `/home` | `home.index` | Auth | Мои заявки |
| GET | `/home/courses/create` | `home.course.create` | Auth | Форма добавления курса |
| POST | `/home/courses` | `home.course.store` | Auth | Сохранить курс |
| DELETE | `/home/applications/{application}` | `home.application.destroy` | Auth | Удалить заявку |

## Тестирование

Тесты используют SQLite in-memory — отдельная БД не нужна.

```bash
php artisan test
```

## Структура проекта

```
courses-programm/
├── app/
│   ├── Models/            # Category, Course, Application, User
│   └── Http/
│       └── Controllers/   # Контроллеры Course, Application и Home
├── database/
│   ├── migrations/        # таблицы categories, courses, applications, users
│   └── seeders/           # Сидеры начальных данных
├── resources/
│   └── views/             # Blade-шаблоны (UI на Bootstrap 5)
├── routes/
│   └── web.php             # Маршруты приложения
├── tests/                  # Набор тестов PHPUnit (SQLite in-memory)
└── .env.example
```

## Лицензия

Лицензия для этого проекта пока не указана.
