# Laravel CRUD API

Полноценный CRUD API для управления пользователями, постами и комментариями, разработанный на Laravel 12.0.

## 🚀 Быстрый старт

### Вариант 1: Локальная установка

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

### Вариант 2: Docker (рекомендуется)

**Windows (PowerShell):**
```powershell
# Автоматическое развертывание
.\deploy.ps1
```

**Linux/Mac (Bash):**
```bash
# Автоматическое развертывание
./deploy.sh
```

**Ручное развертывание:**
```bash
# Создание .env файла
cp docker/env.example .env

# Запуск контейнеров
docker-compose up -d --build

# Проверка статуса
docker-compose ps
```

API будет доступен по адресу: `http://localhost/api`

**📖 Подробная документация по Docker:** [README_DOCKER.md](README_DOCKER.md)

## 📚 Документация

Вся документация находится в папке `docs/`:

- **[API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md)** - Полная документация API с примерами
- **[README_API.md](docs/README_API.md)** - Руководство по установке и использованию
- **[TESTING.md](docs/TESTING.md)** - Документация по автотестам
- **[TASK_COMPLETION_SUMMARY.md](docs/TASK_COMPLETION_SUMMARY.md)** - Резюме выполнения задания

### 🐳 Docker документация

- **[README_DOCKER.md](README_DOCKER.md)** - Подробная документация по Docker развертыванию
- **[DOCKER_QUICK_START.md](DOCKER_QUICK_START.md)** - Быстрый старт с Docker

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
- **Docker** для контейнеризации
- **Nginx** для веб-сервера
- **Redis** для кэширования

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
docker/             # Docker конфигурации
├── nginx/          # Nginx настройки
├── php/            # PHP конфигурации
├── mysql/          # MySQL настройки
└── scripts/        # Скрипты запуска
```

## 📄 Лицензия

MIT License
