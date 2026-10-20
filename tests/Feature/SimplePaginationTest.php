<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function paginationNavigation(string $html): string
{
    preg_match('/<nav role="navigation".*?<\/nav>/s', $html, $matches);

    return $matches[0] ?? '';
}

test('simple pagination enables only available directions', function () {
    Post::factory()->count(33)->create();

    $first = paginationNavigation($this->get('/')->assertOk()->getContent());
    expect($first)->toContain('btn btn-outline btn-disabled', 'disabled aria-disabled="true"', 'rel="next"')
        ->not->toContain('rel="prev"', 'dark:');

    $middle = paginationNavigation($this->get('/?page=2')->assertOk()->getContent());
    expect($middle)->toContain('rel="prev"', 'rel="next"')
        ->not->toContain('btn-disabled');

    $last = paginationNavigation($this->get('/?page=3')->assertOk()->getContent());
    expect($last)->toContain('rel="prev"', 'btn btn-outline btn-disabled', 'disabled aria-disabled="true"')
        ->not->toContain('rel="next"');
});

test('simple pagination is hidden when all posts fit on one page', function () {
    Post::factory()->count(16)->create();

    expect(paginationNavigation($this->get('/')->assertOk()->getContent()))->toBe('');
});
