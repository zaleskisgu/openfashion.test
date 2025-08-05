#!/bin/bash

# Ждем запуска базы данных
echo "Waiting for database to be ready..."
while ! nc -z db 3306; do
  sleep 1
done
echo "Database is ready!"

# Установка зависимостей если их нет
if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Создание .env файла если его нет
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Генерация ключа приложения
echo "Generating application key..."
php artisan key:generate --force

# Запуск миграций
echo "Running migrations..."
php artisan migrate --force

# Запуск сидеров
echo "Running seeders..."
php artisan db:seed --force

# Очистка кэша
echo "Clearing cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Установка прав доступа
echo "Setting permissions..."
chown -R www-data:www-data /var/www
chmod -R 755 /var/www/storage
chmod -R 755 /var/www/bootstrap/cache

# Запуск PHP-FPM
echo "Starting PHP-FPM..."
php-fpm 