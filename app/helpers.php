<?php

function route_class()
{
    return str_replace('.', '-', \Illuminate\Support\Facades\Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

//摘要截取
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', '', strip_tags($value)));
    return \Illuminate\Support\Str::limit($excerpt, $length);
}

//模型链接
function model_link($title, $model, $prefix = '')
{
    //获取模型蛇形复数命名
    $model_name = model_plural_name($model);

    $prefix = $prefix ? "/$prefix/" : '/';

    //拼接url
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    return sprintf("<a href='%s' target='_blank'>%s</a>", $url, $title);
}

//后台模型链接
function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_plural_name($model)
{
    //获取完整类型 如App\Model\User
    $full_class_name = get_class($model);

    //获得基础类名
    $class_name = class_basename($full_class_name);

    //获得蛇形命名
    $snake_class_name = \Illuminate\Support\Str::snake($class_name);

    //返回复数形式
    return \Illuminate\Support\Str::plural($snake_class_name);

}

