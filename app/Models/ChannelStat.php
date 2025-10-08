<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelStat extends Model
{
    protected $connection = 'statistic';
    protected $table = 'channel_stats';
    public $timestamps = false;
}
