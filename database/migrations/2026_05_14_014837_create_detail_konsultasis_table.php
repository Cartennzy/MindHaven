<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_konsultasis', function (Blueprint $table) {
            $table->id('id_detailkonsultasi');

            $table->foreignId('id_konsultasi')
                ->unique()
                ->constrained('konsultasis', 'id_konsultasi')
                ->onDelete('cascade');

            $table->text('keluhan_utama');
            $table->text('riwayat_hidup')->nullable();
            $table->text('hasil_observasi')->nullable();
            $table->text('diagnosis_awal')->nullable();
            $table->text('rencana_penanganan')->nullable();
            $table->longText('laporan_asesmen_psikologis')->nullable();

            $table->boolean('perlu_rujukan')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_konsultasis');
    }
};