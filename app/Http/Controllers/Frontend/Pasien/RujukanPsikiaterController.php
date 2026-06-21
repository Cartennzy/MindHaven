<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\RujukanPsikiater;
use Illuminate\Support\Facades\Auth;

class RujukanPsikiaterController extends Controller
{
    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $rujukans = RujukanPsikiater::with([
                'konsultasi.detailKonsultasi',
                'psikolog.user',
                'psikiater',
                'rumahSakit',
            ])
            ->where('id_pasien', $pasien->id_pasien)
            ->latest('id_rujukan')
            ->get();

        return view('frontend.pasien.rujukan_psikiater.index', compact('rujukans'));
    }

    public function show(RujukanPsikiater $rujukanPsikiater)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $rujukanPsikiater->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke surat rujukan ini.');
        }

        $rujukanPsikiater->load([
            'pasien.user',
            'konsultasi.detailKonsultasi',
            'psikolog.user',
            'psikiater',
            'rumahSakit',
        ]);

        return view('frontend.pasien.rujukan_psikiater.show', compact('rujukanPsikiater'));
    }
}