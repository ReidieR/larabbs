<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug'
    ];

    // 关联分类模型
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 关联用户模型
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
    }
    public function scopeRecent($query)
    {
        // 按照创建时间进行排序
        return $query->orderBy('created_at', 'desc');
    }
    public function scopeRecentReplied($query)
    {
        // 当话题有新回复的时候，会更新模型的replay_count属性，
        // 此时会触发框架对数据模型的 updated_at 时间戳进行更新
        return $query->orderBy('updated_at', 'desc');
    }
}
