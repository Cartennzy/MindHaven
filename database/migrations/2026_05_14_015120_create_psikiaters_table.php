<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psikiaters', function (Blueprint $table) {
            $table->id('id_psikiater');

            $table->foreignId('id_rumahsakit')
                ->constrained('rumah_sakits', 'id_rumahsakit')
                ->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('spesialisasi');
            $table->string('no_telepon');
            $table->string('jadwal_praktik')->nullable();
            $table->string('str_psikiater')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('pengalaman')->default(0);
            $table->string('foto_profil')->nullable();
            $table->text('alamat_praktik')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psikiaters');
    }
};