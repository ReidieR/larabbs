<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Queries\TopicQuery;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    // 话题列表
    public function index(Request $request, TopicQuery $query)
    {

        $topics = $query->paginate();
        return TopicResource::collection($topics);
    }

    // 用户发布的话题
    public function userIndex(Request $request, User $user, TopicQuery $query)
    {
        $topics = $user->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());

        return new TopicResource($topic);
    }

    public function destory(Topic $topic)
    {
        $this->authorize('destory', $topic);
        $topic->delete();
        return response(null, 204);
    }

    public function show($topicId, TopicQuery $query)
    {
        $topic = $query->findOrfail($topicId);
        return new TopicResource($topic);
    }
}
