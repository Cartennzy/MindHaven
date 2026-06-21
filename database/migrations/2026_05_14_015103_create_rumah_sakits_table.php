<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rumah_sakits', function (Blueprint $table) {
            $table->id('id_rumahsakit');

            $table->string('nama_rumahsakit');
            $table->text('alamat');
            $table->string('no_telepon');

            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('website')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rumah_sakits');
    }
};