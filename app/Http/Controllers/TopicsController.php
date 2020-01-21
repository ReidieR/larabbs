<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Topic;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show']]);
	}

	public function index(Request $request, Topic $topic)
	{

		$topics = $topic->withOrder($request->order)
			->with('user', 'category')		// 预加载防止n+1问题
			->paginate(10);
		return view('topics.index', compact('topics'));
	}

	public function show(Topic $topic)
	{
		return view('topics.show', compact('topic'));
	}

	public function create(Topic $topic)
	{
		$categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
		$topic->fill($request->all());
		$topic->user_id = Auth::id();
		$topic->save();
		return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
	}

	public function edit(Topic $topic)
	{
		$this->authorize('update', $topic);
		$categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('success', '更新成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功');
	}

	// 图片上传
	public function uploadImage(Request $request, ImageUploadHandler $uploader)
	{
		// 初始化返回数据，默认失败
		$data = [
			'success' => false,
			'mag' => '上传失败',
			'file_path' => ''
		];
		// 判断是否有上传文件
		if ($file = $request->upload_file) {
			// 保存图片到本地
			$result = $uploader->save($file, 'topics', Auth::id(), 1024);
			// 图片保存成功
			if ($result) {
				$data = [
					'success' => true,
					'msg' => '上传成功',
					'file_path' => $result['path']
				];
			}
		}
		return $data;
	}
}
