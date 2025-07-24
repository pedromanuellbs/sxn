<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotConfig extends Model
{
    // If table name doesn't follow Laravel's plural convention
    protected $table = 'bot_config';

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'tone',
        'status',
    ];
}
