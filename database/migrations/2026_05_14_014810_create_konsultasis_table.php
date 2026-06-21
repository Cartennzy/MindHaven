<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id('id_konsultasi');

            $table->foreignId('id_pasien')
                ->constrained('pasiens', 'id_pasien')
                ->onDelete('cascade');

            $table->foreignId('id_psikolog')
                ->constrained('psikologs', 'id_psikolog')
                ->onDelete('cascade');

            $table->string('topik_konsultasi');
            $table->text('keluhan');
            $table->date('tanggal_konsultasi');
            $table->time('jam_konsultasi');

            $table->enum('metode_konsultasi', [
                'chat',
                'video_call',
                'temu_janji',
            ])->default('chat');

            $table->decimal('harga', 10, 2);

            $table->enum('status', [
                'pending',
                'diproses',
                'selesai',
                'dibatalkan'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};