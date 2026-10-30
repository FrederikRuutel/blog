<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

test('all tag management routes require login', function () {
    $tag = Tag::factory()->create();
    foreach (['tags.index', 'tags.create', 'tags.show', 'tags.edit'] as $route) {
        $this->get(route($route, $tag))->assertRedirect(route('login'));
    }
    $this->post(route('tags.store'), ['name' => 'Guest'])->assertRedirect(route('login'));
    $this->put(route('tags.update', $tag), ['name' => 'Guest'])->assertRedirect(route('login'));
    $this->delete(route('tags.destroy', $tag))->assertRedirect(route('login'));
    $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => $tag->name]);
});

test('tags can be created viewed and updated including keeping the same name', function () {
    $this->actingAs(User::factory()->create());
    $this->get(route('tags.create'))->assertOk();
    $this->post(route('tags.store'), ['name' => 'Laravel'])->assertRedirect(route('tags.index'));
    $tag = Tag::sole();
    $this->get(route('tags.show', $tag))->assertOk()->assertSee('Laravel');
    $this->get(route('tags.edit', $tag))->assertOk()->assertSee('value="Laravel"', false);
    $this->put(route('tags.update', $tag), ['name' => 'Laravel'])->assertSessionHasNoErrors()->assertRedirect(route('tags.index'));
    $this->put(route('tags.update', $tag), ['name' => 'PHP'])->assertRedirect(route('tags.index'));
    $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'PHP']);
});

test('tag names are required unique strings of at most 255 characters', function () {
    $this->actingAs(User::factory()->create());
    $tag = Tag::factory()->create(['name' => 'Existing']);
    $other = Tag::factory()->create(['name' => 'Other']);
    foreach (['', '   ', 'Existing', str_repeat('a', 256), ['array']] as $name) {
        $this->from(route('tags.create'))->post(route('tags.store'), ['name' => $name])
            ->assertRedirect(route('tags.create'))->assertSessionHasErrors('name');
    }
    $this->from(route('tags.edit', $other))->put(route('tags.update', $other), ['name' => $tag->name])
        ->assertRedirect(route('tags.edit', $other))->assertSessionHasErrors('name')
        ->assertSessionHasInput('name', 'Existing');
    $this->assertDatabaseCount('tags', 2);
    expect($other->fresh()->name)->toBe('Other');
});

test('tag index is alphabetically paginated and includes post counts', function () {
    $this->actingAs(User::factory()->create());
    for ($i = 16; $i >= 0; $i--) {
        Tag::factory()->create(['name' => sprintf('Tag %02d', $i)]);
    }
    $tag = Tag::where('name', 'Tag 00')->firstOrFail();
    $tag->posts()->attach(Post::factory()->count(2)->create()->modelKeys());
    $this->get(route('tags.index'))->assertOk()->assertViewHas('tags', function ($tags) use ($tag) {
        return $tags->total() === 17 && $tags->count() === 16
            && $tags->first()->id === $tag->id && $tags->first()->posts_count === 2;
    })->assertSeeInOrder(['Tag 00', 'Tag 01', 'Tag 15'])->assertDontSee('Tag 16');
    $this->get(route('tags.index').'?page=2')->assertOk()->assertSee('Tag 16');
});

test('tag view shows all associated posts across pages and deletion preserves posts', function () {
    $this->actingAs(User::factory()->create());
    $tag = Tag::factory()->create(['name' => 'Remove this label']);
    $posts = Post::factory()->count(17)->create();
    $tag->posts()->attach($posts->modelKeys());
    $unrelated = Post::factory()->create(['title' => 'Unrelated post']);
    $this->get(route('tags.show', $tag))->assertOk()->assertViewHas('posts', fn ($p) => $p->total() === 17)
        ->assertDontSee($unrelated->title);
    $this->get(route('tags.show', $tag).'?page=2')->assertOk()->assertViewHas('posts', fn ($p) => $p->count() === 1);
    $this->get(route('post', $posts[0]))->assertOk()->assertSee($tag->name);
    $this->delete(route('tags.destroy', $tag))->assertRedirect(route('tags.index'));
    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    $this->assertDatabaseCount('post_tag', 0);
    $this->assertDatabaseCount('posts', 18);
    $this->get(route('post', $posts[0]))->assertOk()->assertDontSee($tag->name);
});

test('missing tags return 404 and the navigation links to tag management', function () {
    $this->actingAs(User::factory()->create());
    $this->get('/admin/tags/999999')->assertNotFound();
    $this->get('/admin/tags/999999/edit')->assertNotFound();
    $this->put('/admin/tags/999999', ['name' => 'Missing'])->assertNotFound();
    $this->delete('/admin/tags/999999')->assertNotFound();
    $this->get(route('tags.index'))->assertOk()->assertSee(route('tags.index'), false);
});
