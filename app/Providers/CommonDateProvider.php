<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class CommonDateProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(User $user, Link $link)
    {
        // 活跃用户数据共享
        $active_users = $user->getActiveUsers();
        view()->share('active_users', $active_users);
        // 链接数据共享
        $links = $link->getAllCached();
        view()->share('links', $links);
    }
}
