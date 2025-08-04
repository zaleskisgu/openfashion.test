<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    /** @test */
    public function it_can_get_all_posts()
    {
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

    /** @test */
    public function it_can_get_single_post()
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

    /** @test */
    public function it_returns_404_for_nonexistent_post()
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

    /** @test */
    public function it_can_create_post()
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

    /** @test */
    public function it_validates_required_fields_when_creating_post()
    {
        $response = $this->postJson('/api/posts', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id', 'body']);
    }

    /** @test */
    public function it_validates_user_exists_when_creating_post()
    {
        $postData = [
            'user_id' => 999,
            'body' => 'This is a test post'
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function it_validates_body_length_when_creating_post()
    {
        $user = User::factory()->create();
        $postData = [
            'user_id' => $user->id,
            'body' => str_repeat('a', 10001) // Слишком длинный текст
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['body']);
    }

    /** @test */
    public function it_can_update_post()
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

    /** @test */
    public function it_returns_404_when_updating_nonexistent_post()
    {
        $updateData = [
            'body' => 'Updated content'
        ];

        $response = $this->putJson('/api/posts/999', $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_post()
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

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_post()
    {
        $response = $this->deleteJson('/api/posts/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_get_post_comments()
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

    /** @test */
    public function it_returns_empty_comments_for_post_without_comments()
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

    /** @test */
    public function it_returns_404_for_comments_of_nonexistent_post()
    {
        $response = $this->getJson('/api/posts/999/comments');

        $response->assertStatus(404);
    }
} 