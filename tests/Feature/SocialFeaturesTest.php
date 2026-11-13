<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\FollowSeeder;
use Database\Seeders\LikeSeeder;

test('guests cannot follow like or open a feed', function () {
    $author = User::factory()->create();
    $post = Post::factory()->for($author)->create();

    $this->post(route('follow', $author))->assertRedirect(route('login'));
    $this->post(route('like', $post))->assertRedirect(route('login'));
    $this->get(route('feed'))->assertRedirect(route('login'));
    $this->assertDatabaseCount('follows', 0);
    $this->assertDatabaseCount('likes', 0);
    $this->get(route('user', $author))->assertOk()
        ->assertDontSee('action="'.route('follow', $author).'"', false);
});

test('following toggles the signed in users subscription and filters their feed', function () {
    $viewer = User::factory()->create();
    $author = User::factory()->create();
    $post = Post::factory()->for($author)->create(['title' => 'Followed author post']);
    $unrelated = Post::factory()->create(['title' => 'Unrelated post']);
    $this->actingAs($viewer)->from(route('user', $author))
        ->post(route('follow', $author))->assertRedirect(route('user', $author));
    $this->assertDatabaseHas('follows', ['follower_id' => $viewer->id, 'followee_id' => $author->id]);
    $this->get(route('user', $author))->assertOk()->assertSee('Unfollow');
    $this->get(route('feed'))->assertOk()->assertSee($post->title)->assertDontSee($unrelated->title);
    $this->post(route('follow', $author))->assertRedirect();
    $this->assertDatabaseCount('follows', 0);
    $this->get(route('feed'))->assertOk()->assertDontSee($post->title);
});

test('the followed author feed paginates', function () {
    $viewer = User::factory()->create();
    $author = User::factory()->create();
    $viewer->followees()->attach($author);
    Post::factory()->count(17)->for($author)->create();
    $posts = $author->posts()->latest()->orderByDesc('id')->get();

    $this->actingAs($viewer)->get(route('feed'))->assertOk()
        ->assertSee($posts[0]->title)->assertDontSee($posts[16]->title);
    $this->get(route('feed').'?page=2')->assertOk()->assertSee($posts[16]->title);
});

test('liking toggles only the signed in users like and displays the count and names', function () {
    $viewer = User::factory()->create(['name' => 'Current Reader']);
    $other = User::factory()->create(['name' => 'Other Reader']);
    $post = Post::factory()->create();
    Like::factory()->for($post)->for($other)->create();

    $this->actingAs($viewer)->from(route('post', $post))
        ->post(route('like', $post))->assertRedirect(route('post', $post));
    $this->assertDatabaseHas('likes', ['user_id' => $viewer->id, 'post_id' => $post->id]);
    $this->get(route('post', $post))->assertOk()
        ->assertSee('Unlike')->assertSee('Other Reader')->assertSee('Current Reader')
        ->assertSee('<span>2</span>', false)->assertSee('name="_token"', false);
    $this->post(route('like', $post))->assertRedirect();
    $this->assertDatabaseCount('likes', 1);
    $this->assertDatabaseHas('likes', ['user_id' => $other->id, 'post_id' => $post->id]);
    $this->get(route('home'))->assertOk()->assertSee('Like')->assertSee('<span>1</span>', false);
});

test('social seeds can run repeatedly and deletion removes associated rows', function () {
    $users = User::factory()->count(3)->create();
    $post = Post::factory()->for($users[0])->create();
    $users[0]->followees()->attach($users[1]);
    Like::factory()->for($post)->for($users[1])->create();
    $this->seed([FollowSeeder::class, LikeSeeder::class]);
    $this->seed([FollowSeeder::class, LikeSeeder::class]);

    expect(DB::table('follows')->count())->toBeLessThanOrEqual(9)
        ->and(Like::count())->toBeLessThanOrEqual(3);
    $users[1]->delete();
    $this->assertDatabaseMissing('follows', ['follower_id' => $users[1]->id]);
    $this->assertDatabaseMissing('follows', ['followee_id' => $users[1]->id]);
    $this->assertDatabaseMissing('likes', ['user_id' => $users[1]->id]);
    $post->delete();
    $this->assertDatabaseCount('likes', 0);
});
