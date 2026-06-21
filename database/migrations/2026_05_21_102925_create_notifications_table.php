<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notifikasi');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('psikolog_id')
                ->nullable()
                ->constrained('psikologs', 'id_psikolog')
                ->nullOnDelete();

            $table->foreignId('id_konsultasi')
                ->nullable()
                ->constrained('konsultasis', 'id_konsultasi')
                ->nullOnDelete();

            $table->foreignId('id_rujukan')
                ->nullable()
                ->constrained('rujukan_psikiaters', 'id_rujukan')
                ->nullOnDelete();

            $table->string('judul');
            $table->text('pesan');

            $table->enum('tipe', [
                'konsultasi_baru',
                'hasil_konsultasi',
                'rujukan_psikiater',
                'psikolog_baru',
                'verifikasi_psikolog',
                'pembayaran_baru'
            ])->default('konsultasi_baru');

            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};