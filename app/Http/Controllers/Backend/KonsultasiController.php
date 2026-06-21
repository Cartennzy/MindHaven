<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Konsultasi::with([
            'pasien.user',
            'psikolog.user',
            'pembayaran',
            'detailKonsultasi',
            'rujukanPsikiater.psikiater.rumahSakit',
            'rujukanPsikiater.rumahSakit',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('keluhan', 'like', '%' . $keyword . '%')
                    ->orWhere('topik_konsultasi', 'like', '%' . $keyword . '%')
                    ->orWhereHas('pasien', function ($pasien) use ($keyword) {
                        $pasien->where('nama_lengkap', 'like', '%' . $keyword . '%')
                            ->orWhereHas('user', function ($user) use ($keyword) {
                                $user->where('name', 'like', '%' . $keyword . '%')
                                    ->orWhere('email', 'like', '%' . $keyword . '%');
                            });
                    })
                    ->orWhereHas('psikolog', function ($psikolog) use ($keyword) {
                        $psikolog->where('nama_lengkap', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%')
                            ->orWhereHas('user', function ($user) use ($keyword) {
                                $user->where('name', 'like', '%' . $keyword . '%')
                                    ->orWhere('email', 'like', '%' . $keyword . '%');
                            });
                    });
            });
        }

        $konsultasis = $query->latest('id_konsultasi')->get();

        return view('backend.admin.konsultasi.index', compact('konsultasis'));
    }

    public function show(Konsultasi $konsultasi)
    {
        $konsultasi->load([
            'pasien.user',
            'psikolog.user',
            'pembayaran',
            'detailKonsultasi',
            'rujukanPsikiater.psikiater.rumahSakit',
            'rujukanPsikiater.rumahSakit',
        ]);

        return view('backend.admin.konsultasi.show', compact('konsultasi'));
    }

    public function update(Request $request, Konsultasi $konsultasi)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,dibatalkan',
        ]);

        $konsultasi->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.konsultasi.index')
            ->with('success', 'Status konsultasi berhasil diperbarui.');
    }

    public function destroy(Konsultasi $konsultasi)
    {
        $konsultasi->delete();

        return redirect()
            ->route('admin.konsultasi.index')
            ->with('success', 'Data konsultasi berhasil dihapus.');
    }
}