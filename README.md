# Courses Programm

Laravel-приложение для управления курсами программирования: просмотр каталога, запись на курсы, личный кабинет.

---

## Стек технологий

| Слой | Технология |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Frontend | Bootstrap 5 |
| База данных | MySQL 8 |
| Аутентификация | Laravel UI (сессии) |
| Тесты | PHPUnit 10 |

---

## Установка и запуск

### 1. Клонирование и зависимости

```bash
git clone <repo-url>
cd courses-programm
composer install
```

### 2. Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```

Отредактируйте `.env` — укажите параметры БД:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=courses
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Миграции и начальные данные

```bash
php artisan migrate
php artisan db:seed
```

### 4. Запуск

```bash
php artisan serve
```

Приложение доступно по адресу: `http://127.0.0.1:8000`

---

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

### Связи моделей

- `Category` → `hasMany` Course
- `Course` → `belongsTo` Category, `hasMany` Application
- `Application` → `belongsTo` Course
- `User` → `hasMany` Application

---

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

---

## Тестирование

Тесты используют SQLite in-memory — отдельная БД не нужна.

```bash
php artisan test
```
