<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\TagSeeder;

test('authors can select tags when creating a post', function () {
    $tags = Tag::factory()->count(2)->create();
    $this->actingAs(User::factory()->create());
    $this->get(route('posts.create'))->assertOk()->assertSee($tags[0]->name);
    $this->post(route('posts.store'), ['title' => 'Tagged post', 'tags' => $tags->modelKeys()])
        ->assertRedirect(route('posts.index'));
    expect(Post::sole()->tags->modelKeys())->toEqual($tags->modelKeys());
    $this->get(route('post', Post::sole()))->assertOk()->assertSee($tags[0]->name)->assertSee($tags[1]->name);
});

test('invalid tag selections do not save posts and selected tags survive validation', function () {
    $tag = Tag::factory()->create();
    $this->actingAs(User::factory()->create());
    foreach (['bad', [999999], [$tag->id, $tag->id]] as $tags) {
        $this->from(route('posts.create'))->post(route('posts.store'), ['title' => 'Invalid', 'tags' => $tags])
            ->assertSessionHasErrors();
    }
    $this->from(route('posts.create'))->post(route('posts.store'), ['title' => '', 'tags' => [$tag->id]])
        ->assertSessionHasErrors('title')->assertSessionHasInput('tags', [$tag->id]);
    $this->assertDatabaseCount('posts', 0);
});

test('author pages paginate only their posts and cards show comment counts', function () {
    $author = User::factory()->create();
    Post::factory()->count(17)->create(['user_id' => $author->id]);
    $posts = $author->posts()->latest()->orderByDesc('id')->get();
    $other = Post::factory()->create(['title' => 'Unrelated author post']);
    Comment::factory()->count(2)->create(['post_id' => $posts[0]->id, 'user_id' => $author->id]);
    $this->get(route('user', $author))->assertOk()->assertSee($posts[0]->title)
        ->assertDontSee($other->title)->assertDontSee($posts[16]->title)
        ->assertSee('Comments:</b> 2', false);
    $this->get(route('user', $author).'?page=2')->assertOk()->assertSee($posts[16]->title);
    $this->get(route('post', $posts[0]))->assertOk()->assertSee('Comments:</b> 2', false);
    $this->get('/user/999999')->assertNotFound();
});

test('tag seeding preserves existing tag assignments when repeated', function () {
    $tag = Tag::factory()->create();
    $post = Post::factory()->create();
    $post->tags()->attach($tag);
    $this->seed(TagSeeder::class);
    $this->seed(TagSeeder::class);
    $this->assertDatabaseCount('tags', 1);
    $this->assertDatabaseCount('post_tag', 1);
    expect($post->fresh()->tags->sole()->id)->toBe($tag->id);
});
