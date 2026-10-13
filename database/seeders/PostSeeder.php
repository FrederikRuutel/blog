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
        // Keep existing posts when setup is run more than once.
        if (Post::query()->doesntExist()) {
            Post::factory(1000)->create();
        }
    }
}
