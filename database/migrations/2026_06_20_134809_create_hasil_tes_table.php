<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id('id_hasil');

            // Relasi ke tabel pasien (opsional nullable jika diizinkan bagi non-user/tamu anonim)
            $table->foreignId('id_pasien')
                ->nullable()
                ->constrained('pasiens', 'id_pasien')
                ->onDelete('cascade');

            // Relasi ke kategori instrumen yang diikuti
            $table->foreignId('id_instrumen')
                ->constrained('instrumen_tes', 'id_instrumen')
                ->onDelete('cascade');

            $table->integer('total_skor'); // Akumulasi nilai poin jawaban
            $table->string('kesimpulan_status'); // Hasil rules: Normal, Sedang, Parah
            $table->text('catatan_saran')->nullable(); // Rekomendasi tindakan medis awal
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_tes');
    }
};