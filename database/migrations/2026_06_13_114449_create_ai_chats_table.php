<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chats', function (Blueprint $table) {
            $table->id('id_ai_chat');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('session_id')->nullable();
            $table->text('pertanyaan');
            $table->longText('jawaban')->nullable();
            $table->string('provider')->default('gemini');
            $table->string('model')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chats');
    }
};