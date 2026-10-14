
<?php

use App\Http\Controllers\ClapController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [PostController::class, 'index'])
        ->name('dashboard');

Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])
        ->name('posts.show');

Route::get('/category/{category}',[PostController::class,'category'])
    ->name('post.byCategory');

Route::middleware(['auth', 'verified'])->group(function () {
    

    

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts/create', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{post:slug}', [PostController::class, 'edit'])
        ->name('posts.edit');
    
    Route::put('/posts/{post:slug}', [PostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');

    Route::get('/my-posts', [PostController::class, 'myPosts'])
        ->name('myPosts');
  
    Route::post('/follow/{user}', [FollowerController::class, 'followUnfollow'])
        ->name('follow');

    Route::post('/clap/{post}',[ClapController::class,'clap'])
    ->name('clap');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{user:username}', [PublicProfileController::class, 'show'])
    ->name('profile.show');
