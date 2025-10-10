<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $connection = 'statistic';
    protected $table = 'channels';

    protected $fillable = [
        'channel_code',
        'name',
        'secret_key',
        'status',
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'channel_user', 'channel_id', 'user_id');
    }
}
