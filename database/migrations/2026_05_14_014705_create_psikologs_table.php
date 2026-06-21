<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psikologs', function (Blueprint $table) {
            $table->id('id_psikolog');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');

            $table->string('no_telepon');
            $table->date('tanggal_lahir');

            $table->enum('jenis_kelamin', [
                'laki-laki',
                'perempuan',
            ]);

            $table->text('alamat');
            $table->string('foto_profil')->nullable();

            $table->string('spesialisasi');
            $table->integer('pengalaman')->default(0);
            $table->decimal('biaya_konsultasi', 10, 2)->default(0);

            $table->text('pendidikan')->nullable();
            $table->string('dokumen_pendidikan')->nullable();

            $table->string('str_psikolog')->nullable();
            $table->string('dokumen_str_psikolog')->nullable();

            $table->string('sip_psikolog')->nullable();
            $table->string('dokumen_sip_psikolog')->nullable();

            $table->string('jadwal_praktik')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Diisi Psikolog Setelah Akun Diverifikasi
            |--------------------------------------------------------------------------
            */

            $table->text('bio')->nullable();
            $table->string('metode_konsultasi')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Dokumen Verifikasi Tambahan / CV
            |--------------------------------------------------------------------------
            */

            $table->string('dokumen_verifikasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            $table->boolean('is_active')->default(false);

            $table->enum('status_verifikasi', [
                'pending',
                'verified',
                'rejected',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psikologs');
    }
};