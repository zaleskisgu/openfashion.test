# Laravel CRUD API

Полноценный CRUD API для управления пользователями, постами и комментариями, разработанный на Laravel 12.0.

## 🚀 Быстрый старт

```bash
# Установка зависимостей
composer install

# Настройка окружения
copy .env.example .env
php artisan key:generate

# Миграции и сидеры
php artisan migrate:fresh --seed

# Запуск сервера
php artisan serve
```

API будет доступен по адресу: `http://localhost:8000/api`

## 📚 Документация

Вся документация находится в папке `docs/`:

- **[API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md)** - Полная документация API с примерами
- **[README_API.md](docs/README_API.md)** - Руководство по установке и использованию
- **[TESTING.md](docs/TESTING.md)** - Документация по автотестам
- **[TASK_COMPLETION_SUMMARY.md](docs/TASK_COMPLETION_SUMMARY.md)** - Резюме выполнения задания

## 🧪 Тестирование

```bash
# Запуск всех тестов
composer test

# Запуск только API тестов
composer test:api

# Запуск только unit тестов
composer test:unit
```

**✅ Статус тестов:** 43 теста проходят успешно (338 assertions)

## 📋 Основные возможности

- ✅ **CRUD операции** для Users, Posts, Comments
- ✅ **Валидация данных** через FormRequest
- ✅ **API Resources** для консистентных ответов
- ✅ **Автотесты** для всех endpoints
- ✅ **Каскадное удаление** связанных записей
- ✅ **Обработка ошибок** с JSON ответами
- ✅ **Документация** и Postman коллекция

## 🔗 Основные endpoints

- `GET /api/users` - получить всех пользователей
- `POST /api/users` - создать пользователя
- `GET /api/users/{id}/posts` - посты пользователя
- `GET /api/users/{id}/comments` - комментарии пользователя
- `GET /api/posts/{id}/comments` - комментарии поста

Подробный список всех endpoints: [API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md)

## 🛠 Технологии

- **PHP 8.2+**
- **Laravel 12.0**
- **MySQL 5.7+**
- **PHPUnit** для тестирования
- **Larastan** для статического анализа

## 📁 Структура проекта

```
app/
├── Models/          # Eloquent модели
├── Http/
│   ├── Controllers/Api/  # API контроллеры
│   ├── Requests/         # Валидация
│   └── Resources/        # API ресурсы
tests/
├── Feature/         # API тесты
└── Unit/           # Unit тесты
docs/               # Документация
```

## 📄 Лицензия

MIT License
