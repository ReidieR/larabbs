<?php

namespace App\Models\Traits;

use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait ActiveUserHelper
{

    // 用于存放临时数据
    protected $users = [];

    // 配置信息
    protected $topic_weight = 5;  // 话题权重
    protected $reply_weight = 1;  // 回复权重
    protected $pass_days = 7;      // 多少天内发表内容
    protected $user_num = 6;      // 取出多少用户

    // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_secondes = 65 * 60;

    public function getActiveUsers()
    {
        // 尝试从缓存中读取 cache_key 中对应的用户数据，如果有则直接返回数据
        // 如果没有数据则调用匿名函数中的代码获取活跃用，同时存入缓存中并返回
        return Cache::remember($this->cache_key, $this->cache_expire_in_secondes, function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        // 获取活跃用户
        $active_users = $this->calculateActiveUsers();
        // 存入缓存
        $this->cacheActiveUsers($active_users);
    }

    private function calculateActiveUsers()
    {
        $this->calculateTopicScores();   // 计算话题分数
        $this->calculateReplyScores();   // 计算回复分数

        // 数组按照分数排序
        $users = Arr::sort($this->users, function ($user) {
            return $user['score'];
        });

        // 将数组倒叙排列，key 保持不变
        $users = array_reverse($users, true);

        // 获取我们想要的数量
        $users = array_slice($users, 0, $this->user_num, true);

        // 新建一个空的集合
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            // 寻找用户是否存在
            $user = $this->find($user_id);

            // 如果用户存在，将用户放在集合的末尾
            if ($user) {
                $active_users->push($user);
            }
        }

        // 返回数据
        return $active_users;
    }

    private function calculateTopicScores()
    {
        // 从话题表中取出限定时间内 (pass_days)，有发表过话题的用户
        // 同时取出用户在此时段内发表的话题数量
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')->get();

        // 根据话题数量计算分数
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this
                ->topic_weight;
        }
    }

    private function calculateReplyScores()
    {
        // 从回复表中取出限定时间内 (pass_days)，有发表过话题的用户
        // 同时取出用户在此时段内发表的回复数量
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')->get();

        // 根据回复数量计算分数
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    private function cacheActiveUsers($active_users)
    {
        // 存入缓存
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_secondes);
    }
}
