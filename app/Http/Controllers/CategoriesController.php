<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category, User $user)
    {
        // 读取分类id关联的话题
        $topics = Topic::where('category_id', $category->id)->paginate(20);

        // 活跃用户列表
        $active_users = $user->getActiveUsers();

        // 传参到模板中
        return view('topics.index', compact('topics', 'category', 'active_users'));
    }
}
