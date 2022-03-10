<?php

namespace App\Models;

use App\Models\Traits\ActiveUserHelper;
use App\Models\Traits\FollowerUserHelper;
use App\Models\Traits\LastActivedAtHelper;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContracts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmailContracts, JWTSubject
{
    use HasFactory, MustVerifyEmailTrait, HasRoles;

    use Notifiable {
        notify as  protected laravelNotify;
    }

    use ActiveUserHelper, LastActivedAtHelper, FollowerUserHelper;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'introduction',
        'avatar',
        'phone',
        'wechat_unionid',
        'wechat_openid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        if (strlen($value) != 60) {
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        if (!\Illuminate\Support\Str::startsWith($path, 'http') && !\Illuminate\Support\Str::startsWith($path, 'https')) {
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }
        $this->attributes['avatar'] = $path;

    }

    public function following()
    {
        return $this->belongsToMany(self::class, 'followers', 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'user_id', 'follower_id');
    }


}
