<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use App\Models\DetailKonsultasi;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\RujukanPsikiater;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        if (!$psikolog) {
            abort(403, 'Data psikolog tidak ditemukan.');
        }

        $totalPasien = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->distinct('id_pasien')
            ->count('id_pasien');

        $konsultasiHariIni = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $totalKonsultasi = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)->count();

        $totalKonsultasiPending = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->where('status', 'pending')
            ->count();

        $totalKonsultasiSelesai = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->where('status', 'selesai')
            ->count();

        $totalRekamMedis = DetailKonsultasi::whereHas('konsultasi', function ($query) use ($psikolog) {
            $query->where('id_psikolog', $psikolog->id_psikolog);
        })->count();

        $totalRujukan = RujukanPsikiater::where('id_psikolog', $psikolog->id_psikolog)->count();

        $totalPendapatanPsikolog = Pembayaran::whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->sum('biaya_psikolog');

        $pendapatanHariIni = Pembayaran::whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->whereDate('updated_at', Carbon::today())
            ->sum('biaya_psikolog');

        $totalPembayaranDiterima = Pembayaran::whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->count();

        $jadwalKonsultasi = Konsultasi::with(['pasien.user', 'pembayaran'])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->latest('id_konsultasi')
            ->take(5)
            ->get();

        $pasienTerbaru = Konsultasi::with(['pasien.user', 'pembayaran'])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->latest('id_konsultasi')
            ->first();

        $aktivitasTerbaru = Konsultasi::with(['pasien.user', 'pembayaran'])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->latest('id_konsultasi')
            ->take(5)
            ->get();

        return view('frontend.psikolog.dashboard', compact(
            'totalPasien',
            'konsultasiHariIni',
            'totalKonsultasi',
            'totalKonsultasiPending',
            'totalKonsultasiSelesai',
            'totalRekamMedis',
            'totalRujukan',
            'totalPendapatanPsikolog',
            'pendapatanHariIni',
            'totalPembayaranDiterima',
            'jadwalKonsultasi',
            'pasienTerbaru',
            'aktivitasTerbaru'
        ));
    }

    public function pendapatan()
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        if (!$psikolog) {
            abort(403, 'Data psikolog tidak ditemukan.');
        }

        $pembayarans = Pembayaran::with(['konsultasi.pasien.user'])
            ->whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->latest('id_pembayaran')
            ->paginate(10);

        $totalPendapatanPsikolog = Pembayaran::whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->sum('biaya_psikolog');

        $totalPembayaranDiterima = Pembayaran::whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->count();

        $pendapatanHariIni = Pembayaran::whereHas('konsultasi', function ($query) use ($psikolog) {
                $query->where('id_psikolog', $psikolog->id_psikolog);
            })
            ->where('status_pembayaran', 'diterima')
            ->whereDate('updated_at', Carbon::today())
            ->sum('biaya_psikolog');

        return view('frontend.psikolog.pembayaran.index', compact(
            'pembayarans',
            'totalPendapatanPsikolog',
            'totalPembayaranDiterima',
            'pendapatanHariIni'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        $psikolog = $user->psikolog;

        return view('frontend.psikolog.profile', compact('user', 'psikolog'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}