# Courses Programm

Laravel-приложение для управления курсами программирования: просмотр каталога, запись на курсы, личный кабинет.

---

## Стек технологий

| Слой | Технология |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Frontend | Bootstrap 5 |
| База данных | MySQL 8 (для разработки), SQLite (для тестов) |
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

Сидер создаст 8 категорий и 8 тестовых курсов.

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

```bash
php artisan test
```

Тесты используют SQLite in-memory — отдельная БД не нужна.

### Покрытие тестами

| Файл | Что тестируется |
|---|---|
| `Feature/CoursesControllerTest` | index (фильтр, пагинация), detail (404), storeApplication (валидация, запись) |
| `Feature/HomeControllerTest` | Редиректы гостей, CRUD курсов, удаление заявок |
| `Feature/AuthTest` | Регистрация, вход, выход, неверный пароль |
| `Unit/CourseModelTest` | Связи, касты, cascade delete |
| `Unit/ApplicationModelTest` | Связи, касты, состояния фабрики |
| `Unit/CategoryModelTest` | Связи, cascade delete |

---

## Найденные и исправленные ошибки

### Критические

| Файл | Проблема | Исправление |
|---|---|---|
| `HomeController@store` | Синтаксическая ошибка `])`; несуществующие поля `short_description`, `full_description` | Переписан метод |
| `home.blade.php` | Использует неопределённую переменную `$applications` (контроллер передавал `$courses`) | Контроллер теперь передаёт `$applications` |
| `detail.blade.php` | `$course->name` (поля нет), `$course->user->name` (связи нет) | Исправлено на `$course->title` и `$course->category->title` |
| `home.blade.php` | Ссылки на несуществующие маршруты `application.edit`, `application.delete` | Добавлен маршрут `application.destroy` |
| `CoursesController@store_application` | После сохранения вызывал `$this->index()` вместо redirect | → `redirect()->route('index')` |

### Миграции

| Файл | Проблема | Исправление |
|---|---|---|
| `create_categories_table` | `down()` удаляет `caterogies` (опечатка) | → `categories` |
| `create_applications_table` | `down()` удаляет `application` | → `applications` |
| `create_courses_table` | `down()` удаляет `course` | → `courses` |
| `create_courses_table` | `cost` — тип `string(10)` | → `decimal(10,2)` |
| `create_applications_table` | `full_name`, `email` — `string(50)` (слишком коротко) | → `string(150)` |
| `create_applications_table` | `application_date` — тип `date` (нет времени) | → `timestamp` |

### Модели

| Файл | Проблема | Исправление |
|---|---|---|
| Все модели | Отсутствуют Eloquent-связи | Добавлены `belongsTo`, `hasMany` |
| `Category`, `Course`, `Application` | `HasFactory` не использовался | Добавлен трейт |
| `User` | Нет `applications()` relationship | Добавлен `hasMany(Application::class)` |

---

## Добавленный функционал

- **Форма заявки на странице курса** — можно записаться прямо со страницы детали
- **Личный кабинет** — список заявок пользователя с датой, цветным статусом и кнопкой удаления
- **Создание курсов** — форма для добавления нового курса (для авторизованных пользователей)
- **Flash-сообщения** — уведомления об успехе после отправки заявки или создания курса
- **Inline-валидация** — подсветка полей с ошибками через Bootstrap `is-invalid`
- **Сброс фильтра** — кнопка «Сбросить» при фильтрации по категории
- **Карточки курсов** — сетка 3 колонки с изображением, описанием, ценой, категорией
- **Eager loading** — `with('category')` и `with('course.category')` для предотвращения N+1
- **Seeders** — 8 категорий и 8 реальных курсов для быстрого старта
- **Фабрики** — `CategoryFactory`, `CourseFactory`, `ApplicationFactory` (с состояниями pending/approved/rejected)

---

## Автор

Петунин Иван Евгеньевич
