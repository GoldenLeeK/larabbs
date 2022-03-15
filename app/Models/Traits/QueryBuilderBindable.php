<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/3/14
 * Time: 22:07
 */

namespace App\Models\Traits;


trait QueryBuilderBindable
{
    public function resolveRouteBinding($value)
    {
        $queryClass = property_exists($this, 'queryClass')
            ? $this->queryClass
            : '\\App\\Http\\Queries\\' . class_basename(self::class) . 'Query';

        if (!class_exists($queryClass)) {
            return parent::resolveRouteBinding($value);
        }

        return (new $queryClass($this))
            ->where($this->getRouteKeyName(), $value)
            ->first();


    }

}