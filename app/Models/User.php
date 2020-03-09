<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use MustVerifyEmailTrait, HasRoles;
    use Notifiable {
        notify as protected laravelNotify;
    }

    // 消息通知 
    public function notify($instance)
    {
        // 如果要通过的人是当前用户，就不必要了
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送email或者其他的都pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }
        $this->laravelNotify($instance);
    }
    // 消息通知已读
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 关联话题模型
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    // 权限判断
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
    // 关联回复
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 密码模型修改器
    public function setPasswordAttribute($value)
    {
        if (strlen($value) != 60) {
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    // 头像修改器
    public function setAvatarAttribute($path)
    {
        // 如果不是http开头的字符串，就是从后台上传的，需要补全url
        if (!\Str::startsWith($path, 'http')) {
            // 拼接完整的url
            $path = config('app.url') . '/upload/images/avatars/' . $path;
        }
        $this->attributes['avatar'] = $path;
    }
}
