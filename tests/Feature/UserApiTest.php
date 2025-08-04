<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Запускаем миграции и сидеры для тестовой базы
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    /** @test */
    public function it_can_get_all_users()
    {
        // Создаем тестовых пользователей
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_single_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_user()
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404)
                ->assertJsonStructure([
                    'error',
                    'message',
                    'status'
                ]);
    }

    /** @test */
    public function it_can_create_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'name' => 'Test User',
                        'email' => 'test@example.com'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_user()
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function it_validates_email_format_when_creating_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_validates_unique_email_when_creating_user()
    {
        $existingUser = User::factory()->create();

        $userData = [
            'name' => 'Test User',
            'email' => $existingUser->email,
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_update_user()
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'name' => 'Updated Name',
                        'email' => 'updated@example.com'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_user()
    {
        $updateData = [
            'name' => 'Updated Name'
        ];

        $response = $this->putJson('/api/users/999', $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'User deleted successfully'
                ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_user()
    {
        $response = $this->deleteJson('/api/users/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_get_user_posts()
    {
        $user = User::factory()->create();
        $user->posts()->createMany([
            ['body' => 'First post'],
            ['body' => 'Second post']
        ]);

        $response = $this->getJson("/api/users/{$user->id}/posts");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'user_id',
                            'body',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_returns_empty_posts_for_user_without_posts()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}/posts");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => "No posts found for user ID {$user->id}",
                    'data' => []
                ]);
    }

    /** @test */
    public function it_can_get_user_comments()
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['body' => 'Test post']);
        
        $user->comments()->createMany([
            ['post_id' => $post->id, 'body' => 'First comment'],
            ['post_id' => $post->id, 'body' => 'Second comment']
        ]);

        $response = $this->getJson("/api/users/{$user->id}/comments");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'post_id',
                            'user_id',
                            'body',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_returns_empty_comments_for_user_without_comments()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}/comments");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => "No comments found for user ID {$user->id}",
                    'data' => []
                ]);
    }
} 