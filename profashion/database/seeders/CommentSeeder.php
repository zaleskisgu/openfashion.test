<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'post_id' => 1,
            'user_id' => 2,
            'body' => 'Great post! Laravel is indeed a powerful framework.',
        ]);

        Comment::create([
            'post_id' => 1,
            'user_id' => 3,
            'body' => 'I agree, Laravel makes development much easier.',
        ]);

        Comment::create([
            'post_id' => 2,
            'user_id' => 1,
            'body' => 'API development with Laravel is really straightforward!',
        ]);

        Comment::create([
            'post_id' => 3,
            'user_id' => 2,
            'body' => 'CRUD operations are the foundation of web development.',
        ]);

        Comment::create([
            'post_id' => 4,
            'user_id' => 3,
            'body' => 'Best practices are crucial for maintainable code.',
        ]);
    }
}
