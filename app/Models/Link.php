<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model {
    use HasFactory;

    protected $fillable = ['title', 'link', 'thumb_image'];

    public $cache_key = 'larabbs_links';
    protected $cache_expire = 1440 * 60;

    public function setThumbImageAttribute($path)
    {
        if (!\Illuminate\Support\Str::startsWith($path, 'http') || !\Illuminate\Support\Str::startsWith($path, 'https')) {
            $path = config('app.url') . "/uploads/images/links/$path";
        }
        $this->attributes['thumb_image'] = $path;

    }

    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire, function () {
            return $this->all();
        });


    }
}
