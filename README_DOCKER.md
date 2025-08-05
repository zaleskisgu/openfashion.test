# Docker развертывание Laravel API

Этот проект настроен для работы в Docker контейнерах с Nginx, PHP-FPM и MySQL.

## Быстрый старт

### 1. Автоматическое развертывание

**Windows (PowerShell):**
```powershell
# Запуск автоматического развертывания
.\deploy.ps1
```

**Linux/Mac (Bash):**
```bash
# Запуск автоматического развертывания
./deploy.sh
```

### 2. Ручное развертывание
```bash
# Создание .env файла
cp docker/env.example .env

# Запуск контейнеров
docker-compose up -d --build

# Проверка статуса
docker-compose ps
```

## Структура файлов

```
├── docker-compose.yml          # Основная конфигурация
├── docker-compose.prod.yml     # Продакшен конфигурация
├── deploy.sh                   # Скрипт автоматического развертывания
├── README_DOCKER.md           # Эта документация
├── DOCKER_QUICK_START.md      # Быстрый старт
└── docker/                    # Docker конфигурации
    ├── Dockerfile             # PHP-FPM образ
    ├── .dockerignore          # Исключения для Docker
    ├── env.example            # Пример .env
    ├── nginx/                 # Конфигурации Nginx
    ├── php/                   # PHP настройки
    ├── mysql/                 # MySQL настройки
    ├── prometheus/            # Мониторинг
    ├── scripts/               # Скрипты
    └── ssl/                   # SSL сертификаты
```

## Команды управления

```bash
# Запуск (разработка)
docker-compose up -d

# Запуск (продакшен с SSL)
docker-compose -f docker-compose.prod.yml up -d

# Остановка
docker-compose down

# Просмотр логов
docker-compose logs -f

# Доступ к контейнеру
docker-compose exec app bash

# База данных
docker-compose exec db mysql -u laravel_user -p laravel
```

## Доступ к приложению

- **Локально:** http://localhost
- **API:** http://localhost/api/users
- **Продакшен:** https://jz123.ru (с SSL сертификатами)

## Продакшен

Для продакшена:

1. Добавьте SSL сертификаты в `docker/ssl/`
2. Настройте переменные окружения в `.env`
3. Запустите: `docker-compose -f docker-compose.prod.yml up -d`

## Архитектура

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Nginx     │    │   PHP-FPM   │    │    MySQL    │
│   (Port 80) │◄──►│   (Port 9000)│◄──►│  (Port 3306)│
└─────────────┘    └─────────────┘    └─────────────┘
```

## Сервисы

### 1. Nginx (Порт 80)
- Веб-сервер
- Проксирование запросов к PHP-FPM
- Gzip сжатие
- Кэширование статических файлов
- Безопасность (заголовки)

### 2. PHP-FPM (Порт 9000)
- PHP 8.2 с необходимыми расширениями
- Composer для управления зависимостями
- Laravel приложение
- Автоматические миграции и сидеры

### 3. MySQL (Порт 3306)
- MySQL 8.0
- Автоматическое создание базы данных
- Персистентное хранение данных

### 4. Redis (Порт 6379)
- Кэширование
- Сессии (опционально)

## Troubleshooting

### Проблема: Приложение не запускается
```bash
# Проверьте логи
docker-compose logs app

# Проверьте права доступа
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Проблема: База данных недоступна
```bash
# Проверьте статус MySQL
docker-compose logs db

# Проверьте подключение
docker-compose exec app php artisan tinker
```

### Проблема: Nginx не отвечает
```bash
# Проверьте конфигурацию Nginx
docker-compose exec nginx nginx -t

# Перезапустите Nginx
docker-compose restart nginx
```

## Производительность

### Оптимизация PHP
- Увеличьте `memory_limit` в `docker/php/local.ini`
- Настройте OPcache для кэширования

### Оптимизация Nginx
- Включите Gzip сжатие
- Настройте кэширование статических файлов
- Используйте CDN для статических ресурсов

### Оптимизация MySQL
- Настройте индексы
- Оптимизируйте запросы
- Используйте кэширование

## Безопасность

### Рекомендации
1. Измените пароли по умолчанию
2. Используйте SSL в продакшене
3. Ограничьте доступ к портам
4. Регулярно обновляйте образы
5. Используйте secrets для чувствительных данных

## Резервное копирование

### База данных
```bash
# Создание бэкапа
docker-compose exec db mysqldump -u laravel_user -p laravel > backup.sql

# Восстановление
docker-compose exec -T db mysql -u laravel_user -p laravel < backup.sql
```

### Файлы приложения
```bash
# Архивирование
tar -czf app_backup.tar.gz . --exclude=node_modules --exclude=vendor
```

## Обновление приложения

```bash
# Остановка сервисов
docker-compose down

# Обновление кода
git pull origin main

# Пересборка и запуск
docker-compose up -d --build
``` 