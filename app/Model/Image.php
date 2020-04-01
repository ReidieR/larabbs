<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['type', 'path'];

    // 关联用户模型
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
