<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guests must log in to manage posts', function () {
    $this->get(route('posts.index'))->assertRedirect(route('login'));
    $this->post(route('posts.store'), ['title' => 'Guest'])->assertRedirect(route('login'));
});

test('users only manage their own posts', function () {
    $owner = User::factory()->create();
    Post::factory()->create(['user_id' => $owner->id, 'title' => 'Own post']);
    $other = Post::factory()->create(['user_id' => User::factory(), 'title' => 'Other post']);
    $this->actingAs($owner)->get(route('posts.index'))->assertOk()->assertSee('Own post')->assertDontSee('Other post');
    $this->get(route('posts.show', $other))->assertForbidden();
    $this->get(route('posts.edit', $other))->assertForbidden();
    $this->put(route('posts.update', $other), ['title' => 'Changed'])->assertForbidden();
    $this->delete(route('posts.destroy', $other))->assertForbidden();
    $this->assertDatabaseHas('posts', ['id' => $other->id, 'title' => 'Other post']);
});

test('posts accept multiple images and render a carousel', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())->post(route('posts.store'), [
        'title' => 'Pictures',
        'images' => [UploadedFile::fake()->image('first.jpg'), UploadedFile::fake()->image('second.png')],
    ])->assertRedirect(route('posts.index'));
    $post = Post::sole();
    expect($post->images)->toHaveCount(2);
    expect(Storage::disk('public')->allFiles())->toHaveCount(2);
    $this->get(route('post', $post))->assertOk()->assertSee('carousel')->assertSee($post->images->first()->path, false);
    $this->get('/')->assertOk()->assertSee('carousel');
});

test('non-image uploads and non-array image fields are rejected', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create())->post(route('posts.store'), [
        'title' => 'Invalid', 'images' => [UploadedFile::fake()->create('document.pdf', 10, 'application/pdf')],
    ])->assertSessionHasErrors('images.0');
    $this->post(route('posts.store'), ['title' => 'Invalid', 'images' => 'invalid'])->assertSessionHasErrors('images');
    $this->assertDatabaseCount('posts', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});
