<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClapController extends Controller
{
    public function clap(Post $post): JsonResponse
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(401);
        }

        if ($user->hasClapped($post)) {
            $user->claps()->where('post_id', $post->getKey())->delete();
        } else {
            $user->claps()->create([
                'post_id' => $post->getKey(),
            ]);
        }

        return response()->json([
            'clapsCount' => $post->claps()->count(),
        ]);
    }
}
 