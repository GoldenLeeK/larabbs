<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function follow(User $user)
    {
        if ($user->id == Auth::id()) {
            return back()->with('info', '不能关注自己');
        }
        Auth::user()->follow($user);
        return back()->with('success', '关注成功');

    }

    public function unfollow(User $user)
    {
        if ($user->id == Auth::id()) {
            return back()->with('info', '不能取消关注自己');
        }
        Auth::user()->unfollow($user);
        return back()->with('success', '取消关注成功');
    }


}
