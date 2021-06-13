<?php

namespace App\Observers;

use App\Jobs\TranslateSlug;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver {
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        //XSS过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        //摘要生成
        $topic->excerpt = make_excerpt($topic->excerpt);

    }

    public function saved(Topic $topic)
    {
        //slug字段无内容,则用翻译api进行翻译title
        if (!$topic->slug) {
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic)
    {
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
