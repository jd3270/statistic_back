<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Channel;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 跨库多对多关系
    public function channels()
    {
        return $this->belongsToMany(
            Channel::class,
            'statistic_back.channel_user', // pivot 表所在数据库
            'user_id',
            'channel_id'
        );
    }

    // 获取普通用户可用渠道
    public function availableChannels()
    {
        if ($this->id === 1) {
            return Channel::all();
        }

        return $this->channels;
    }
}

