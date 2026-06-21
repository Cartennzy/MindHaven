<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilKonsultasiController extends Controller
{
    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $konsultasis = Konsultasi::with([
                'psikolog.user',
                'detailKonsultasi',
                'rujukanPsikiater',
            ])
            ->where('id_pasien', $pasien->id_pasien)
            ->whereHas('detailKonsultasi')
            ->latest('id_konsultasi')
            ->get();

        Notifikasi::where('user_id', Auth::id())
            ->where('tipe', 'hasil_konsultasi')
            ->update([
                'is_read' => true,
            ]);

        return view('frontend.pasien.hasil_konsultasi.index', compact('konsultasis'));
    }

    public function show(Konsultasi $konsultasi)
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $konsultasi->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke hasil konsultasi ini.');
        }

        $konsultasi->load([
            'psikolog.user',
            'detailKonsultasi',
            'rujukanPsikiater',
        ]);

        Notifikasi::where('user_id', Auth::id())
            ->where(function ($query) use ($konsultasi) {
                $query->where('id_konsultasi', $konsultasi->id_konsultasi)
                    ->orWhere('tipe', 'hasil_konsultasi');
            })
            ->update([
                'is_read' => true,
            ]);

        return view('frontend.pasien.hasil_konsultasi.show', compact('konsultasi'));
    }

    // Method baru untuk menyimpan rating dan ulasan kustom dari pasien
    public function rate(Request $request, $id)
    {
        $pasien = Auth::user()->pasien;
        
        $konsultasi = Konsultasi::where('id_pasien', $pasien->id_pasien)
            ->findOrFail($id);

        $request->validate([
            'skor_rating' => 'required|integer|between:1,5',
            'catatan_ulasan' => 'nullable|string|max:1000'
        ]);

        $konsultasi->update([
            'skor_rating' => $request->skor_rating,
            'catatan_ulasan' => $request->catatan_ulasan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating berhasil dikirim.'
        ]);
    }
}