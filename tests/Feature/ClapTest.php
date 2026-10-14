<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

it('toggles a clap only for the signed-in user and selected post', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $category = Category::create([
        'title' => 'Technology',
        'slug' => 'technology',
        'content' => 'Technology posts',
    ]);
    $post = Post::factory()->create([
        'category_id' => $category->id,
        'user_id' => $otherUser->id,
    ]);
    $otherPost = Post::factory()->create([
        'category_id' => $category->id,
        'user_id' => $otherUser->id,
    ]);

    $post->claps()->create(['user_id' => $otherUser->id]);
    $otherPost->claps()->create(['user_id' => $user->id]);

    expect($user->hasClapped($post))->toBeFalse();
    expect($user->hasClapped($otherPost))->toBeTrue();

    $this->actingAs($user)
        ->get(route('posts.show', [
            'username' => $otherUser->username,
            'post' => $post->slug,
        ]))
        ->assertOk()
        ->assertSee('x-if="!hasClapped"', false)
        ->assertSee('x-if="hasClapped"', false)
        ->assertSee('x-text="count"', false);

    $this->actingAs($user)
        ->postJson(route('clap', $post))
        ->assertOk()
        ->assertExactJson(['clapsCount' => 2]);

    $this->assertDatabaseHas('claps', [
        'post_id' => $post->id,
        'user_id' => $user->id,
    ]);

    $this->postJson(route('clap', $post))
        ->assertOk()
        ->assertExactJson(['clapsCount' => 1]);

    $this->assertDatabaseMissing('claps', [
        'post_id' => $post->id,
        'user_id' => $user->id,
    ]);
    $this->assertDatabaseHas('claps', [
        'post_id' => $post->id,
        'user_id' => $otherUser->id,
    ]);
    $this->assertDatabaseHas('claps', [
        'post_id' => $otherPost->id,
        'user_id' => $user->id,
    ]);
});

it('rejects guests attempting to clap', function () {
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

    $this->postJson(route('clap', $post))
        ->assertUnauthorized();

    $this->assertDatabaseCount('claps', 0);
});

it('renders a public post for guests with clapping disabled', function () {
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

    $this->get(route('posts.show', [
        'username' => $user->username,
        'post' => $post->slug,
    ]))
        ->assertOk()
        ->assertSee('hasClapped:false', false)
        ->assertSee('isAuthenticated: false', false)
        ->assertSee(':disabled="isLoading || !isAuthenticated"', false);
});
