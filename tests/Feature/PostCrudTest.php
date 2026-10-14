<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('posts can be created viewed edited and deleted', function () {
    $this->get(route('posts.create'))->assertOk();
    $this->post(route('posts.store'), ['title' => 'A new post', 'body' => 'Original content'])
        ->assertRedirect(route('posts.index'));
    $post = Post::sole();
    $this->get(route('posts.show', $post))->assertOk()->assertSee('Original content');
    $this->get(route('posts.edit', $post))->assertOk()->assertSee('Original content');
    $this->put(route('posts.update', $post), ['title' => 'Updated title', 'body' => 'Updated content'])
        ->assertRedirect(route('posts.index'));
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated title', 'body' => 'Updated content']);
    $this->delete(route('posts.destroy', $post))->assertRedirect(route('posts.index'));
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('title is required and invalid updates keep the original post', function () {
    $this->post(route('posts.store'), ['title' => '', 'body' => 'Content'])
        ->assertSessionHasErrors('title');
    $this->assertDatabaseCount('posts', 0);
    $post = Post::factory()->create(['title' => 'Keep me', 'body' => 'Original body']);
    $this->from(route('posts.edit', $post))
        ->put(route('posts.update', $post), ['title' => '', 'body' => 'Changed body'])
        ->assertRedirect(route('posts.edit', $post))->assertSessionHasErrors('title');
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Keep me', 'body' => 'Original body']);
});

test('post body is optional and empty content renders safely', function () {
    $this->post(route('posts.store'), ['title' => 'Title only', 'body' => ''])
        ->assertRedirect(route('posts.index'));
    $post = Post::sole();
    expect($post->body)->toBeNull();
    $this->get(route('post', $post))->assertOk()->assertSee('Title only');
    $this->get(route('home'))->assertOk()->assertSee('Title only');
});

test('admin routes return 404 for missing posts', function () {
    $this->get('/admin/posts/999999')->assertNotFound();
    $this->get('/admin/posts/999999/edit')->assertNotFound();
    $this->put('/admin/posts/999999', ['title' => 'Missing'])->assertNotFound();
    $this->delete('/admin/posts/999999')->assertNotFound();
});
