<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Запускаем миграции и сидеры для тестовой базы
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    #[Test]
    public function it_can_get_all_posts(): void
    {
        // Создаем тестовых пользователей и посты
        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/posts');

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

    #[Test]
    public function it_can_get_single_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $post->id,
                        'user_id' => $user->id,
                        'body' => $post->body
                    ]
                ]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_post(): void
    {
        $response = $this->getJson('/api/posts/999');

        $response->assertStatus(404)
                ->assertJsonStructure([
                    'message',
                    'exception',
                    'file',
                    'line',
                    'trace'
                ]);
    }

    #[Test]
    public function it_can_create_post(): void
    {
        $user = User::factory()->create();
        $postData = [
            'user_id' => $user->id,
            'body' => 'This is a test post'
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'user_id' => $user->id,
                        'body' => 'This is a test post'
                    ]
                ]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'body' => 'This is a test post'
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_post(): void
    {
        $response = $this->postJson('/api/posts', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id', 'body']);
    }

    #[Test]
    public function it_validates_user_exists_when_creating_post(): void
    {
        $postData = [
            'user_id' => 999,
            'body' => 'This is a test post'
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id']);
    }

    #[Test]
    public function it_validates_body_length_when_creating_post(): void
    {
        $user = User::factory()->create();
        $postData = [
            'user_id' => $user->id,
            'body' => str_repeat('a', 10001) // Слишком длинное тело (больше лимита в 10000)
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['body']);
    }

    #[Test]
    public function it_can_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $updateData = [
            'body' => 'Updated post content'
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'body' => 'Updated post content'
                    ]
                ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => 'Updated post content'
        ]);
    }

    #[Test]
    public function it_returns_404_when_updating_nonexistent_post(): void
    {
        $updateData = [
            'body' => 'Updated content'
        ];

        $response = $this->putJson('/api/posts/999', $updateData);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Post deleted successfully'
                ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_post(): void
    {
        $response = $this->deleteJson('/api/posts/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_get_post_comments(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $post->comments()->createMany([
            ['user_id' => $user->id, 'body' => 'First comment'],
            ['user_id' => $user->id, 'body' => 'Second comment']
        ]);

        $response = $this->getJson("/api/posts/{$post->id}/comments");

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

    #[Test]
    public function it_returns_empty_comments_for_post_without_comments(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/posts/{$post->id}/comments");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => "No comments found for post ID {$post->id}",
                    'data' => []
                ]);
    }

    #[Test]
    public function it_returns_404_for_comments_of_nonexistent_post(): void
    {
        $response = $this->getJson('/api/posts/999/comments');

        $response->assertStatus(404);
    }
} 