<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi_messages', function (Blueprint $table) {
            $table->id('id_message');

            $table->foreignId('id_konsultasi')
                ->constrained('konsultasis', 'id_konsultasi')
                ->onDelete('cascade');

            $table->enum('sender_role', ['pasien', 'psikolog']);
            $table->unsignedBigInteger('sender_id');
            $table->text('pesan');
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            $table->index(['id_konsultasi', 'sender_role', 'sender_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi_messages');
    }
};