<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id('id_pasien');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('no_telepon');
            $table->date('tanggal_lahir');

            $table->enum('jenis_kelamin', [
                'laki-laki',
                'perempuan'
            ]);

            $table->text('alamat');
            $table->string('foto_profil')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};