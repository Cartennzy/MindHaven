<?php

namespace App\Http\Controllers\Frontend\Pasien;

use App\Http\Controllers\Controller;
use App\Models\InstrumenTes;
use App\Models\HasilTes;
use App\Models\Artikel;
use App\Models\Meditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Tambah facade pendukung untuk rendering dokumen PDF stream

class SelfAssessmentController extends Controller
{
    /**
     * Menampilkan halaman beranda utama fitur Self-Assessment Gratis.
     * Mengambil seluruh data instrumen kuesioner (Stres, Burnout, Kecemasan, Depresi)
     * sekaligus menghitung jumlah butir pertanyaan yang tersedia pada tiap kategori.
     */
    public function index()
    {
        // Mengambil semua instrumen tes beserta jumlah relasi pertanyaannya secara efisien (Eager Loading Count)
        $instrumens = InstrumenTes::withCount('pertanyaans')->get();
        
        // Melempar data instrumen ke file view berkas utama indeks asesmen pasien
        return view('frontend.pasien.assessment.index', compact('instrumens'));
    }

    /**
     * Menampilkan lembar pengerjaan kuesioner pertanyaan (Multi-step Wizard Form)
     * berdasarkan parameter slug kategori tes unik yang dipilih oleh pasien.
     */
    public function show($slug)
    {
        // Mencari data instrumen di database berdasarkan slug, jika tidak ditemukan langsung melempar error 404
        $instrumen = InstrumenTes::where('slug', $slug)->with('pertanyaans')->firstOrFail();

        // Validasi pengaman backend: Jika butir pertanyaan belum terisi di database, kembalikan ke menu utama
        if ($instrumen->pertanyaans->count() === 0) {
            return redirect()->route('pasien.self-assessment.index')
                ->with('error', 'Butir kuesioner pertanyaan untuk kategori ini belum tersedia.');
        }

        // Melempar data instrumen dan daftar butir pertanyaan ke halaman form kuesioner
        return view('frontend.pasien.assessment.show', compact('instrumen'));
    }

    /**
     * Memproses pengiriman data form kuesioner, mengalkulasi bobot akumulasi nilai,
     * mendeteksi kriteria status keparahan klinis melalui parameter JSON rules_skor,
     * kemudian menyimpan rekam riwayat pemeriksaan kesehatan mental secara permanen.
     */
    public function store(Request $request, $slug)
    {
        // Mengambil data master instrumen tes berdasarkan parameter slug kuesioner
        $instrumen = InstrumenTes::where('slug', $slug)->firstOrFail();
        
        // Mengambil data profile entitas pasien yang terikat dengan user yang sedang login aktif saat ini
        $pasien = Auth::user()->pasien;

        // Validasi pengaman: Memastikan akun pengguna sudah terdaftar dan terhubung sebagai entitas pasien
        if (!$pasien) {
            return redirect()->back()->with('error', 'Otorisasi gagal. Akun Anda belum terdaftar sebagai pasien.');
        }

        // Validasi input form: Memastikan array jawaban dari form kuesioner wajib terisi penuh tanpa terlewat
        $request->validate([
            'jawaban' => 'required|array',
        ], [
            'jawaban.required' => 'Mohon selesaikan dan isi seluruh butir kuesioner pertanyaan yang tersedia.',
        ]);

        // ==========================================================================
        // PROSES HITUNG AKUMULASI SKOR JAWABAN (Skala Likert Dinamis 0 - 3)
        // ==========================================================================
        $totalSkor = 0;
        // Melakukan perulangan foreach untuk menjumlahkan semua poin nilai dari kiriman radio button form frontend
        foreach ($request->jawaban as $poin) {
            $totalSkor += (int) $poin; // Melakukan casting tipe data ke integer untuk memastikan kalkulasi presisi
        }

        // Inisialisasi variabel nilai default sebelum dicocokkan dengan kriteria aturan ambang batas skor
        $kesimpulanStatus = 'Tidak Terdefinisi';
        $catatanSaran = 'Silakan berkonsultasi dengan tim tenaga ahli psikologi klinis.';
        
        // Menangkap data konfigurasi parameter JSON aturan penilaian (rules_skor) dari tabel database
        $rules = $instrumen->rules_skor;

        // ==========================================================================
        // PROSES LOOP MATCHING DIAGNOSIS: Mencocokkan Total Skor ke Range Aturan JSON
        // ==========================================================================
        if (is_array($rules)) {
            foreach ($rules as $rule) {
                // Memeriksa secara matematis apakah totalSkor pasien masuk di antara ambang batas minimal dan maksimal
                if ($totalSkor >= $rule['min'] && $totalSkor <= $rule['max']) {
                    $kesimpulanStatus = $rule['status']; // Mengunci string kesimpulan status (Normal / Sedang / Tinggi / dll)
                    $catatanSaran = $rule['saran'];      // Mengambil saran penanganan medis yang sesuai
                    break;                               // Menghentikan perulangan (break execution) demi efisiensi server jika sudah klop
                }
            }
        }

        // Menyimpan rekam riwayat pemeriksaan hasil self-assessment pasien ke dalam tabel database hasil_tes
        $hasil = HasilTes::create([
            'id_pasien' => $pasien->id_pasien,
            'id_instrumen' => $instrumen->id_instrumen,
            'total_skor' => $totalSkor,
            'kesimpulan_status' => $kesimpulanStatus,
            'catatan_saran' => $catatanSaran,
        ]);

        // Mengalihkan halaman ke dashboard resume visualisasi hasil dengan membawa ID berkas rekam medis yang baru dibuat
        return redirect()->route('pasien.self-assessment.result', $hasil->id_hasil)
            ->with('success', 'Asesmen mandiri kesehatan mental Anda berhasil dianalisis.');
    }

