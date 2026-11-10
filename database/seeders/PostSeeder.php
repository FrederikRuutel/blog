<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Post::query()->exists()) {
            return;
        }

        if (User::query()->doesntExist()) {
            $this->call(UserSeeder::class);
        }

        $users = User::select('id')->get();
        $categories = Category::pluck('id');
        $posts = Post::factory(1000)->make()->sortBy('created_at');
        foreach ($posts as $post) {
            $post->user()->associate($users->random());
            if ($categories->isNotEmpty() && random_int(0, 9) !== 0) {
                $post->category()->associate($categories->random());
            }
            $post->save();
        }
    }
}
