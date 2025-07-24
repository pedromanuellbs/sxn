<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_id', 'from', 'to', 'text'];

    public function telegramUser()
    {
        return $this->belongsTo(TelegramUser::class, 'chat_id', 'chat_id');
    }
}
