<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');

            $table->foreignId('id_konsultasi')
                ->unique()
                ->constrained('konsultasis', 'id_konsultasi')
                ->onDelete('cascade');

            $table->string('id_order')->unique();
            $table->string('snap_token')->nullable();
            $table->string('snap_redirect_url')->nullable();
            $table->string('metode_pembayaran')->nullable();

            $table->decimal('biaya_admin', 10, 2)->default(5000);
            $table->decimal('biaya_psikolog', 10, 2)->default(0);
            $table->decimal('total_pembayaran', 10, 2)->default(0);

            $table->string('bukti_pembayaran')->nullable();

            $table->enum('status_pembayaran', [
                'pending',
                'diterima',
                'gagal',
                'expired'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};