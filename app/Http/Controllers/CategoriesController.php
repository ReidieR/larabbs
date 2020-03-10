<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;


class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        // 读取分类id关联的话题
        $topics = Topic::where('category_id', $category->id)->paginate(20);

        // 传参到模板中
        return view('topics.index', compact('topics', 'category'));
    }
}
