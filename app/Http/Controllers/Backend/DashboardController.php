<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Konsultasi;
use App\Models\Meditasi;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Psikiater;
use App\Models\Psikolog;
use App\Models\RujukanPsikiater;
use App\Models\RumahSakit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPasien = Pasien::count();
        $totalPsikolog = Psikolog::count();
        $totalPsikiater = Psikiater::count();
        $totalRumahSakit = RumahSakit::count();
        $totalKonsultasi = Konsultasi::count();
        $totalPembayaran = Pembayaran::count();

        $totalRekamMedis = Schema::hasTable('rekam_medis')
            ? DB::table('rekam_medis')->count()
            : 0;

        $totalRujukan = RujukanPsikiater::count();
        $totalArtikel = Artikel::count();
        $totalMeditasi = Meditasi::count();

        $pembayaranPending = Pembayaran::where('status_pembayaran', 'pending')->count();
        $pembayaranDiterima = Pembayaran::where('status_pembayaran', 'diterima')->count();

        $saldoAdmin = Pembayaran::where('status_pembayaran', 'diterima')
            ->sum('biaya_admin');

        $totalPendapatanPsikolog = Pembayaran::where('status_pembayaran', 'diterima')
            ->sum('biaya_psikolog');

        $totalTransaksiBerhasil = Pembayaran::where('status_pembayaran', 'diterima')
            ->sum('total_pembayaran');

        $konsultasiSelesai = Konsultasi::where('status', 'selesai')->count();

        return view('backend.admin.dashboard', compact(
            'totalPasien',
            'totalPsikolog',
            'totalPsikiater',
            'totalRumahSakit',
            'totalKonsultasi',
            'totalPembayaran',
            'totalRekamMedis',
            'totalRujukan',
            'totalArtikel',
            'totalMeditasi',
            'pembayaranPending',
            'pembayaranDiterima',
            'saldoAdmin',
            'totalPendapatanPsikolog',
            'totalTransaksiBerhasil',
            'konsultasiSelesai'
        ));
    }
}