<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaan_tes', function (Blueprint $table) {
            $table->id('id_pertanyaan');
            
            $table->foreignId('id_instrumen')
                ->constrained('instrumen_tes', 'id_instrumen')
                ->onDelete('cascade');

            $table->text('teks_pertanyaan'); // Mengunci nama kolom teks_pertanyaan
            $table->json('pilihan_opsi')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_tes');
    }
};