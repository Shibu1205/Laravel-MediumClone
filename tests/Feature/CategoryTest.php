<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

test('category page shows published posts from authors the viewer does not follow', function () {
    $viewer = User::factory()->create();
    $author = User::factory()->create();
    $category = Category::create([
        'title' => 'Technology',
        'slug' => 'technology',
        'content' => 'Technology posts',
    ]);
    Post::factory()->create([
        'title' => 'Unfollowed Author Post',
        'category_id' => $category->id,
        'user_id' => $author->id,
        'published_at' => now(),
    ]);

    $this->actingAs($viewer)
        ->get(route('post.byCategory', $category))
        ->assertOk()
        ->assertSee('Unfollowed Author Post');
});

test('dashboard shows published posts across categories from authors the viewer does not follow', function () {
    $viewer = User::factory()->create();
    $author = User::factory()->create();
    $technology = Category::create([
        'title' => 'Technology',
        'slug' => 'technology',
        'content' => 'Technology posts',
    ]);
    $culture = Category::create([
        'title' => 'Culture',
        'slug' => 'culture',
        'content' => 'Culture posts',
    ]);

    Post::factory()->create([
        'title' => 'Technology Post',
        'category_id' => $technology->id,
        'user_id' => $author->id,
        'published_at' => now(),
    ]);
    Post::factory()->create([
        'title' => 'Culture Post',
        'category_id' => $culture->id,
        'user_id' => $author->id,
        'published_at' => now(),
    ]);

    $this->actingAs($viewer)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Technology Post')
        ->assertSee('Culture Post');
});
