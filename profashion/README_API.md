# Laravel CRUD API

Полноценный CRUD API для управления пользователями, постами и комментариями, разработанный на Laravel.

## Требования

- PHP 8.2+
- Laravel 12.0+
- MySQL 5.7+ или 8.0+

## Установка и запуск

1. **Клонируйте проект** (если нужно):
```bash
git clone <repository-url>
cd profashion
```

2. **Установите зависимости**:
```bash
composer install
```

3. **Настройте переменные окружения**:
```bash
copy .env.example .env
php artisan key:generate
```

4. **Запустите миграции и сидеры**:
```bash
php artisan migrate:fresh --seed
```

5. **Запустите сервер разработки**:
```bash
php artisan serve
```

API будет доступен по адресу: `http://localhost:8000/api`

## Структура проекта

### Модели
- `User` - пользователи
- `Post` - посты
- `Comment` - комментарии

### Контроллеры
- `Api\UserController` - управление пользователями
- `Api\PostController` - управление постами
- `Api\CommentController` - управление комментариями

### Валидация
- `User\StoreUserRequest` - валидация создания пользователя
- `User\UpdateUserRequest` - валидация обновления пользователя
- `Post\StorePostRequest` - валидация создания поста
- `Post\UpdatePostRequest` - валидация обновления поста
- `Comment\StoreCommentRequest` - валидация создания комментария
- `Comment\UpdateCommentRequest` - валидация обновления комментария

### Ресурсы
- `UserResource` - форматирование ответов для пользователей
- `PostResource` - форматирование ответов для постов
- `CommentResource` - форматирование ответов для комментариев

## API Endpoints

### Пользователи
- `GET /api/users` - получить всех пользователей
- `GET /api/users/{id}` - получить пользователя по ID
- `POST /api/users` - создать пользователя
- `PUT /api/users/{id}` - обновить пользователя
- `DELETE /api/users/{id}` - удалить пользователя
- `GET /api/users/{id}/posts` - получить посты пользователя
- `GET /api/users/{id}/comments` - получить комментарии пользователя

### Посты
- `GET /api/posts` - получить все посты
- `GET /api/posts/{id}` - получить пост по ID
- `POST /api/posts` - создать пост
- `PUT /api/posts/{id}` - обновить пост
- `DELETE /api/posts/{id}` - удалить пост
- `GET /api/posts/{id}/comments` - получить комментарии поста

### Комментарии
- `GET /api/comments` - получить все комментарии
- `GET /api/comments/{id}` - получить комментарий по ID
- `POST /api/comments` - создать комментарий
- `PUT /api/comments/{id}` - обновить комментарий
- `DELETE /api/comments/{id}` - удалить комментарий

## Тестирование

### С Postman
1. Импортируйте коллекцию `Laravel_CRUD_API.postman_collection.json` в Postman
2. Убедитесь, что сервер запущен на `http://localhost:8000`
3. Выполните запросы из коллекции

### С curl
```bash
# Получить всех пользователей
curl http://localhost:8000/api/users

# Создать пользователя
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123"}'

# Получить посты пользователя
curl http://localhost:8000/api/users/1/posts
```

## База данных

### Структура таблиц

**users**
- `id` (primary key)
- `name` (string)
- `email` (string, unique)
- `password` (string, hashed)
- `created_at`, `updated_at`

**posts**
- `id` (primary key)
- `user_id` (foreign key)
- `body` (text)
- `created_at`, `updated_at`

**comments**
- `id` (primary key)
- `post_id` (foreign key)
- `user_id` (foreign key)
- `body` (text)
- `created_at`, `updated_at`

### Отношения
- User has many Posts
- User has many Comments
- Post belongs to User
- Post has many Comments
- Comment belongs to User
- Comment belongs to Post

## Валидация

### Пользователь
- `name`: обязательное, строка, максимум 255 символов
- `email`: обязательное, email формат, уникальное
- `password`: обязательное, строка, минимум 6 символов

### Пост
- `user_id`: обязательное, существует в таблице users
- `body`: обязательное, строка, максимум 10000 символов

### Комментарий
- `post_id`: обязательное, существует в таблице posts
- `user_id`: обязательное, существует в таблице users
- `body`: обязательное, строка, максимум 1000 символов

## Особенности реализации

✅ **Полный CRUD** для всех сущностей
✅ **Валидация данных** через FormRequest классы
✅ **API Resources** для консистентных JSON ответов
✅ **Eloquent отношения** между моделями
✅ **Дополнительные эндпоинты** для получения связанных данных
✅ **MySQL база данных** с правильными связями
✅ **Сидеры** с тестовыми данными
✅ **Документация API** с примерами
✅ **Коллекция Postman** для тестирования

## Дополнительные возможности

### Автоматическое удаление связанных записей
При удалении пользователя автоматически удаляются все его посты и комментарии (cascade delete).

### Загрузка связанных данных
API автоматически загружает связанные данные при необходимости:
- Посты включают информацию о пользователе и комментариях
- Комментарии включают информацию о пользователе и посте
- Пользователи могут получить свои посты и комментарии

### Консистентные ответы
Все API ответы имеют единообразную структуру:
```json
{
  "message": "Success message",
  "data": { ... }
}
```

## Возможные улучшения

- [ ] Добавить аутентификацию (Laravel Sanctum)
- [ ] Добавить авторизацию (Policies)
- [ ] Добавить пагинацию для списков
- [ ] Добавить фильтрацию и сортировку
- [ ] Добавить кэширование
- [ ] Добавить логирование
- [ ] Добавить тесты (PHPUnit)
- [ ] Добавить Swagger документацию
- [ ] Добавить Docker конфигурацию 