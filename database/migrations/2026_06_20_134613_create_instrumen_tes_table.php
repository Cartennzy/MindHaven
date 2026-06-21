<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrumen_tes', function (Blueprint $table) {
            $table->id('id_instrumen');
            $table->string('nama_tes'); // Contoh: Stres, Burnout, Gangguan Kecemasan, Depresi
            $table->string('slug')->unique(); // Untuk URL: stres, burnout, kecemasan, depresi
            $table->text('deskripsi')->nullable();
            
            /*
            | Column 'rules_skor' diisi format JSON untuk kriteria hasil, contoh:
            | [
            |   {"status": "Normal/Rendah", "min": 0, "max": 7, "warna": "emerald"},
            |   {"status": "Sedang", "min": 8, "max": 14, "warna": "amber"},
            |   {"status": "Parah/Tinggi", "min": 15, "max": 21, "warna": "rose"}
            | ]
            */
            $table->json('rules_skor')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumen_tes');
    }
};