<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeLog extends Model
{
    protected $connection = 'statistic';
    protected $table = 'recharge_logs';
    public $timestamps = false;

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'statistic_back.channel_user',
            'channel_id',
            'user_id'
        )->usingDatabase('statistic_back');
    }

}
