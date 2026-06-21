<?php

namespace App\Http\Controllers\Frontend\Psikolog;

use App\Http\Controllers\Controller;
use App\Models\DetailKonsultasi;
use App\Models\Konsultasi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function index()
    {
        $psikolog = Auth::user()->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $konsultasis = Konsultasi::with([
                'pasien.user',
                'pembayaran',
                'detailKonsultasi',
                'rujukanPsikiater',
            ])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->latest('id_konsultasi')
            ->get();

        return view('frontend.psikolog.konsultasi.index', compact('konsultasis'));
    }

    public function hasilIndex()
    {
        $psikolog = Auth::user()->psikolog;

        if (!$psikolog) {
            abort(403, 'Akun ini belum terhubung dengan data psikolog.');
        }

        $konsultasis = Konsultasi::with([
                'pasien.user',
                'detailKonsultasi',
            ])
            ->where('id_psikolog', $psikolog->id_psikolog)
            ->whereHas('detailKonsultasi')
            ->latest('id_konsultasi')
            ->get();

        return view('frontend.psikolog.hasil_konsultasi.index', compact('konsultasis'));
    }

    public function hasil(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        return redirect()->route('psikolog.konsultasi.show', $konsultasi->id_konsultasi);
    }

    public function show(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        $konsultasi->load([
            'pasien.user',
            'psikolog.user',
            'pembayaran',
            'detailKonsultasi',
            'rujukanPsikiater',
        ]);

        Notifikasi::where('psikolog_id', Auth::user()->psikolog->id_psikolog)
            ->where('id_konsultasi', $konsultasi->id_konsultasi)
            ->update(['is_read' => true]);

        return view('frontend.psikolog.konsultasi.show', compact('konsultasi'));
    }

    public function accept(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        if ($konsultasi->status !== 'pending') {
            return back()->with('error', 'Konsultasi ini sudah diproses.');
        }

        $konsultasi->update([
            'status' => 'diproses',
        ]);

        return back()->with('success', 'Konsultasi berhasil di-ACC dan sesi konsultasi sudah dapat dimulai.');
    }

    public function sesi(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        if ($konsultasi->status === 'pending') {
            return redirect()
                ->route('psikolog.konsultasi.show', $konsultasi->id_konsultasi)
                ->with('error', 'Konsultasi harus di-ACC terlebih dahulu.');
        }

        $konsultasi->load([
            'pasien.user',
            'psikolog.user',
            'detailKonsultasi',
        ]);

        return view('frontend.psikolog.konsultasi.sesi', compact('konsultasi'));
    }

    public function update(Request $request, Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,dibatalkan',
            'keluhan_utama' => 'nullable|string',
            'riwayat_hidup' => 'nullable|string',
            'hasil_observasi' => 'required|string',
            'diagnosis_awal' => 'required|string',
            'rencana_penanganan' => 'required|string',
            'laporan_asesmen_psikologis' => 'nullable|string',
            'perlu_rujukan' => 'nullable',
        ], [
            'status.required' => 'Status konsultasi wajib dipilih.',
            'status.in' => 'Status konsultasi tidak valid.',

            'keluhan_utama.string' => 'Keluhan utama harus berupa teks.',
            'riwayat_hidup.string' => 'Riwayat hidup harus berupa teks.',

            'hasil_observasi.required' => 'Hasil observasi wajib diisi.',
            'hasil_observasi.string' => 'Hasil observasi harus berupa teks.',

            'diagnosis_awal.required' => 'Diagnosis awal wajib diisi.',
            'diagnosis_awal.string' => 'Diagnosis awal harus berupa teks.',

            'rencana_penanganan.required' => 'Rencana penanganan wajib diisi.',
            'rencana_penanganan.string' => 'Rencana penanganan harus berupa teks.',

            'laporan_asesmen_psikologis.string' => 'Laporan asesmen psikologis harus berupa teks.',
        ]);

        DetailKonsultasi::updateOrCreate(
            [
                'id_konsultasi' => $konsultasi->id_konsultasi,
            ],
            [
                'keluhan_utama' => $request->keluhan_utama ?: $konsultasi->keluhan,
                'riwayat_hidup' => $request->riwayat_hidup,
                'hasil_observasi' => $request->hasil_observasi,
                'diagnosis_awal' => $request->diagnosis_awal,
                'rencana_penanganan' => $request->rencana_penanganan,
                'laporan_asesmen_psikologis' => $request->laporan_asesmen_psikologis,
                'perlu_rujukan' => $request->boolean('perlu_rujukan'),
            ]
        );

        $konsultasi->update([
            'status' => $request->status,
        ]);

        $konsultasi->load([
            'pasien.user',
            'psikolog',
            'detailKonsultasi',
        ]);

        if ($konsultasi->pasien && $konsultasi->pasien->user) {
            Notifikasi::updateOrCreate(
                [
                    'user_id' => $konsultasi->pasien->user->id,
                    'id_konsultasi' => $konsultasi->id_konsultasi,
                    'tipe' => 'hasil_konsultasi',
                ],
                [
                    'psikolog_id' => $konsultasi->id_psikolog,
                    'judul' => 'Hasil Konsultasi Tersedia',
                    'pesan' => 'Psikolog ' . ($konsultasi->psikolog->nama_lengkap ?? 'MindHaven') . ' telah mengirimkan hasil konsultasi Anda.',
                    'is_read' => false,
                ]
            );
        }

        return redirect()
            ->route('psikolog.konsultasi.show', $konsultasi->id_konsultasi)
            ->with('success', 'Hasil konsultasi berhasil disimpan dan sudah tersinkron ke pasien.');
    }

    private function authorizeAccess(Konsultasi $konsultasi): void
    {
        $psikolog = Auth::user()->psikolog;

        if (!$psikolog || $konsultasi->id_psikolog !== $psikolog->id_psikolog) {
            abort(403, 'Anda tidak memiliki akses ke data konsultasi ini.');
        }
    }
}