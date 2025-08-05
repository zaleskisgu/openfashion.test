#!/bin/bash

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Начинаем развертывание Laravel API в Docker${NC}"

# Проверка наличия Docker
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker не установлен. Установите Docker и попробуйте снова.${NC}"
    exit 1
fi

# Проверка наличия Docker Compose
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose не установлен. Установите Docker Compose и попробуйте снова.${NC}"
    exit 1
fi

# Создание .env файла если его нет
if [ ! -f .env ]; then
    echo -e "${YELLOW}📝 Создаем .env файл...${NC}"
    cp docker/env.example .env
    
    # Генерация случайного ключа приложения
    APP_KEY=$(openssl rand -base64 32)
    sed -i "s/APP_KEY=/APP_KEY=base64:$APP_KEY/" .env
    
    echo -e "${GREEN}✅ .env файл создан${NC}"
else
    echo -e "${GREEN}✅ .env файл уже существует${NC}"
fi

# Создание директорий для SSL сертификатов
if [ ! -d "docker/ssl" ]; then
    echo -e "${YELLOW}📁 Создаем директорию для SSL сертификатов...${NC}"
    mkdir -p docker/ssl
    echo -e "${GREEN}✅ Директория создана${NC}"
fi

# Остановка существующих контейнеров
echo -e "${YELLOW}🛑 Останавливаем существующие контейнеры...${NC}"
docker-compose down

# Удаление старых образов
echo -e "${YELLOW}🧹 Очищаем старые образы...${NC}"
docker system prune -f

# Сборка и запуск контейнеров
echo -e "${YELLOW}🔨 Собираем и запускаем контейнеры...${NC}"
docker-compose up -d --build

# Ожидание запуска сервисов
echo -e "${YELLOW}⏳ Ожидаем запуска сервисов...${NC}"
sleep 30

# Проверка статуса контейнеров
echo -e "${YELLOW}🔍 Проверяем статус контейнеров...${NC}"
docker-compose ps

# Проверка доступности приложения
echo -e "${YELLOW}🌐 Проверяем доступность приложения...${NC}"
if curl -f http://localhost/api/users > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Приложение доступно по адресу: http://localhost${NC}"
    echo -e "${GREEN}✅ API доступен по адресу: http://localhost/api/users${NC}"
else
    echo -e "${RED}❌ Приложение недоступно. Проверьте логи:${NC}"
    echo -e "${YELLOW}docker-compose logs${NC}"
fi

# Информация о развертывании
echo -e "${GREEN}🎉 Развертывание завершено!${NC}"
echo -e "${YELLOW}📋 Полезные команды:${NC}"
echo -e "  • Просмотр логов: ${GREEN}docker-compose logs -f${NC}"
echo -e "  • Остановка: ${GREEN}docker-compose down${NC}"
echo -e "  • Перезапуск: ${GREEN}docker-compose restart${NC}"
echo -e "  • Обновление: ${GREEN}docker-compose up -d --build${NC}"
echo -e "  • Доступ к контейнеру: ${GREEN}docker-compose exec app bash${NC}"
echo -e "  • База данных: ${GREEN}docker-compose exec db mysql -u laravel_user -p laravel${NC}"

# Проверка SSL сертификатов для продакшена
if [ -f "docker/ssl/certificate.crt" ] && [ -f "docker/ssl/private.key" ]; then
    echo -e "${GREEN}🔒 SSL сертификаты найдены${NC}"
    echo -e "${YELLOW}Для запуска с SSL используйте: ${GREEN}docker-compose -f docker-compose.prod.yml up -d${NC}"
else
    echo -e "${YELLOW}⚠️  SSL сертификаты не найдены${NC}"
    echo -e "${YELLOW}Для продакшена добавьте сертификаты в docker/ssl/ и используйте docker-compose.prod.yml${NC}"
fi 