<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class SendMoodCheckin extends Command
{
    protected $signature = 'mood:send-checkin';
    protected $description = 'Mengirimkan pesan broadcast check-in mood harian ke WhatsApp pasien';

    public function handle()
    {
        // Mengambil user pasien yang memiliki relasi data di tabel pasiens dan nomor telepon tidak kosong
        $pasiens = User::where('role', 'pasien')
            ->whereHas('pasien', function($query) {
                $query->whereNotNull('no_telepon');
            })->get();

        $fonnteToken = env('FONNTE_TOKEN');

        foreach ($pasiens as $pasien) {
            // Ambil nomor hp pasien dari tabel relasi pasiens
            $nomorHp = $pasien->pasien->no_telepon;
            $message = "Hai {$pasien->name}, bagaimana kondisi emosionalmu hari ini?\n\nBalas dengan angka saja ya:\n1 (Buruk)\n2 (Biasa)\n3 (Bahagia)";

            // Eksekusi pengiriman payload ke API Fonnte
            Http::withHeaders([
                'Authorization' => $fonnteToken
            ])->post('https://api.fonnte.com/send', [
                'target' => $nomorHp,
                'message' => $message,
                'countryCode' => '62'
            ]);
        }

        $this->info('Pesan otomatis check-in mood berhasil disebarkan ke semua pasien.');
    }
}