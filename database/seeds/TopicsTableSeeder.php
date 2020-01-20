<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 所有用户id
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有分类id
        $category_ids = Category::all()->pluck('id')->toArray();

        // Faker 实例
        $faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)
            ->times(100)
            ->make()
            ->each(function ($topic, $index)
            use ($user_ids, $category_ids, $faker) {
                // 从用户id数组随机获取一个数并赋值
                $topic->user_id = $faker->randomElement($user_ids);
                // 话题同上
                $topic->category_id = $faker->randomElement($category_ids);
            });
        // 将数据集合转化为数组
        Topic::insert($topics->toArray());
    }
}
