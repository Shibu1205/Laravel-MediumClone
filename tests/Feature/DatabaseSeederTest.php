<?php

use App\Models\Category;
use App\Models\Post;

it('seeds categories and posts without schema mismatches', function () {
    $this->artisan('migrate:fresh')
        ->assertOk();

    $this->artisan('db:seed')
        ->assertOk();

    expect(Category::count())->toBe(6)
        ->and(Post::count())->toBe(100);
});
