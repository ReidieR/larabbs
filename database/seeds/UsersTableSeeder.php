<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        // 头像假数据
        $avatars = [
            'https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/Lhd1SHqu86.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/LOnMrqbHJn.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/xAuDMxteQy.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/NDnzMutoxX.png',
        ];
        // 生成数据集合
        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index) use ($faker, $avatars) {
                // 从头像中随机出一个并赋值
                $user->avatar = $faker->randomElement($avatars);
            });
        // 让隐藏字段可见，并将数据集合转为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();
        // 插入数据库中
        User::insert($user_array);
        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Reid';
        $user->avatar = 'http://larabbs.test/upload/images/avatars/202001/19/1_1579439745_vzNbhRiByf.jpg';
        $user->email = 'weqwe@1414.com';
        $user->assignRole('Founder');
        $user->save();
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
