FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www

# Установка netcat для проверки доступности базы данных
RUN apt-get update && apt-get install -y netcat-openbsd

# Копирование composer файлов
COPY composer.json composer.lock ./

# Установка зависимостей PHP (без post-install скриптов)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Копирование исходного кода
COPY . .

# Запуск post-install скриптов после копирования всех файлов
RUN composer run-script post-autoload-dump

# Копирование скрипта запуска
COPY docker/scripts/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Установка прав доступа
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

EXPOSE 9000

CMD ["/usr/local/bin/start.sh"] 