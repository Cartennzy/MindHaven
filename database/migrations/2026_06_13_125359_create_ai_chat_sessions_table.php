<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id('id_ai_chat_session');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->string('judul')->default('Chat baru');
            $table->timestamps();
        });

        Schema::table('ai_chats', function (Blueprint $table) {
            $table->foreignId('id_ai_chat_session')
                ->nullable()
                ->after('id_ai_chat')
                ->constrained('ai_chat_sessions', 'id_ai_chat_session')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ai_chats', function (Blueprint $table) {
            $table->dropForeign(['id_ai_chat_session']);
            $table->dropColumn('id_ai_chat_session');
        });

        Schema::dropIfExists('ai_chat_sessions');
    }
};