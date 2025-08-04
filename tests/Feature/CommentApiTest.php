<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function it_can_get_all_comments(): void
    {
        // Создаем тестовых пользователей, посты и комментарии
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Comment::factory()->count(3)->create([
            'user_id' => $user->id,
            'post_id' => $post->id
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

    #[Test]
    public function it_can_get_single_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
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

    #[Test]
    public function it_returns_404_for_nonexistent_comment(): void
    {
        $response = $this->getJson('/api/comments/999');

        $response->assertStatus(404)
                ->assertJsonStructure([
                    'message',
                    'error'
                ])
                ->assertJson([
                    'message' => 'Comment with ID 999 not found',
                    'error' => 'Comment not found'
                ]);
    }

    #[Test]
    public function it_can_create_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $commentData = [
            'user_id' => $user->id,
            'post_id' => $post->id,
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
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                        'body' => 'This is a test comment'
                    ]
                ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => 'This is a test comment'
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_comment(): void
    {
        $response = $this->postJson('/api/comments', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id', 'post_id', 'body']);
    }

    #[Test]
    public function it_validates_post_exists_when_creating_comment(): void
    {
        $user = User::factory()->create();
        $commentData = [
            'user_id' => $user->id,
            'post_id' => 999,
            'body' => 'This is a test comment'
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['post_id']);
    }

    #[Test]
    public function it_validates_user_exists_when_creating_comment(): void
    {
        $post = Post::factory()->create();
        $commentData = [
            'user_id' => 999,
            'post_id' => $post->id,
            'body' => 'This is a test comment'
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['user_id']);
    }

    #[Test]
    public function it_validates_body_length_when_creating_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $commentData = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => str_repeat('a', 1001) // Слишком длинное тело
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['body']);
    }

    #[Test]
    public function it_can_update_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
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

    #[Test]
    public function it_returns_404_when_updating_nonexistent_comment(): void
    {
        $updateData = [
            'body' => 'Updated content'
        ];

        $response = $this->putJson('/api/comments/999', $updateData);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_delete_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
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

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_comment(): void
    {
        $response = $this->deleteJson('/api/comments/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_cascades_deletes_when_post_is_deleted(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        // Удаляем пост
        $post->delete();

        // Проверяем, что комментарий тоже удален
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    #[Test]
    public function it_cascades_deletes_when_user_is_deleted(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        // Удаляем пользователя
        $user->delete();

        // Проверяем, что комментарий тоже удален
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }
} 