<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

test('only authenticated visitors can submit comments', function () {
    $post = Post::factory()->create();
    $this->get(route('post', $post))->assertOk()->assertSee('Log in to comment')->assertDontSee('name="body"', false);
    $this->post(route('comments.store', $post), ['body' => 'Guest comment'])->assertRedirect(route('login'));
    $this->assertDatabaseCount('comments', 0);
});

test('comments belong to the route post and signed in user and render safely', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $other = User::factory()->create();
    $this->actingAs($user)->post(route('comments.store', $post), [
        'body' => '<script>alert(1)</script>', 'user_id' => $other->id, 'post_id' => 999999,
    ])->assertRedirect(route('post', $post))->assertSessionHas('status', 'comment-created');
    $comment = Comment::sole();
    expect($comment->user_id)->toBe($user->id)->and($comment->post_id)->toBe($post->id);
    $this->get(route('post', $post))->assertOk()->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert(1)</script>', false)->assertSee($user->name);
});

test('comment body must be a nonempty string and the post must exist', function () {
    $post = Post::factory()->create();
    $this->actingAs(User::factory()->create());
    foreach (['', '   ', ['invalid']] as $body) {
        $this->from(route('post', $post))->post(route('comments.store', $post), ['body' => $body])
            ->assertRedirect(route('post', $post))->assertSessionHasErrors('body');
    }
    $this->post('/post/999999/comments', ['body' => 'Missing post'])->assertNotFound();
    $this->assertDatabaseCount('comments', 0);
});
