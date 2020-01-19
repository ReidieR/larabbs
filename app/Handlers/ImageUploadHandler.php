<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use Image;

class ImageUploadHandler
{
  // 只允许以下格式的图片上传
  protected $allow_ext = ['png', 'gif', 'jpg', 'jpeg'];

  public function save($file, $folder, $file_prefix, $max_width = false)
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

    // 如果超过限制的图片大小则进行裁剪
    if ($max_width && $extension != 'gif') {
      // 此类中封装的函数用于图片裁剪
      $this->reduceSize($upload_path . '/' . $file_name, $max_width);
    }
    return [
      'path' => config('app.url') . "/$folder_name/$file_name"
    ];
  }

  // 图片裁剪函数
  public function reduceSize($file_path, $max_width)
  {
    // 先实例化，传参是文件的磁盘物理路径
    $image = Image::make($file_path);

    // 进行大小调整操作
    $image->resize($max_width, null, function ($constraint) {
      // 设定高度时宽度的等比例缩放
      $constraint->aspectRatio();
      // 防止裁图时图片尺寸变大
      $constraint->upsize();
    });
    // 对图片修改后进行保存
    $image->save();
  }
}
