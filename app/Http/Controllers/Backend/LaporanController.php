<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\Psikolog;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with([
            'konsultasi.pasien.user',
            'konsultasi.psikolog.user',
            'konsultasi.detailKonsultasi',
            'konsultasi.rujukanPsikiater',
        ]);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('id_order', 'like', "%{$keyword}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$keyword}%")
                    ->orWhereHas('konsultasi.pasien', function ($pasien) use ($keyword) {
                        $pasien->where('nama_lengkap', 'like', "%{$keyword}%")
                            ->orWhereHas('user', function ($user) use ($keyword) {
                                $user->where('email', 'like', "%{$keyword}%");
                            });
                    })
                    ->orWhereHas('konsultasi.psikolog', function ($psikolog) use ($keyword) {
                        $psikolog->where('nama_lengkap', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('konsultasi', function ($konsultasi) use ($keyword) {
                        $konsultasi->where('topik_konsultasi', 'like', "%{$keyword}%")
                            ->orWhere('keluhan', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('id_psikolog')) {
            $query->whereHas('konsultasi', function ($q) use ($request) {
                $q->where('id_psikolog', $request->id_psikolog);
            });
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('status_konsultasi')) {
            $query->whereHas('konsultasi', function ($q) use ($request) {
                $q->where('status', $request->status_konsultasi);
            });
        }

        if ($request->filled('bulan')) {
            $query->whereHas('konsultasi', function ($q) use ($request) {
                $q->whereMonth('tanggal_konsultasi', $request->bulan);
            });
        }

        if ($request->filled('tahun')) {
            $query->whereHas('konsultasi', function ($q) use ($request) {
                $q->whereYear('tanggal_konsultasi', $request->tahun);
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereHas('konsultasi', function ($q) use ($request) {
                $q->whereDate('tanggal_konsultasi', '>=', $request->tanggal_mulai);
            });
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereHas('konsultasi', function ($q) use ($request) {
                $q->whereDate('tanggal_konsultasi', '<=', $request->tanggal_selesai);
            });
        }

        $laporans = $query->latest('id_pembayaran')->get();

        $totalTransaksi = $laporans->count();
        $totalPembayaranDiterima = $laporans->where('status_pembayaran', 'diterima')->count();
        $totalPembayaranPending = $laporans->where('status_pembayaran', 'pending')->count();
        $totalPembayaranGagal = $laporans->where('status_pembayaran', 'gagal')->count();

        $totalPendapatan = $laporans->where('status_pembayaran', 'diterima')->sum('total_pembayaran');
        $totalBiayaAdmin = $laporans->where('status_pembayaran', 'diterima')->sum('biaya_admin');
        $totalBiayaPsikolog = $laporans->where('status_pembayaran', 'diterima')->sum('biaya_psikolog');

        $konsultasiQuery = Konsultasi::query();

        if ($request->filled('id_psikolog')) {
            $konsultasiQuery->where('id_psikolog', $request->id_psikolog);
        }

        if ($request->filled('status_konsultasi')) {
            $konsultasiQuery->where('status', $request->status_konsultasi);
        }

        if ($request->filled('bulan')) {
            $konsultasiQuery->whereMonth('tanggal_konsultasi', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $konsultasiQuery->whereYear('tanggal_konsultasi', $request->tahun);
        }

        if ($request->filled('tanggal_mulai')) {
            $konsultasiQuery->whereDate('tanggal_konsultasi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $konsultasiQuery->whereDate('tanggal_konsultasi', '<=', $request->tanggal_selesai);
        }

        $totalKonsultasi = (clone $konsultasiQuery)->count();
        $konsultasiPending = (clone $konsultasiQuery)->where('status', 'pending')->count();
        $konsultasiDiproses = (clone $konsultasiQuery)->where('status', 'diproses')->count();
        $konsultasiSelesai = (clone $konsultasiQuery)->where('status', 'selesai')->count();
        $konsultasiDibatalkan = (clone $konsultasiQuery)->where('status', 'dibatalkan')->count();

        $psikologs = Psikolog::orderBy('nama_lengkap')->get();

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tahunSekarang = date('Y');
        $tahunList = range($tahunSekarang, $tahunSekarang - 5);

        $tahunGrafik = $request->filled('tahun') ? $request->tahun : $tahunSekarang;

        $grafikBulanLabels = array_values($bulanList);
        $grafikTransaksiBulanan = [];
        $grafikPendapatanBulanan = [];
        $grafikAdminBulanan = [];
        $grafikPsikologBulanan = [];
        $grafikKonsultasiBulanan = [];

        foreach ($bulanList as $nomorBulan => $namaBulan) {
            $pembayaranBulanan = Pembayaran::whereHas('konsultasi', function ($q) use ($nomorBulan, $tahunGrafik, $request) {
                $q->whereMonth('tanggal_konsultasi', $nomorBulan)
                    ->whereYear('tanggal_konsultasi', $tahunGrafik);

                if ($request->filled('id_psikolog')) {
                    $q->where('id_psikolog', $request->id_psikolog);
                }

                if ($request->filled('status_konsultasi')) {
                    $q->where('status', $request->status_konsultasi);
                }
            });

            if ($request->filled('status_pembayaran')) {
                $pembayaranBulanan->where('status_pembayaran', $request->status_pembayaran);
            }

            $pembayaranDiterimaBulanan = (clone $pembayaranBulanan)->where('status_pembayaran', 'diterima');

            $grafikTransaksiBulanan[] = (clone $pembayaranBulanan)->count();
            $grafikPendapatanBulanan[] = (clone $pembayaranDiterimaBulanan)->sum('total_pembayaran');
            $grafikAdminBulanan[] = (clone $pembayaranDiterimaBulanan)->sum('biaya_admin');
            $grafikPsikologBulanan[] = (clone $pembayaranDiterimaBulanan)->sum('biaya_psikolog');

            $konsultasiBulanan = Konsultasi::whereMonth('tanggal_konsultasi', $nomorBulan)
                ->whereYear('tanggal_konsultasi', $tahunGrafik);

            if ($request->filled('id_psikolog')) {
                $konsultasiBulanan->where('id_psikolog', $request->id_psikolog);
            }

            if ($request->filled('status_konsultasi')) {
                $konsultasiBulanan->where('status', $request->status_konsultasi);
            }

            $grafikKonsultasiBulanan[] = $konsultasiBulanan->count();
        }

        return view('backend.admin.laporan.index', compact(
            'laporans',
            'psikologs',
            'bulanList',
            'tahunList',
            'tahunGrafik',
            'grafikBulanLabels',
            'grafikTransaksiBulanan',
            'grafikPendapatanBulanan',
            'grafikAdminBulanan',
            'grafikPsikologBulanan',
            'grafikKonsultasiBulanan',
            'totalTransaksi',
            'totalPembayaranDiterima',
            'totalPembayaranPending',
            'totalPembayaranGagal',
            'totalPendapatan',
            'totalBiayaAdmin',
            'totalBiayaPsikolog',
            'totalKonsultasi',
            'konsultasiPending',
            'konsultasiDiproses',
            'konsultasiSelesai',
            'konsultasiDibatalkan'
        ));
    }
}