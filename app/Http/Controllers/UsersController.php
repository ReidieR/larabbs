<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    // 个人中心
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 编辑资料
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    // 更新资料
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $data = $request->all();
        // dd($data);  
        if ($request->avator) {
            // dd(123);
            $result = $uploader->save($request->avator, 'avators', $user->id);
            // dd($result);
            if ($result) {
                $data['avator'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');
    }
}
