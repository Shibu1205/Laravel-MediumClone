<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

test('post owner can open the public page with an edit link', function () {
    $user = User::factory()->create();
    $category = Category::create([
        'title' => 'Technology',
        'slug' => 'technology',
        'content' => 'Technology posts',
    ]);
    $post = Post::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('posts.show', [
            'username' => $user->username,
            'post' => $post->slug,
        ]))
        ->assertOk()
        ->assertSee(route('posts.edit', $post->slug));
});
