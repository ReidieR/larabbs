<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];
    public $cache_key = 'larabbs_links';
    public $cache_expire_in_seconds = 1440 * 60;

    public function getAllCached()
    {
        // 尝试从缓冲 cache_key 获取数据，如果能得到则直接返回
        //  否则运行匿名函数中的代码获取links表中的数据，并存入缓存中并返回
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
            return $this->all();
        });
    }
}
