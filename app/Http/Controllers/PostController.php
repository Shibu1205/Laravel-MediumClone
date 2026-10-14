<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $query = Post::with(['user','media'])
        ->where('published_at','<=',now())
        ->withCount('claps')
        ->latest();

        $posts = $query->simplePaginate(5);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::get();

        return view('posts.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostUpdateRequest $request)
    {
        //

        $data = $request->validated();

        //$image = $data['image'] ?? null;
        //unset($data['image']);
        $data['user_id'] = Auth::id();
        
        //$imagePath = $image->store('posts','public');
        //$data['image'] = $imagePath;

        $post = Post::create($data);

        $post->addMediaFromRequest('image')->toMediaCollection();

        return redirect()->route('dashboard')->with('success', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        //
        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function readTime(Post $post, $wordsPerMinute = 100)
    {
        $wordcount = str_word_count(strip_tags($post->content));
        $minutes = ceil($wordcount / $wordsPerMinute);

        return $minutes;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !==Auth::id()) {
            abort(403);

        }
        $categories = Category::get();
        return view('posts.edit',[
            'post'=> $post,
            'categories'=> $categories,
        ]);
        return view('posts.edit',[
            'post'=> $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostCreateRequest $request, Post $post)
    {
        if ($post->user_id !==Auth::id()) {
            abort(403);

        }
        $data= $request->validated();
        $post->update($data);

        if($data['image'] ?? false){
            $post->addMediaFromRequest('image')
            ->toMediaCollection('');
        }
        return redirect()->route('myPosts')->with('success','');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !==Auth::id()) {
            abort(403);

        }
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function category(Category $category)
    {
        $query=$category->posts()
        ->where('published_at','<=',now())
        ->with(['user','media'])
        ->withCount('claps')
        ->latest();

          $posts = $query->simplePaginate(5);

        return view('posts.index',[
           'posts'=>$posts,
        ]);
    }

    public function myPosts(Category $category)
    {
        $user = Auth::user();
        $posts=$category->posts()
        ->with(['user','media'])
        ->withCount('claps')
        ->latest()->simplePaginate(5);

        return view('posts.index',[
           'posts'=>$posts,
        ]);
    }
}
