<?php

use App\Models\Category;
use App\Models\Post;
use Database\Seeders\CategorySeeder;

test('the blog shows nested categories and labels categorized and uncategorized posts', function () {
    $parent = Category::factory()->create(['name' => 'Technology']);
    $child = Category::factory()->for($parent, 'parent')->create(['name' => 'Web development']);
    $post = Post::factory()->for($child)->create();
    Post::factory()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Technology', 'Web development'])
        ->assertSee('Uncategorized');

    $this->get(route('post', $post))
        ->assertOk()
        ->assertSee('<div class="badge badge-secondary">Web development</div>', false);
});

test('seeding adds four categories with four children each and can be repeated', function () {
    $this->seed(CategorySeeder::class);
    $this->seed(CategorySeeder::class);

    $roots = Category::query()->whereNull('parent_id')->withCount('children')->get();
    expect($roots)->toHaveCount(4)
        ->and($roots->pluck('children_count')->all())->toBe([4, 4, 4, 4]);
    $this->assertDatabaseCount('categories', 20);
});
