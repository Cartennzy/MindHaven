<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rujukan_psikiaters', function (Blueprint $table) {
            $table->id('id_rujukan');

            $table->string('nomor_rujukan')->nullable()->unique();

            $table->foreignId('id_konsultasi')
                ->constrained('konsultasis', 'id_konsultasi')
                ->onDelete('cascade');

            $table->foreignId('id_psikolog')
                ->constrained('psikologs', 'id_psikolog')
                ->onDelete('cascade');

            $table->foreignId('id_pasien')
                ->constrained('pasiens', 'id_pasien')
                ->onDelete('cascade');

            $table->foreignId('id_psikiater')
                ->nullable()
                ->constrained('psikiaters', 'id_psikiater')
                ->nullOnDelete();

            $table->text('diagnosa_awal')->nullable();

            $table->text('alasan_rujukan');

            $table->text('catatan_psikolog')->nullable();

            $table->enum('status', [
                'menunggu',
                'diproses',
                'selesai',
                'dibatalkan',
            ])->default('menunggu');

            $table->date('tanggal_rujukan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rujukan_psikiaters');
    }
};