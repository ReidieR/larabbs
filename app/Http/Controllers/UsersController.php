<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    // 个人中心
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 编辑资料
    public function edit()
    {
    }
    // 更新资料
    public function update()
    {
    }
}
