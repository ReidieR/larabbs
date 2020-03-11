<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $cache_key1 = 'larabbs_last_actived_at';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // 获取当前日期
        $date = Carbon::now()->toDateString();

        // redis哈希表命名，如：larabbs_last_actived_at_2020-3-10
        $hash = $this->cache_key1 . $date;

        // 字段名称
        $field = $this->field_prefix . $this->id;

        // dd(Redis::hGetAll($hash));
        // 当前时间
        $now = Carbon::now()->toDateTimeString();

        // 数据写入redis，字段存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // 获取昨天的日期
        $yesterday_date = Carbon::yesterday()->toDateString();

        // redis 哈希表命名 例如：larabbs_last_actived_at_2020-3-11
        $hash = $this->cache_key1 . $this->yesterday_date;

        // 从redis中获取所有数据
        $data = Redis::hGetAll($hash);

        // 遍历并同步保存到数据库中
        foreach ($data as $user_id => $value) {
            // 将 `user_1` 变为 1 
            $user_id = str_replace($this->field_prefix, '', $user_id);

            // 只有当用户存在才将数据存入数据表
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $value;
                $user->save();
            }
        }

        // 以数据库存储为中心，同步后删除redis中的数据
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();

        // Redis 哈希表名
        $hash = $this->cache_key1 . $date;

        // 字段名称
        $field = $this->field_prefix . $this->id;

        // 三元运算符优先选择redis中的数据，否则使用数据库中的数据
        $datetime = Redis::hGet($hash, $field) ?: $value;

        // 如果存在返回对应的carbon实体
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否则使用注册时间
            return $this->created_at;
        }
    }
}
