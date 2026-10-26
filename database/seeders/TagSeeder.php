<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Tag::query()->doesntExist()) {
            Tag::factory(10)->create();
        }
        $tags = Tag::all();
        $posts = Post::doesntHave('tags')->select('id')->get();
        foreach($posts as $post) {
            $randomTags = $tags->random(rand(0, min(10, $tags->count())));
            $post->tags()->attach($randomTags);
        }
    }
}
