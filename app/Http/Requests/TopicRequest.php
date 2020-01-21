<?php

namespace App\Http\Requests;

class TopicRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
                // CREATE
            case 'POST':
            case 'PUT':
            case 'PATCH': {
                    return [
                        'title' => 'required|min:2',
                        'body' => 'required|min:10',
                        'category_id' => 'required|numeric'
                    ];
                }
            case 'GET':
            case 'DELETE':
            default: {
                    return [];
                }
        }
    }

    public function messages()
    {
        return [
            'title.min' => '标题最少为三个字符',
            'body.min' => '内容最少为10个字符',
            'body.required' => '内容 不能为空'
        ];
    }
}
