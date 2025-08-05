# Цвета для вывода
$Red = "Red"
$Green = "Green"
$Yellow = "Yellow"

Write-Host "🚀 Начинаем развертывание Laravel API в Docker" -ForegroundColor $Green

# Проверка наличия Docker
try {
    docker --version | Out-Null
    Write-Host "✅ Docker найден" -ForegroundColor $Green
} catch {
    Write-Host "❌ Docker не установлен. Установите Docker и попробуйте снова." -ForegroundColor $Red
    exit 1
}

# Проверка наличия Docker Compose
try {
    docker-compose --version | Out-Null
    Write-Host "✅ Docker Compose найден" -ForegroundColor $Green
} catch {
    Write-Host "❌ Docker Compose не установлен. Установите Docker Compose и попробуйте снова." -ForegroundColor $Red
    exit 1
}

# Создание .env файла если его нет
if (-not (Test-Path ".env")) {
    Write-Host "📝 Создаем .env файл..." -ForegroundColor $Yellow
    Copy-Item "docker/env.example" ".env"
    
    # Генерация случайного ключа приложения
    $AppKey = [System.Convert]::ToBase64String([System.Security.Cryptography.RandomNumberGenerator]::GetBytes(32))
    (Get-Content ".env") -replace "APP_KEY=", "APP_KEY=base64:$AppKey" | Set-Content ".env"
    
    Write-Host "✅ .env файл создан" -ForegroundColor $Green
} else {
    Write-Host "✅ .env файл уже существует" -ForegroundColor $Green
}

# Создание директорий для SSL сертификатов
if (-not (Test-Path "docker/ssl")) {
    Write-Host "📁 Создаем директорию для SSL сертификатов..." -ForegroundColor $Yellow
    New-Item -ItemType Directory -Path "docker/ssl" -Force | Out-Null
    Write-Host "✅ Директория создана" -ForegroundColor $Green
}

# Остановка существующих контейнеров
Write-Host "🛑 Останавливаем существующие контейнеры..." -ForegroundColor $Yellow
docker-compose down

# Удаление старых образов
Write-Host "🧹 Очищаем старые образы..." -ForegroundColor $Yellow
docker system prune -f

# Сборка и запуск контейнеров
Write-Host "🔨 Собираем и запускаем контейнеры..." -ForegroundColor $Yellow
docker-compose up -d --build

# Ожидание запуска сервисов
Write-Host "⏳ Ожидаем запуска сервисов..." -ForegroundColor $Yellow
Start-Sleep -Seconds 30

# Проверка статуса контейнеров
Write-Host "🔍 Проверяем статус контейнеров..." -ForegroundColor $Yellow
docker-compose ps

# Проверка доступности приложения
Write-Host "🌐 Проверяем доступность приложения..." -ForegroundColor $Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost/api/users" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Приложение доступно по адресу: http://localhost" -ForegroundColor $Green
        Write-Host "✅ API доступен по адресу: http://localhost/api/users" -ForegroundColor $Green
    } else {
        Write-Host "❌ Приложение недоступно. Проверьте логи:" -ForegroundColor $Red
        Write-Host "docker-compose logs" -ForegroundColor $Yellow
    }
} catch {
    Write-Host "❌ Приложение недоступно. Проверьте логи:" -ForegroundColor $Red
    Write-Host "docker-compose logs" -ForegroundColor $Yellow
}

# Информация о развертывании
Write-Host "🎉 Развертывание завершено!" -ForegroundColor $Green
Write-Host "📋 Полезные команды:" -ForegroundColor $Yellow
Write-Host "  • Просмотр логов: docker-compose logs -f" -ForegroundColor $Green
Write-Host "  • Остановка: docker-compose down" -ForegroundColor $Green
Write-Host "  • Перезапуск: docker-compose restart" -ForegroundColor $Green
Write-Host "  • Обновление: docker-compose up -d --build" -ForegroundColor $Green
Write-Host "  • Доступ к контейнеру: docker-compose exec app bash" -ForegroundColor $Green
Write-Host "  • База данных: docker-compose exec db mysql -u laravel_user -p laravel" -ForegroundColor $Green

# Проверка SSL сертификатов для продакшена
if ((Test-Path "docker/ssl/certificate.crt") -and (Test-Path "docker/ssl/private.key")) {
    Write-Host "🔒 SSL сертификаты найдены" -ForegroundColor $Green
    Write-Host "Для запуска с SSL используйте: docker-compose -f docker-compose.prod.yml up -d" -ForegroundColor $Yellow
} else {
    Write-Host "⚠️  SSL сертификаты не найдены" -ForegroundColor $Yellow
    Write-Host "Для продакшена добавьте сертификаты в docker/ssl/ и используйте docker-compose.prod.yml" -ForegroundColor $Yellow
} 