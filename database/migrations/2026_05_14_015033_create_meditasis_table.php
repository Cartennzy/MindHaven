<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meditasis', function (Blueprint $table) {
            $table->id('id_meditasi');

            $table->foreignId('id_admin')
                ->constrained('admins', 'id_admin')
                ->onDelete('cascade');

            $table->string('kategori');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('audio')->nullable();
            $table->integer('durasi');
            $table->enum('status', ['draft', 'published'])->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meditasis');
    }
};