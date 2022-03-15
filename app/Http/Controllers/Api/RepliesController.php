<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->body = $request->body;


        $reply->topic()->associate($topic);
        $reply->user()->associate($request->user());
        $reply->save();



        return new ReplyResource($reply);


    }
}
