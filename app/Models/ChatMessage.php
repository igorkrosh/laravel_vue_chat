<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    public function Room()
    {
        return $this->hasOne('App\Models\ChatRoom', 'id', 'chat_room_id');
    }

    public function User()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
