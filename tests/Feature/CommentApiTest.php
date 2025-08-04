<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentApiTest extends TestCase
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
    public function it_can_get_all_comments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Comment::factory()->count(3)->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/comments');

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
    public function it_can_get_single_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        $response = $this->getJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'post_id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $comment->id,
                        'post_id' => $post->id,
                        'user_id' => $user->id,
                        'body' => $comment->body
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_comment()
    {
        $response = $this->getJson('/api/comments/999');

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
    public function it_can_create_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $commentData = [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'This is a test comment'
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'post_id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'post_id' => $post->id,
                        'user_id' => $user->id,
                        'body' => 'This is a test comment'
                    ]
                ]);

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'This is a test comment'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_comment()
    {
        $response = $this->postJson('/api/comments', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['post_id', 'user_id', 'body']);
    }

    /** @test */
    public function it_validates_post_exists_when_creating_comment()
    {
        $user = User::factory()->create();
        $commentData = [
            'post_id' => 999,
            'user_id' => $user->id,
            'body' => 'This is a test comment'
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['post_id']);
    }

    /** @test */
    public function it_validates_user_exists_when_creating_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $commentData = [
            'post_id' => $post->id,
            'user_id' => 999,
            'body' => 'This is a test comment'
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function it_validates_body_length_when_creating_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $commentData = [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => str_repeat('a', 1001) // Слишком длинный текст
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['body']);
    }

    /** @test */
    public function it_can_update_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
        
        $updateData = [
            'body' => 'Updated comment content'
        ];

        $response = $this->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'post_id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'body' => 'Updated comment content'
                    ]
                ]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment content'
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_comment()
    {
        $updateData = [
            'body' => 'Updated content'
        ];

        $response = $this->putJson('/api/comments/999', $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Comment deleted successfully'
                ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_comment()
    {
        $response = $this->deleteJson('/api/comments/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_cascades_deletes_when_post_is_deleted()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        // Удаляем пост
        $this->deleteJson("/api/posts/{$post->id}");

        // Проверяем, что комментарий тоже удален
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    /** @test */
    public function it_cascades_deletes_when_user_is_deleted()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        // Удаляем пользователя
        $this->deleteJson("/api/users/{$user->id}");

        // Проверяем, что комментарий тоже удален
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }
} 