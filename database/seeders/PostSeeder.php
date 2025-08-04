<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'user_id' => 1,
            'body' => 'This is my first post about Laravel development!',
        ]);

        Post::create([
            'user_id' => 2,
            'body' => 'Learning API development with Laravel is amazing!',
        ]);

        Post::create([
            'user_id' => 3,
            'body' => 'CRUD operations are essential for any web application.',
        ]);

        Post::create([
            'user_id' => 1,
            'body' => 'Another post from John about programming best practices.',
        ]);
    }
}
