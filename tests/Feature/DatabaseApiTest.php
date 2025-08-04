<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DatabaseApiTest extends TestCase
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
    public function it_can_reset_database(): void
    {
        // Создаем дополнительные данные для проверки
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        // Проверяем, что данные созданы
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);

        // Вызываем роут сброса базы
        $response = $this->postJson('/api/database/reset');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'migrations',
                        'seeding'
                    ]
                ])
                ->assertJson([
                    'message' => 'Database reset and seeded successfully',
                    'data' => [
                        'migrations' => 'All tables recreated',
                        'seeding' => 'Test data inserted'
                    ]
                ]);

        // Проверяем, что пользовательские данные удалены
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        // Проверяем, что сидерные данные восстановлены
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'bob@example.com']);
    }
} 