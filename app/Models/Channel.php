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
        'name',
        'secret_key',
        'status',
    ];

    public $timestamps = true; 
}
