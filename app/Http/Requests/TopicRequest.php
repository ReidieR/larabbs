<?php

namespace App\Http\Requests;

class TopicRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
                // CREATE
            case 'POST':
                return [
                    'title' => 'required|string',
                    'body' => 'required|string',
                    'category_id' => 'required|exists:categories,id',
                ];
                break;
            case 'PATCH':
                return [
                    'title' => 'string',
                    'body' => 'string',
                    'category_id' => 'exists:categories,id',
                ];

                break;
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
