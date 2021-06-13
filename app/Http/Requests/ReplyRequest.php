<?php

namespace App\Http\Requests;

class ReplyRequest extends Request {
    public function rules()
    {
        return [
            'body' => 'required|min:2',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '评论内容不得为空',
            'body.min'      => '评论内容不得小于两位',
        ];
    }
}
