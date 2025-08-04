# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
Currently, the API does not require authentication for testing purposes.

## Endpoints

### Users

#### Get all users
```
GET /api/users
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    }
  ]
}
```

#### Get user by ID
```
GET /api/users/{id}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-08-04T19:29:52.000000Z",
    "updated_at": "2025-08-04T19:29:52.000000Z"
  }
}
```

#### Create user
```
POST /api/users
```

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "User created successfully",
  "data": {
    "id": 4,
    "name": "New User",
    "email": "newuser@example.com",
    "created_at": "2025-08-04T19:30:00.000000Z",
    "updated_at": "2025-08-04T19:30:00.000000Z"
  }
}
```

#### Update user
```
PUT /api/users/{id}
```

**Request Body:**
```json
{
  "name": "Updated Name",
  "email": "updated@example.com"
}
```

**Response:**
```json
{
  "message": "User updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Name",
    "email": "updated@example.com",
    "created_at": "2025-08-04T19:29:52.000000Z",
    "updated_at": "2025-08-04T19:30:00.000000Z"
  }
}
```

#### Delete user
```
DELETE /api/users/{id}
```

**Response:**
```json
{
  "message": "User deleted successfully"
}
```

#### Get user's posts
```
GET /api/users/{id}/posts
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "body": "This is my first post about Laravel development!",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "comments": [],
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    }
  ]
}
```

#### Get user's comments
```
GET /api/users/{id}/comments
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "post_id": 1,
      "user_id": 2,
      "body": "Great post! Laravel is indeed a powerful framework.",
      "user": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "post": {
        "id": 1,
        "user_id": 1,
        "body": "This is my first post about Laravel development!",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2025-08-04T19:29:52.000000Z",
          "updated_at": "2025-08-04T19:29:52.000000Z"
        },
        "comments": [],
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    }
  ]
}
```

### Posts

#### Get all posts
```
GET /api/posts
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "body": "This is my first post about Laravel development!",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "comments": [
        {
          "id": 1,
          "post_id": 1,
          "user_id": 2,
          "body": "Great post! Laravel is indeed a powerful framework.",
          "created_at": "2025-08-04T19:29:52.000000Z",
          "updated_at": "2025-08-04T19:29:52.000000Z"
        }
      ],
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    }
  ]
}
```

#### Get post by ID
```
GET /api/posts/{id}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "body": "This is my first post about Laravel development!",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    },
    "comments": [
      {
        "id": 1,
        "post_id": 1,
        "user_id": 2,
        "body": "Great post! Laravel is indeed a powerful framework.",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      }
    ],
    "created_at": "2025-08-04T19:29:52.000000Z",
    "updated_at": "2025-08-04T19:29:52.000000Z"
  }
}
```

#### Create post
```
POST /api/posts
```

**Request Body:**
```json
{
  "user_id": 1,
  "body": "This is a new post about Laravel API development!"
}
```

**Response:**
```json
{
  "message": "Post created successfully",
  "data": {
    "id": 5,
    "user_id": 1,
    "body": "This is a new post about Laravel API development!",
    "user": null,
    "comments": [],
    "created_at": "2025-08-04T19:30:00.000000Z",
    "updated_at": "2025-08-04T19:30:00.000000Z"
  }
}
```

#### Update post
```
PUT /api/posts/{id}
```

**Request Body:**
```json
{
  "body": "Updated post content"
}
```

**Response:**
```json
{
  "message": "Post updated successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "body": "Updated post content",
    "user": null,
    "comments": [],
    "created_at": "2025-08-04T19:29:52.000000Z",
    "updated_at": "2025-08-04T19:30:00.000000Z"
  }
}
```

#### Delete post
```
DELETE /api/posts/{id}
```

**Response:**
```json
{
  "message": "Post deleted successfully"
}
```

#### Get post's comments
```
GET /api/posts/{id}/comments
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "post_id": 1,
      "user_id": 2,
      "body": "Great post! Laravel is indeed a powerful framework.",
      "user": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "post": {
        "id": 1,
        "user_id": 1,
        "body": "This is my first post about Laravel development!",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2025-08-04T19:29:52.000000Z",
          "updated_at": "2025-08-04T19:29:52.000000Z"
        },
        "comments": [],
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    }
  ]
}
```

### Comments

#### Get all comments
```
GET /api/comments
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "post_id": 1,
      "user_id": 2,
      "body": "Great post! Laravel is indeed a powerful framework.",
      "user": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "post": {
        "id": 1,
        "user_id": 1,
        "body": "This is my first post about Laravel development!",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2025-08-04T19:29:52.000000Z",
          "updated_at": "2025-08-04T19:29:52.000000Z"
        },
        "comments": [],
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    }
  ]
}
```

#### Get comment by ID
```
GET /api/comments/{id}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "post_id": 1,
    "user_id": 2,
    "body": "Great post! Laravel is indeed a powerful framework.",
    "user": {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com",
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    },
    "post": {
      "id": 1,
      "user_id": 1,
      "body": "This is my first post about Laravel development!",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-08-04T19:29:52.000000Z",
        "updated_at": "2025-08-04T19:29:52.000000Z"
      },
      "comments": [],
      "created_at": "2025-08-04T19:29:52.000000Z",
      "updated_at": "2025-08-04T19:29:52.000000Z"
    },
    "created_at": "2025-08-04T19:29:52.000000Z",
    "updated_at": "2025-08-04T19:29:52.000000Z"
  }
}
```

#### Create comment
```
POST /api/comments
```

**Request Body:**
```json
{
  "post_id": 1,
  "user_id": 3,
  "body": "This is a new comment!"
}
```

**Response:**
```json
{
  "message": "Comment created successfully",
  "data": {
    "id": 6,
    "post_id": 1,
    "user_id": 3,
    "body": "This is a new comment!",
    "user": null,
    "post": null,
    "created_at": "2025-08-04T19:30:00.000000Z",
    "updated_at": "2025-08-04T19:30:00.000000Z"
  }
}
```

#### Update comment
```
PUT /api/comments/{id}
```

**Request Body:**
```json
{
  "body": "Updated comment content"
}
```

**Response:**
```json
{
  "message": "Comment updated successfully",
  "data": {
    "id": 1,
    "post_id": 1,
    "user_id": 2,
    "body": "Updated comment content",
    "user": null,
    "post": null,
    "created_at": "2025-08-04T19:29:52.000000Z",
    "updated_at": "2025-08-04T19:30:00.000000Z"
  }
}
```

#### Delete comment
```
DELETE /api/comments/{id}
```

**Response:**
```json
{
  "message": "Comment deleted successfully"
}
```

## Validation Rules

### User
- `name`: required, string, max 255 characters
- `email`: required, email format, unique
- `password`: required, string, min 6 characters

### Post
- `user_id`: required, exists in users table
- `body`: required, string, max 10000 characters

### Comment
- `post_id`: required, exists in posts table
- `user_id`: required, exists in users table
- `body`: required, string, max 1000 characters

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

### Not Found Error (404)
```json
{
  "message": "No query results for model [App\\Models\\User] 999"
}
```

## Testing with Postman

1. Import the following collection into Postman:

```json
{
  "info": {
    "name": "Laravel CRUD API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Users",
      "item": [
        {
          "name": "Get All Users",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/users"
          }
        },
        {
          "name": "Get User by ID",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/users/1"
          }
        },
        {
          "name": "Create User",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/users",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"Test User\",\n  \"email\": \"test@example.com\",\n  \"password\": \"password123\"\n}"
            }
          }
        },
        {
          "name": "Update User",
          "request": {
            "method": "PUT",
            "url": "http://localhost:8000/api/users/1",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"Updated Name\"\n}"
            }
          }
        },
        {
          "name": "Delete User",
          "request": {
            "method": "DELETE",
            "url": "http://localhost:8000/api/users/1"
          }
        },
        {
          "name": "Get User Posts",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/users/1/posts"
          }
        },
        {
          "name": "Get User Comments",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/users/1/comments"
          }
        }
      ]
    },
    {
      "name": "Posts",
      "item": [
        {
          "name": "Get All Posts",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/posts"
          }
        },
        {
          "name": "Get Post by ID",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/posts/1"
          }
        },
        {
          "name": "Create Post",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/posts",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"user_id\": 1,\n  \"body\": \"This is a new post!\"\n}"
            }
          }
        },
        {
          "name": "Update Post",
          "request": {
            "method": "PUT",
            "url": "http://localhost:8000/api/posts/1",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"body\": \"Updated post content\"\n}"
            }
          }
        },
        {
          "name": "Delete Post",
          "request": {
            "method": "DELETE",
            "url": "http://localhost:8000/api/posts/1"
          }
        },
        {
          "name": "Get Post Comments",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/posts/1/comments"
          }
        }
      ]
    },
    {
      "name": "Comments",
      "item": [
        {
          "name": "Get All Comments",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/comments"
          }
        },
        {
          "name": "Get Comment by ID",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/api/comments/1"
          }
        },
        {
          "name": "Create Comment",
          "request": {
            "method": "POST",
            "url": "http://localhost:8000/api/comments",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"post_id\": 1,\n  \"user_id\": 2,\n  \"body\": \"This is a new comment!\"\n}"
            }
          }
        },
        {
          "name": "Update Comment",
          "request": {
            "method": "PUT",
            "url": "http://localhost:8000/api/comments/1",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"body\": \"Updated comment content\"\n}"
            }
          }
        },
        {
          "name": "Delete Comment",
          "request": {
            "method": "DELETE",
            "url": "http://localhost:8000/api/comments/1"
          }
        }
      ]
    }
  ]
}
```

## Features Implemented

✅ **CRUD Operations**: Complete CRUD for Users, Posts, and Comments
✅ **Validation**: FormRequest classes for input validation
✅ **API Resources**: Laravel Resources for consistent JSON responses
✅ **Relationships**: Proper Eloquent relationships between models
✅ **Additional Endpoints**: 
   - Get all posts for a user
   - Get all comments for a user
   - Get all comments for a post
   - Reset and seed database
✅ **Database**: SQLite database with migrations and seeders
✅ **Documentation**: Complete API documentation with examples

## Running the Application

1. Start the server:
```bash
php artisan serve
```

2. Run migrations (if needed):
```bash
php artisan migrate:fresh --seed
```

3. Test the API using Postman or any HTTP client

The API will be available at `http://localhost:8000/api`

## Database Management

### Reset and seed database
```
POST /api/database/reset
```

**Description:** This endpoint performs the equivalent of `php artisan migrate:fresh --seed`. It drops all tables, recreates them from migrations, and populates them with test data.

**Response:**
```json
{
  "message": "Database reset and seeded successfully",
  "data": {
    "migrations": "All tables recreated",
    "seeding": "Test data inserted"
  }
}
```

**Error Response (500):**
```json
{
  "message": "Failed to reset database",
  "error": "Error details"
}
``` 