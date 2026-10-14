<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('adds a post to the list after a valid submission', function () {
    $user = User::factory()->create();
    $category = Category::create([
        'title' => 'Technology',
        'slug' => 'technology',
        'content' => 'Technology posts',
    ]);

    $createdAt = now()->startOfSecond();
    $this->travelTo($createdAt);

    foreach (range(1, 10) as $number) {
        Post::factory()->create([
            'title' => "Older Post {$number}",
            'slug' => "older-post-{$number}",
            'category_id' => $category->id,
            'user_id' => $user->id,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    Storage::fake('public');
    $image = UploadedFile::fake()->image('post.jpg');

    $this->actingAs($user);

    $this->get(route('posts.create'))
        ->assertOk()
        ->assertSee('name="image"', false);

    $response = $this->post(route('posts.store'), [
        'image' => $image,
        'title' => 'First Post',
        'category_id' => $category->id,
        'content' => 'This is the post content.',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('posts', [
        'title' => 'First Post',
        'slug' => 'first-post',
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $post = Post::query()->where('slug', 'first-post')->firstOrFail();
    Storage::disk('public')->assertExists($post->image);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['First Post', 'Older Post 10']);
});

it('adds a post without an image to the list', function () {
    $user = User::factory()->create();
    $category = Category::create([
        'title' => 'Technology',
        'slug' => 'technology',
        'content' => 'Technology posts',
    ]);

    $this->actingAs($user);

    $response = $this->post(route('posts.store'), [
        'title' => 'Post Without Image',
        'category_id' => $category->id,
        'content' => 'This post was created without an image.',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('posts', [
        'title' => 'Post Without Image',
        'slug' => 'post-without-image',
        'image' => null,
    ]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Post Without Image');
});
