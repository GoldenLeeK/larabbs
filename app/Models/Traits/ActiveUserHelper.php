<?php


namespace App\Models\Traits;


use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait ActiveUserHelper {
    protected $users = [];//用于存放临时用户数据

    //配置信息
    protected $topic_weight = 4;  //话题权重
    protected $reply_weight = 1;  //回复权重
    protected $pass_days = 7;//过去多少天发表内容
    protected $user_number = 6;//用户数量

    //缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire = 60 * 60;

    public function getActiveUsers()
    {
        //尝试从缓存读users数据，如果有直接返回，如果没有重新获取并进行缓存
        return Cache::remember($this->cache_key, $this->cache_expire, function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        $active_users = $this->calculateActiveUsers();

        $this->cacheActiveUsers($active_users);
    }

    private function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        //数组按得分进行排序
        $users = Arr::sort($this->users, function ($user) {
            return $user['score'];
        });
        //进行倒序
        $users = array_reverse($users);
        //截取想要的数量
        $users = array_splice($users, 0, $this->user_number);

        $active_users = collect();

        foreach ($users as $user) {
            //判断用户是否存在
            $user = $this->find($user['user_id']);
            if ($user) {
                $active_users->push($user);
            }
        }

        return $active_users;
    }

    private function calculateTopicScore()
    {
        //找出限定时间范围内发表过话题的用户
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) AS topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        //计算用户权重
        foreach ($topic_users as $topic) {
            $this->users[$topic->user_id]['user_id'] = $topic->user_id;
            $this->users[$topic->user_id]['score']   = $topic->topic_count * $this->topic_weight;
        }

    }

    private function calculateReplyScore()
    {
        //找出限定时间范围内发表过评论的用户
        $reply_users = Reply::query()->select(DB::raw('user_id,count(*) AS reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        //计算用户权重
        foreach ($reply_users as $reply) {
            $reply_score = $reply->reply_count * $this->reply_weight;
            if (isset($this->users[$reply->user_id])) {
                $this->users[$reply->user_id]['score'] += $reply_score;
            } else {
                $this->users[$reply->user_id]['user_id'] = $reply->user_id;
                $this->users[$reply->user_id]['score']   = $reply_score;
            }
        }
    }

    private function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire);
    }
}
