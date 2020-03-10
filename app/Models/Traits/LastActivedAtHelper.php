<?php

namespace App\Models\Traits;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $cache_key = 'larabbs_last_actived_at';
    protected $filed_prefix = 'user_';

    public function recordLastActivedAt()
    {
    }
}
