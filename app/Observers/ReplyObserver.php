<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver {
    public function creating(Reply $reply)
    {
        $reply->body = clean($reply->body, 'user_topic_body');
    }

    public function created(Reply $reply)
    {
        //命令行执行不通知
        if (! app()->runningInConsole()){
            $reply->topic->reply_count = $reply->topic->updateReplyCount();
            //通知作者
            $reply->topic->user->notify(new TopicReplied($reply));
        }
    }

    public function deleted(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->updateReplyCount();
    }


}
