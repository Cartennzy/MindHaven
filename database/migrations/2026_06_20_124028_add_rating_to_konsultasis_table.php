<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->integer('skor_rating')->nullable()->after('status');
        });

        Schema::table('konsultasis', function (Blueprint $table) {
            $table->text('catatan_ulasan')->nullable()->after('skor_rating');
        });
    }

    public function down(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn(['skor_rating', 'catatan_ulasan']);
        });
    }
};