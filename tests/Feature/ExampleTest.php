<?php

use App\Models\Post;
use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home displays post cards and a second page', function () {
    $posts = Post::factory()->count(17)->create();
    $this->get(route('home'))->assertOk()
        ->assertSee($posts[0]->title)
        ->assertSee(route('post', $posts[0]), false)
        ->assertDontSee($posts[16]->title);
    $this->get('/?page=2')->assertOk()->assertSee($posts[16]->title);
});

test('post detail preserves line breaks and escapes HTML', function () {
    $post = Post::factory()->create(['body' => "First line\n<script>alert(1)</script>"]);
    $this->get(route('post', $post))->assertOk()
        ->assertSee('First line<br />', false)
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert(1)</script>', false);
    $this->get('/post/999999')->assertNotFound();
});

test('admin lists posts and buttons retain the utility-only exercise', function () {
    $post = Post::factory()->create();
    $this->get(route('posts.index'))->assertOk()->assertSee($post->title);
    $this->get(route('buttons'))->assertOk()
        ->assertSee('Disabled primary')
        ->assertSee('focus-visible:ring-4', false);
});

test('seeding keeps existing posts', function () {
    $post = Post::factory()->create();
    $this->seed(PostSeeder::class);
    $this->assertDatabaseCount('posts', 1);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => $post->title]);
});
