<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    //
    public function followUnfollow(User $user){
        $user->followers()->toggle(Auth::id());
        return response()->json([
            'followersCount'=>$user->followers()->count()
            ]);
    }
}
