<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Pasien;
use Illuminate\Support\Facades\Auth;

class PasienController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $pasiens = Pasien::with('user')
            ->whereHas('konsultasis', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->withCount([
                'konsultasis as total_konsultasi' => function ($query) use ($psikolog) {
                    $query->where('id_psikolog', $psikolog->id_psikolog);
                }
            ])
            ->latest('id_pasien')
            ->get();

        return view('frontend.psikolog.pasien.index', compact('pasiens'));
    }

    public function show(Pasien $pasien)
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $punyaAkses = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->where('id_pasien', $pasien->id_pasien)
            ->exists();

        if (!$punyaAkses) {
            abort(403, 'Anda tidak memiliki akses ke data pasien ini.');
        }

        $pasien->load([
            'user',
            'konsultasis' => function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog)
                    ->with(['detailKonsultasi', 'pembayaran'])
                    ->latest('id_konsultasi');
            },
        ]);

        return view('frontend.psikolog.pasien.show', compact('pasien'));
    }
}