<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Channel;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 1;
    public const ROLE_ADMIN = 2;
    public const ROLE_USER = 3;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    // 获取普通用户可用渠道
    public function availableChannels()
    {
        if ($this->id === 1) {
            return Channel::all();
        }

        return $this->channels;
    }

    public function canAccessPanel(Panel $panel): bool
    {

        if (app()->environment('local')) {
            return true;
        }
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);

    }
}

