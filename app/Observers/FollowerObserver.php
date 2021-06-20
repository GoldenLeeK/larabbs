<?php

namespace App\Observers;

use App\Models\Follower;
use App\Notifications\Followed;
use App\Models\User;

class FollowerObserver {

    public function created(Follower $follower_model)
    {
        if (!app()->runningInConsole()) {
            //通知被关注者
            $user     = User::find($follower_model->user_id);
            $follower = User::find($follower_model->follower_id);
            $user->notify(new Followed($follower));
        }
    }

}
