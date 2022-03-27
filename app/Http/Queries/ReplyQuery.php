<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/3/16
 * Time: 23:29
 */

namespace App\Http\Queries;


use App\Models\Reply;
use Spatie\QueryBuilder\QueryBuilder;

class ReplyQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Reply::query());

        $this->allowedIncludes('user', 'topic','topic.user');
    }

}