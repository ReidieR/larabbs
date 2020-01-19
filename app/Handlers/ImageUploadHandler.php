<?php

namespace App\Handlers;

use Illuminate\Support\Str;

class ImageUploadHandler
{
  // 只允许以下格式的图片上传
  protected $allow_ext = ['png', 'gif', 'jpg', 'jpeg'];

  public function save($file, $folder, $file_prefix)
  {
    // 构建储存文件的规则，eg:upload/images/avators/202001/19/
    // 文件夹切割能让查找效率更高
    $folder_name = "upload/images/$folder/" . date('Ym/d', time());
    // 文件具体储存的物理路径，`public_path` 获取得是 `public` 文件夹中的路径
    // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/202001/19/
    $upload_path = public_path() . '/' . $folder_name;
    // 获取文件的后缀名，因图片从剪贴板中粘贴时后缀名为空，所以保持后缀一直存在
    $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
    // 拼接文件名称，加前缀是为了增加辨识度，前缀可以是相关数据模型的id
    // 值如：1_1493521050_7BVc9v9ujP.png
    $file_name = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
    // 如果上传的不是图片直接返回
    if (!in_array($extension, $this->allow_ext)) {
      return false;
    }
    // 将图片移动到目标文件夹
    $file->move($upload_path, $file_name);

    return [
      'path' => config('app.url') . "/$folder_name/$file_name"
    ];
  }
}
