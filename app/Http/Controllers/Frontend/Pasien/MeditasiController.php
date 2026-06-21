<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Meditasi;
use Illuminate\Support\Facades\Auth;

class MeditasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pasien = $user->pasien ?? null;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $meditasis = Meditasi::where('status', 'published')
            ->latest('id_meditasi')
            ->get();

        $kategoriMeditasi = Meditasi::where('status', 'published')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('frontend.pasien.meditasi.index', compact(
            'user',
            'pasien',
            'meditasis',
            'kategoriMeditasi'
        ));
    }

    public function show(Meditasi $meditasi)
    {
        $user = Auth::user();
        $pasien = $user->pasien ?? null;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        if ($meditasi->status !== 'published') {
            abort(404);
        }

        return view('frontend.pasien.meditasi.show', compact('user', 'pasien', 'meditasi'));
    }
}