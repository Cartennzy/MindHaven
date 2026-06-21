<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    protected $table = 'ai_chat_sessions';

    protected $primaryKey = 'id_ai_chat_session';

    protected $fillable = [
        'user_id',
        'session_id',
        'judul',
    ];

    public function chats()
    {
        return $this->hasMany(AiChat::class, 'id_ai_chat_session', 'id_ai_chat_session');
    }
}