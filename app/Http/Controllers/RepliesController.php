<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ReplyRequest;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(ReplyRequest $request, Reply $reply)
    {
        $reply->user_id  = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->body     = $request->body;
        $reply->save();
        return redirect()->to($reply->topic->link())->with('success', '评论成功!');
    }


    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();
        return redirect()->to($reply->topic->link())->with('success', '删除评论成功');
    }
}
