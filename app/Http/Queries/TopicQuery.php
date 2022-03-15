<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/3/14
 * Time: 22:10
 */

namespace App\Http\Queries;


use App\Models\Topic;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopicQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Topic::query());

        $this->allowedIncludes('user', 'category')
             ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied')
            ]);
    }

}