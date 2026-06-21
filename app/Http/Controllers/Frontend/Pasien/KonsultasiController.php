<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Notifikasi;
use App\Models\Psikolog;
use App\Models\HasilTes; // SINKRONISASI: Import model HasilTes agar bisa membaca skor asesmen mandiri gratis
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class KonsultasiController extends Controller
{
    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $konsultasis = Konsultasi::with([
                'psikolog.user',
                'pembayaran',
                'detailKonsultasi',
                'rujukanPsikiater.psikiater',
                'rujukanPsikiater.rumahSakit',
            ])
            ->where('id_pasien', $pasien->id_pasien)
            ->latest('id_konsultasi')
            ->get();

        return view('frontend.pasien.konsultasi.index', compact('konsultasis'));
    }

    public function create(Request $request)
    {
        $topikKonseling = [
            [
                'nama' => 'Stres',
                'deskripsi' => 'Bantuan untuk mengelola tekanan, kelelahan mental, dan beban pikiran.',
                'keyword' => 'stres',
                'icon' => 'fa-solid fa-brain',
                'synonyms' => ['stres', 'stress', 'burnout', 'tekanan', 'kelelahan mental'],
            ],
            [
                'nama' => 'Gangguan Kecemasan',
                'deskripsi' => 'Pendampingan untuk rasa cemas berlebihan, panik, dan overthinking.',
                'keyword' => 'gangguan_kecemasan',
                'icon' => 'fa-solid fa-heart-pulse',
                'synonyms' => [
                    'gangguan_kecemasan',
                    'gangguan kecemasan',
                    'kecemasan',
                    'cemas',
                    'anxiety',
                    'panic',
                    'panic attack',
                    'panik',
                    'overthinking',
                    'emotional regulation',
                    'regulasi emosi',
                ],
            ],
            [
                'nama' => 'Depresi',
                'deskripsi' => 'Bantuan awal untuk perasaan sedih berkepanjangan dan kehilangan motivasi.',
                'keyword' => 'depresi',
                'icon' => 'fa-solid fa-cloud-rain',
                'synonyms' => ['depresi', 'depression', 'sedih', 'kehilangan motivasi'],
            ],
            [
                'nama' => 'Keluarga & Hubungan',
                'deskripsi' => 'Konseling untuk masalah keluarga, pasangan, pertemanan, dan relasi sosial.',
                'keyword' => 'keluarga_hubungan',
                'icon' => 'fa-solid fa-users',
                'synonyms' => [
                    'keluarga_hubungan',
                    'keluarga hubungan',
                    'keluarga',
                    'hubungan',
                    'relationship',
                    'relasi',
                    'pasangan',
                    'pertemanan',
                ],
            ],
            [
                'nama' => 'Trauma',
                'deskripsi' => 'Pendampingan psikologis untuk pengalaman traumatis and luka emosional.',
                'keyword' => 'trauma',
                'icon' => 'fa-solid fa-shield-heart',
                'synonyms' => ['trauma', 'traumatis', 'ptsd', 'luka emosional'],
            ],
            [
                'nama' => 'Gangguan Mood',
                'deskripsi' => 'Bantuan untuk perubahan suasana hati, emosi tidak stabil, dan mood swing.',
                'keyword' => 'gangguan_mood',
                'icon' => 'fa-solid fa-face-smile-beam',
                'synonyms' => [
                    'gangguan_mood',
                    'gangguan mood',
                    'mood',
                    'mood swing',
                    'suasana hati',
                    'emosi tidak stabil',
                    'bipolar',
                ],
            ],
            [
                'nama' => 'Lainnya',
                'deskripsi' => 'Pilih ini jika keluhan Anda tidak termasuk dalam topik yang tersedia.',
                'keyword' => 'lainnya',
                'icon' => 'fa-solid fa-circle-plus',
                'synonyms' => ['lainnya', 'umum', 'general', 'konseling umum'],
            ],
        ];

        $selectedTopik = $request->get('topik');

        $query = Psikolog::with('user')
            ->withCount(['konsultasis as total_review' => function($q) {
                $q->whereNotNull('skor_rating');
            }])
            ->withAvg(['konsultasis as rata_rating' => function($q) {
                $q->whereNotNull('skor_rating');
            }], 'skor_rating')
            ->where('status_verifikasi', 'verified');

        if (Schema::hasColumn('psikologs', 'is_active')) {
            $query->where('is_active', true);
        }

        if ($selectedTopik && $selectedTopik !== 'lainnya') {
            $selectedTopicData = collect($topikKonseling)
                ->firstWhere('keyword', $selectedTopik);

            if ($selectedTopicData) {
                $keywords = $selectedTopicData['synonyms'];

                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $keywordSpace = str_replace('_', ' ', $keyword);
                        $keywordUnderscore = str_replace(' ', '_', $keyword);

                        $q->orWhere('spesialisasi', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('spesialisasi', 'LIKE', '%' . $keywordSpace . '%')
                            ->orWhere('spesialisasi', 'LIKE', '%' . $keywordUnderscore . '%');

                        if (Schema::hasColumn('psikologs', 'bio')) {
                            $q->orWhere('bio', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('bio', 'LIKE', '%' . $keywordSpace . '%')
                                ->orWhere('bio', 'LIKE', '%' . $keywordUnderscore . '%');
                        }
                    }
                });
            }
        }

        $psikologs = $query
            ->orderByDesc('rata_rating')
            ->orderByDesc('pengalaman')
            ->latest('id_psikolog')
            ->get();

        return view('frontend.pasien.konsultasi.create', compact(
            'psikologs',
            'topikKonseling',
            'selectedTopik'
        ));
    }

    public function detailPsikolog(Psikolog $psikolog)
    {
        if ($psikolog->status_verifikasi !== 'verified') {
            abort(404, 'Psikolog tidak tersedia.');
        }

        if (Schema::hasColumn('psikologs', 'is_active') && !$psikolog->is_active) {
            abort(404, 'Psikolog tidak tersedia.');
        }

        $psikolog->load([
            'user',
            'jadwalPraktiks'
            ]);

        $totalPasien = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->distinct('id_pasien')
            ->count('id_pasien');

        $totalKonsultasi = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
            ->count();

        return view('frontend.pasien.konsultasi.detail', compact(
            'psikolog',
            'totalPasien',
            'totalKonsultasi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_psikolog' => 'required|exists:psikologs,id_psikolog',
            'topik_konsultasi' => 'required|string|max:255',
        ], [
            'id_psikolog.required' => 'Psikolog wajib dipilih.',
            'id_psikolog.exists' => 'Psikolog yang dipilih tidak valid.',
            'topik_konsultasi.required' => 'Topik konsultasi wajib dipilih.',
            'topik_konsultasi.string' => 'Topik konsultasi harus berupa teks.',
            'topik_konsultasi.max' => 'Topik konsultasi maksimal 255 karakter.',
        ]);

        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $query = Psikolog::where('status_verifikasi', 'verified');

        if (Schema::hasColumn('psikologs', 'is_active')) {
            $query->where('is_active', true);
        }

        $psikolog = $query->findOrFail($request->id_psikolog);

        $konsultasi = Konsultasi::create([
            'id_pasien' => $pasien->id_pasien,
            'id_psikolog' => $psikolog->id_psikolog,
            'topik_konsultasi' => $request->topik_konsultasi,
            'metode_konsultasi' => 'chat',
            'keluhan' => 'Keluhan akan diisi setelah pembayaran berhasil.',
            'tanggal_konsultasi' => now('Asia/Jakarta')->toDateString(),
            'jam_konsultasi' => now('Asia/Jakarta')->format('H:i:s'),
            'harga' => $psikolog->biaya_konsultasi ?? 0,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('pasien.pembayaran.create', [
                'konsultasi_id' => $konsultasi->id_konsultasi,
            ])
            ->with('success', 'Silakan lanjutkan pembayaran konsultasi.');
    }

    public function metode(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        $konsultasi->load(['psikolog.user', 'pembayaran']);

        if (!$konsultasi->pembayaran || $konsultasi->pembayaran->status_pembayaran !== 'diterima') {
            return redirect()
                ->route('pasien.pembayaran.create', [
                    'konsultasi_id' => $konsultasi->id_konsultasi,
                ])
                ->with('error', 'Selesaikan pembayaran terlebih dahulu sebelum memilih metode konsultasi.');
        }

        $bookingClosed = $this->isBookingClosed();

        // =========================================================================
        // SINKRONISASI INTEGRASI: Ambil skor self-assessment terakhir jika parah
        // =========================================================================
        $defaultKeluhanAssessment = '';
        $latestTest = HasilTes::where('id_pasien', $konsultasi->id_pasien)
            ->with('instrumen')
            ->latest()
            ->first();

        if ($latestTest) {
            $statusLower = strtolower($latestTest->kesimpulan_status);
            // Jika indikasi parah, tinggi, atau berat, langsung suntikkan otomatis ke view blade
            if (str_contains($statusLower, 'parah') || str_contains($statusLower, 'tinggi') || str_contains($statusLower, 'berat') || str_contains($statusLower, 'buruk')) {
                $defaultKeluhanAssessment = "[REKAM MEDIS INTEGRASI AUTOMATIC]\nSaya telah melakukan Self-Assessment Mandiri untuk kategori " . $latestTest->instrumen->nama_tes . " dengan hasil akhir indikasi: " . $latestTest->kesimpulan_status . " (Skor Akhir: " . $latestTest->total_skor . " Poin).\n\nKeluhan tambahan: ";
            }
        }

        return view('frontend.pasien.konsultasi.metode', compact('konsultasi', 'bookingClosed', 'defaultKeluhanAssessment'));
    }

    public function storeMetode(Request $request, Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        $pasien = Auth::user()->pasien;

        $request->validate([
            'metode_konsultasi' => 'required|in:chat,video_call,temu_janji',
            'tanggal_konsultasi' => 'required|date',
            'jam_konsultasi' => 'required',
            'keluhan' => 'required|string|max:2000',
        ], [
            'metode_konsultasi.required' => 'Metode konsultasi wajib dipilih.',
            'metode_konsultasi.in' => 'Metode konsultasi tidak valid.',
            'tanggal_konsultasi.required' => 'Tanggal konsultasi wajib diisi.',
            'tanggal_konsultasi.date' => 'Tanggal konsultasi tidak valid.',
            'jam_konsultasi.required' => 'Jam konsultasi wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'keluhan.string' => 'Keluhan harus berupa teks.',
            'keluhan.max' => 'Keluhan maksimal 2000 karakter.',
        ]);

        $konsultasi->load(['pembayaran', 'psikolog.user']);

        if (!$konsultasi->pembayaran || $konsultasi->pembayaran->status_pembayaran !== 'diterima') {
            return redirect()
                ->route('pasien.pembayaran.create', [
                    'konsultasi_id' => $konsultasi->id_konsultasi,
                ])
                ->with('error', 'Selesaikan pembayaran terlebih dahulu sebelum memilih metode konsultasi.');
        }

        if ($this->isBookingClosed()) {
            return redirect()
                ->route('pasien.konsultasi.show', $konsultasi->id_konsultasi)
                ->with('error', 'Akses booking konsultasi sudah ditutup. Silakan kembali besok pagi mulai pukul 06.00 WIB.');
        }

        $konsultasi->update([
            'metode_konsultasi' => $request->metode_konsultasi,
            'tanggal_konsultasi' => $request->tanggal_konsultasi,
            'jam_konsultasi' => $request->jam_konsultasi,
            'keluhan' => $request->keluhan,
            'status' => 'diproses',
        ]);

        if ($konsultasi->psikolog && $konsultasi->psikolog->user_id) {
            Notifikasi::create([
                'user_id' => $konsultasi->psikolog->user_id,
                'psikolog_id' => $konsultasi->id_psikolog,
                'id_konsultasi' => $konsultasi->id_konsultasi,
                'judul' => 'Konsultasi Baru',
                'pesan' => 'Ada konsultasi baru dari pasien ' . ($pasien->nama_lengkap ?? Auth::user()->name) . '.',
                'tipe' => 'konsultasi_baru',
                'is_read' => false,
            ]);
        }

        return redirect()
            ->route('pasien.konsultasi.show', $konsultasi->id_konsultasi)
            ->with('success', 'Metode dan jadwal konsultasi berhasil dipilih.');
    }

    public function sesi(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        if ($konsultasi->status === 'pending') {
            return redirect()
                ->route('pasien.konsultasi.show', $konsultasi->id_konsultasi)
                ->with('error', 'Sesi konsultasi belum dapat dimulai.');
        }

        if ($konsultasi->status === 'dibatalkan') {
            return redirect()
                ->route('pasien.konsultasi.show', $konsultasi->id_konsultasi)
                ->with('error', 'Konsultasi ini telah dibatalkan.');
        }

        $konsultasi->load([
            'psikolog.user',
            'pasien.user',
            'messages',
        ]);

        return view('frontend.pasien.konsultasi.sesi', compact('konsultasi'));
    }

    public function show(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        $konsultasi->load([
            'psikolog.user',
            'pembayaran',
            'detailKonsultasi',
            'rujukanPsikiater.psikiater',
            'rujukanPsikiater.rumahSakit',
            'messages',
        ]);

        return view('frontend.pasien.konsultasi.show', compact('konsultasi'));
    }

    public function hasil(Konsultasi $konsultasi)
    {
        return $this->hasilShow($konsultasi);
    }

    public function hasilIndex()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            abort(403, 'Akun ini belum terhubung dengan data pasien.');
        }

        $konsultasis = Konsultasi::with([
                'psikolog.user',
                'detailKonsultasi',
                'rujukanPsikiater.psikiater',
                'rujukanPsikiater.rumahSakit',
            ])
            ->where('id_pasien', $pasien->id_pasien)
            ->whereHas('detailKonsultasi')
            ->latest('id_konsultasi')
            ->get();

        return view('frontend.pasien.hasil_konsultasi.index', compact('konsultasis'));
    }

    public function hasilShow(Konsultasi $konsultasi)
    {
        $this->authorizeAccess($konsultasi);

        $konsultasi->load([
            'psikolog.user',
            'detailKonsultasi',
            'rujukanPsikiater.psikiater',
            'rujukanPsikiater.rumahSakit',
        ]);

        if (!$konsultasi->detailKonsultasi) {
            return redirect()
                ->route('pasien.konsultasi.show', $konsultasi->id_konsultasi)
                ->with('error', 'Hasil konsultasi belum tersedia.');
        }

        return view('frontend.pasien.konsultasi.show', compact('konsultasi'));
    }

    public function getJadwalPsikolog($id)
    {
        $psikolog = Psikolog::with('jadwalPraktiks')->findOrFail($id);
        
        $bookedDates = Konsultasi::where('id_psikolog', $id)
            ->whereIn('status', ['diproses', 'selesai', 'pending'])
            ->where('tanggal_konsultasi', '>=', now('Asia/Jakarta')->toDateString())
            ->select('tanggal_konsultasi')
            ->selectRaw('count(*) as total')
            ->groupBy('tanggal_konsultasi')
            ->get()
            ->pluck('total', 'tanggal_konsultasi');

        return response()->json([
            'jadwal' => $psikolog->jadwalPraktiks,
            'booked_dates' => $bookedDates
        ]);
    }

    private function isBookingClosed(): bool
    {
        $now = Carbon::now('Asia/Jakarta');

        return $now->lt($now->copy()->setTime(6, 0, 0))
            || $now->gte($now->copy()->setTime(20, 0, 0));
    }

    private function authorizeAccess(Konsultasi $konsultasi): void
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien || $konsultasi->id_pasien !== $pasien->id_pasien) {
            abort(403, 'Anda tidak memiliki akses ke konsultasi ini.');
        }
    }
}