<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChat extends Model
{
    protected $table = 'ai_chats';

    protected $primaryKey = 'id_ai_chat';

    protected $fillable = [
        'id_ai_chat_session',
        'user_id',
        'session_id',
        'pertanyaan',
        'jawaban',
        'provider',
        'model',
    ];

    public function chatSession()
    {
        return $this->belongsTo(AiChatSession::class, 'id_ai_chat_session', 'id_ai_chat_session');
    }
}