    /**
     * Menampilkan dashboard visualisasi ringkasan hasil akhir asesmen mandiri kesehatan mental,
     * grafik pelacakan progress tracking evaluasi berkala, serta penapisan konten rekomendasi terapi.
     */
    public function result($id_hasil)
    {
        // Mendapatkan entitas data pasien dari user yang sedang terotentikasi login
        $pasien = Auth::user()->pasien;
        
        // Mengambil berkas rekam riwayat hasil tes spesifik milik pasien berdasarkan ID hasil, sertakan data instrumennya
        $hasil = HasilTes::where('id_pasien', $pasien->id_pasien)
            ->with('instrumen')
            ->findOrFail($id_hasil);

        // Menentukan warna dasar awal kelas penanda badge visual Tailwind CSS
        $warnaBadge = 'emerald';
        $rules = $hasil->instrumen->rules_skor;
        
        // Membaca ulang konfigurasi warna badge dinamis (emerald/amber/rose) berdasarkan kesesuaian status indikasi asli di DB
        if (is_array($rules)) {
            foreach ($rules as $rule) {
                if ($hasil->kesimpulan_status === $rule['status']) {
                    $warnaBadge = $rule['warna'] ?? 'emerald';
                    break;
                }
            }
        }

        // ==========================================================================
        // FITUR 3: PROGRESS TRACKING LOGIC (Membaca Tren Evaluasi Berkala Bulanan)
        // ==========================================================================
        // Mengambil maksimal 4 data riwayat rekam medis terdahulu pada kategori instrumen yang sejenis milik pasien tersebut
        $riwayatTes = HasilTes::where('id_pasien', $pasien->id_pasien)
            ->where('id_instrumen', $hasil->id_instrumen)
            ->orderBy('created_at', 'asc')
            ->take(4)
            ->get();

        $persentasePerkembangan = 0;
        $statusPerkembanganText = "";
        
        // Perhitungan persentase perbandingan perkembangan minimal harus memiliki 2 rekam data pemeriksaan (awal dan akhir)
        if ($riwayatTes->count() >= 2) {
            $tesPertama = $riwayatTes->first()->total_skor;  // Skor pada sesi tes terlama bulan ini
            $tesTerakhir = $riwayatTes->last()->total_skor;  // Skor pada sesi tes terbaru yang barusan diselesaikan
            
            // Validasi pembagian matematika: Memastikan nilai pembagi awal di atas angka 0 untuk mencegah fatal error division by zero
            if ($tesPertama > 0) {
                $selisih = $tesPertama - $tesTerakhir;
                $persentasePerkembangan = round(($selisih / $tesPertama) * 100); // Rumus mencari persentase selisih perkembangan mood
                
                // Menyusun teks analisis narasi perkembangan psikologis pasien secara kontekstual riil
                if ($persentasePerkembangan > 0) {
                    $statusPerkembanganText = "Tingkat " . $hasil->instrumen->nama_tes . " Anda menurun " . abs($persentasePerkembangan) . "% dibanding awal tes bulan ini. Ini perkembangan yang sangat bagus!";
                } elseif ($persentasePerkembangan < 0) {
                    $statusPerkembanganText = "Tingkat " . $hasil->instrumen->nama_tes . " Anda meningkat " . abs($persentasePerkembangan) . "% dibanding awal tes. Luangkan waktu sejenak untuk istirahat.";
                } else {
                    $statusPerkembanganText = "Tingkat emosional Anda terpantau stabil dibanding minggu lalu.";
                }
            }
        }

        // ==========================================================================
        // FITUR 4: REKOMENDASI KONTEN SINKRONISASI DATABASE SECARA KONTEKSTUAL
        // ==========================================================================
        // Menangkap string nama kategori instrumen (contoh: 'Stres', 'Burnout', 'Kecemasan', 'Depresi') untuk klausa pencarian
        $kategoriTarget = $hasil->instrumen->nama_tes; 

        // 1. QUERY ARTIKEL: Mencari maksimal 2 artikel aktif yang memiliki kemiripan teks pada kolom kategori maupun kolom judul
        $rekomendasiArtikel = Artikel::where('status', true)
            ->where(function($query) use ($kategoriTarget) {
                $query->where('kategori', 'LIKE', '%' . $kategoriTarget . '%')
                      ->orWhere('judul', 'LIKE', '%' . $kategoriTarget . '%');
            })
            ->latest()
            ->take(2)
            ->get();

        // Fallback System Artikel: Jika query khusus di atas kosong, otomatis ambil 2 artikel terbaru secara umum milik platform
        if ($rekomendasiArtikel->isEmpty()) {
            $rekomendasiArtikel = Artikel::where('status', true)->latest()->take(2)->get();
        }

        // 2. QUERY MEDITASI: Mencari maksimal 2 sesi audio/video meditasi terpublikasi berdasarkan kemiripan teks kategori atau judul
        $rekomendasiMeditasi = Meditasi::where('status', 'published')
            ->where(function($query) use ($kategoriTarget) {
                $query->where('kategori', 'LIKE', '%' . $kategoriTarget . '%')
                      ->orWhere('judul', 'LIKE', '%' . $kategoriTarget . '%');
            })
            ->latest()
            ->take(2)
            ->get();

        // Fallback System Meditasi: Jika query audio terapi spesifik kosong, otomatis ambil 2 berkas meditasi teranyar secara umum
        if ($rekomendasiMeditasi->isEmpty()) {
            $rekomendasiMeditasi = Meditasi::where('status', 'published')->latest()->take(2)->get();
        }

        // Mengirimkan seluruh variabel data hasil analisis, grafik riwayat, dan rekomendasi konten ke file view blade frontend
        return view('frontend.pasien.assessment.result', compact(
            'hasil', 
            'warnaBadge', 
            'riwayatTes', 
            'persentasePerkembangan', 
            'statusPerkembanganText',
            'rekomendasiArtikel',
            'rekomendasiMeditasi'
        ));
    }

    /**
     * MENCETAK RESUME GEJALA MEDIS KE FORMAT PDF (INTEGRASI BARU)
     * Mengambil rekam data lengkap hasil pengujian psikometrik pasien untuk di-export langsung.
     */
    public function exportPdf($id_hasil)
    {
        $pasien = Auth::user()->pasien;
        
        $hasil = HasilTes::where('id_pasien', $pasien->id_pasien)
            ->with(['instrumen'])
            ->findOrFail($id_hasil);

        $riwayatTes = HasilTes::where('id_pasien', $pasien->id_pasien)
            ->where('id_instrumen', $hasil->id_instrumen)
            ->orderBy('created_at', 'asc')
            ->take(4)
            ->get();

        $pdf = Pdf::loadView('frontend.pasien.assessment.pdf_resume', compact('hasil', 'riwayatTes', 'pasien'));
        return $pdf->download('MindHaven-Resume-Medis-' . $hasil->id_hasil . '.pdf');
    }
}