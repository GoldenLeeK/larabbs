<?php


namespace App\Models\Traits;


use App\Models\Follower;
use App\Models\User;

trait FollowerUserHelper {
    //关注
    public function follow(User $user)
    {
        Follower::firstOrCreate(['user_id' => $user->id, 'follower_id' => $this->id]);
    }

    //取消关注
    public function unfollow(User $user)
    {
        Follower::where(['user_id' => $user->id, 'follower_id' => $this->id])->delete();
    }

    //是否关注
    public function followed(User $user)
    {
        $is_followed = Follower::where(['user_id' => $user->id, 'follower_id' => $this->id])->get();
        return !$is_followed->isEmpty();
    }
}
