<?php

use App\Models\Post;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;

test('signed in authors are attached to new posts', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'An authored post', 'body' => 'Content',
    ])->assertRedirect(route('posts.index'));
    expect(Post::first()->user->id)->toBe($user->id);
});

test('deleting an author preserves their posts', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $user->delete();
    expect($post->fresh()->user_id)->toBeNull();
    $this->get('/')->assertOk()->assertSee('Unknown author');
});

test('demo user seeding is repeatable and handles an empty password setting', function () {
    $this->seed(UserSeeder::class);
    $count = User::count();
    $this->seed(UserSeeder::class);
    expect(User::count())->toBe($count);
    $user = User::where('email', env('DEFAULT_USER_EMAIL', 'user@user.user'))->firstOrFail();
    expect(Hash::check('password', $user->password))->toBeTrue();
});
