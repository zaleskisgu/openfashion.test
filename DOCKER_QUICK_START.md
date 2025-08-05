# 🚀 Быстрый старт с Docker

## Установка и запуск

### 1. Автоматический запуск

**Windows (PowerShell):**
```powershell
# Запустите скрипт развертывания
.\deploy.ps1
```

**Linux/Mac (Bash):**
```bash
# Запустите скрипт развертывания
./deploy.sh
```

### 2. Ручной запуск
```bash
# Создайте .env файл
cp docker/env.example .env

# Запустите контейнеры
docker-compose up -d --build

# Проверьте статус
docker-compose ps
```

## Доступ к приложению

- 🌐 **Веб-интерфейс:** http://localhost
- 🔌 **API:** http://localhost/api/users
- 📊 **API документация:** http://localhost/api/users (GET)

## Основные команды

```bash
# Запуск
docker-compose up -d

# Остановка
docker-compose down

# Логи
docker-compose logs -f

# Доступ к контейнеру
docker-compose exec app bash

# База данных
docker-compose exec db mysql -u laravel_user -p laravel
```

## Продакшен

Для продакшена с SSL:

1. Добавьте сертификаты в `docker/ssl/`
2. Запустите: `docker-compose -f docker-compose.prod.yml up -d`

## Структура проекта

```
├── docker-compose.yml          # Основная конфигурация
├── docker-compose.prod.yml     # Продакшен с SSL
├── deploy.sh                   # Автоматическое развертывание
└── docker/                     # Все Docker файлы
    ├── Dockerfile              # PHP-FPM образ
    ├── nginx/                  # Конфигурации Nginx
    ├── php/                    # PHP настройки
    ├── mysql/                  # MySQL настройки
    └── ssl/                    # SSL сертификаты
```

## API Endpoints

- `GET /api/users` - Список пользователей
- `GET /api/posts` - Список постов
- `GET /api/comments` - Список комментариев
- `POST /api/database/reset` - Сброс базы данных

## Troubleshooting

```bash
# Проверка логов
docker-compose logs app

# Перезапуск
docker-compose restart

# Полная пересборка
docker-compose up -d --build
```

---

📖 **Подробная документация:** `README_DOCKER.md` 