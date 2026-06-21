<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DetailKonsultasi;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\RujukanPsikiater;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $totalKonsultasi = Konsultasi::where('id_pasien', $pasien->id_pasien)->count();

        $totalPembayaran = Pembayaran::whereHas('konsultasi', function ($query) use ($pasien) {
            $query->where('id_pasien', $pasien->id_pasien);
        })->count();

        $totalRekamMedis = DetailKonsultasi::whereHas('konsultasi', function ($query) use ($pasien) {
            $query->where('id_pasien', $pasien->id_pasien);
        })->count();

        $totalRujukan = RujukanPsikiater::where('id_pasien', $pasien->id_pasien)->count();

        return view('frontend.pasien.dashboard', compact(
            'totalKonsultasi',
            'totalPembayaran',
            'totalRekamMedis',
            'totalRujukan'
        ));
    }
}