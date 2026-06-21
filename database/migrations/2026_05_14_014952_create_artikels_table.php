<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id('id_artikel');

            $table->foreignId('id_admin')
                ->constrained('admins', 'id_admin')
                ->onDelete('cascade');

            $table->string('kategori')->nullable();

            $table->string('judul');

            $table->longText('konten');

            $table->string('gambar')->nullable();

            $table->string('penulis')->nullable();

            $table->string('sumber_artikel')->nullable();

            $table->date('tanggal_publish')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};