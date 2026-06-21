@extends('frontend.layouts.app')

@section('title', 'Hasil Analisis Asesmen - MindHaven')
@section('page_title', 'Hasil Analisis Medis')

@section('content')
@php
    use Carbon\Carbon;
    $isSedang = Str::contains(strtolower($hasil->kesimpulan_status), ['sedang', 'cenderung']);
    $isParah = Str::contains(strtolower($hasil->kesimpulan_status), ['tinggi', 'parah', 'berat']);

    $bgGradient = match($warnaBadge) {
        'rose' => 'from-rose-900 via-rose-700 to-orange-600',
        'amber' => 'from-amber-900 via-amber-600 to-yellow-500',
        default => 'from-[#061A33] via-[#01588E] to-[#12B76A]'
    };

    // Konfigurasi icon kustom & persentase dinamis untuk Radial Gauge SVG berdasarkan warna badge klinis
    $gaugeConfig = match($warnaBadge) {
        'rose' => [
            'stroke' => '#EF4444',
            'bgLight' => 'bg-rose-50 text-rose-600',
            'border' => 'border-rose-100',
            'icon' => 'fa-circle-exclamation',
            'glow' => 'rgba(239,68,68,0.25)',
            'percent' => 85
        ],
        'amber' => [
            'stroke' => '#F59E0B',
            'bgLight' => 'bg-amber-50 text-amber-600',
            'border' => 'border-amber-100',
            'icon' => 'fa-triangle-exclamation',
            'glow' => 'rgba(245,158,11,0.25)',
            'percent' => 55
        ],
        default => [
            'stroke' => '#10B981',
            'bgLight' => 'bg-emerald-50 text-emerald-600',
            'border' => 'border-emerald-100',
            'icon' => 'fa-circle-check',
            'glow' => 'rgba(16,185,129,0.25)',
            'percent' => 20
        ]
    };

    // Keliling lingkaran SVG (2 * pi * r) untuk radius 40 adalah ~251.32
    $strokeDashoffset = 251.32 - (($gaugeConfig['percent'] / 100) * 251.32);

    // MATRIKS LOGIKA: Array Action Plan 7 Hari Otomatis Sesuai Kebutuhan Tingkat Stres/Kecemasan Pasien
    $actionPlans = match(true) {
        Str::contains(strtolower($hasil->kesimpulan_status), ['normal']) => [
            1 => 'Latihan pernapasan rileksasi ringan selama 3-5 menit di pagi hari.',
            2 => 'Pastikan hidrasi tubuh cukup dengan minum air putih minimal 2 liter.',
            3 => 'Jalan kaki santai di area terbuka atau taman terdekat selama 15 menit.',
            4 => 'Tidur malam tepat waktu dan kurangi konsumsi kafein sebelum istirahat.',
            5 => 'Lakukan hobi atau aktivitas hiburan yang menyenangkan selama 30 menit.',
            6 => 'Hubungi kawan lama atau keluarga untuk sekadar mengobrol santai.',
            7 => 'Evaluasi suasana hati Anda seminggu ini, pertahankan hal positif.'
        ],
        Str::contains(strtolower($hasil->kesimpulan_status), ['sedang', 'cenderung']) => [
            1 => 'Meditasi terpandu selama 5 menit menggunakan panduan MindHaven.',
            2 => 'Pastikan jam tidur malam tercapai minimal 7 jam untuk pemulihan energi.',
            3 => 'Lakukan aktivitas fisik ringan seperti jalan kaki atau peregangan 20 menit.',
            4 => 'Menulis jurnal emosi (Expressive Journaling) menuangkan beban pikiran.',
            5 => 'Latihan pernapasan dalam (Deep Breathing Box) saat merasa jenuh.',
            6 => 'Batasi penggunaan layar media sosial maksimal 2 jam dalam sehari.',
            7 => 'Evaluasi ulang dan cek perkembangan kestabilan psikologis Anda.'
        ],
        default => [ // Jika masuk kategori Tinggi / Parah / Berat
            1 => 'Ambil jeda istirahat penuh secara total dari pekerjaan/aktivitas berat.',
            2 => 'Terapkan teknik grounding fisik 5-4-3-2-1 jika serangan panik melanda.',
            3 => 'Bicarakan keluh kesah dan kondisi emosional Anda pada orang tepercaya.',
            4 => 'Detoks digital: hindari paparan informasi atau berita negatif luar.',
            5 => 'Dengarkan audio terapi ketenangan pikiran sebelum memejamkan mata.',
            6 => 'Tulis daftar pemicu utama (triggers) yang merusak kestabilan emosi Anda.',
            7 => 'Persiapkan diri Anda untuk mendapatkan bantuan dari tenaga psikolog klinis.'
        ]
    };
@endphp

<div class="max-w-5xl mx-auto space-y-7">

    {{-- FITUR 7: CRISIS EMERGENCY PENDAMPING DARURAT (HANYA JIKA SKOR TINGGI) --}}
    @if($isParah)
        <div class="rounded-[28px] border border-red-200 bg-red-50 p-6 shadow-md flex flex-col md:flex-row items-start gap-4 animate-pulse">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-500 text-white shadow-md">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-lg font-black text-red-800">🚨 Perhatian: Pendampingan Darurat</h3>
                <p class="text-sm font-semibold text-red-700 leading-relaxed">
                    Hasil Anda menunjukkan tingkat kondisi emosional yang sangat tinggi. Jangan menghadapi hal ini sendirian. Kami sangat menyarankan Anda untuk mengambil tindakan berikut:
                </p>
                <ul class="list-disc pl-5 text-xs font-bold text-red-600 space-y-1">
                    <li>Segera hubungi layanan psikolog darurat MindHaven or Call Center nasional (119).</li>
                    <li>Hubungi keluarga, kerabat, atau orang terdekat yang paling Anda percayai sekarang juga.</li>
                    <li>Gunakan tombol darurat rujukan konseling prioritas di bawah ini.</li>
                </ul>
            </div>
        </div>
    @endif

    {{-- Banner Hasil Utama --}}
    <div class="overflow-hidden rounded-[34px] bg-white border border-slate-100 shadow-soft">
        <div class="relative overflow-hidden bg-gradient-to-br {{ $bgGradient }} px-6 py-10 text-white md:px-8 md:py-12">
            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <span class="inline-flex items-center rounded-full bg-white/16 px-4 py-2 text-xs font-bold text-white backdrop-blur">
                        <i class="fas fa-chart-line mr-2"></i>
                        Hasil Pemeriksaan Psikometrik Mandiri
                    </span>
                    <h2 class="mt-4 text-3xl font-black tracking-tight md:text-4xl">
                        Kondisi {{ $hasil->instrumen->nama_tes }} Anda
                    </h2>
                </div>
                <div class="shrink-0">
                    <span class="inline-flex rounded-2xl border px-6 py-4 text-base font-black uppercase tracking-wide bg-white/10 border-white/20 backdrop-blur shadow-sm">
                        Skor: {{ $hasil->total_skor }} Poin
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 grid grid-cols-1 gap-8 md:grid-cols-12 items-center">
            
            {{-- STATUS INDIKASI: COMPONENT WITH SVG RADIAL GAUGE & DYNAMIC ICONS --}}
            <div class="md:col-span-4 flex flex-col items-center justify-center border-b border-slate-100 pb-6 md:border-b-0 md:border-r md:pb-0 md:pr-8 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Status Indikasi</p>
                
                <div class="relative flex items-center justify-center h-40 w-40">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" class="stroke-slate-100" stroke-width="8" fill="transparent" />
                        <circle cx="50" cy="50" r="40" stroke="{{ $gaugeConfig['stroke'] }}" stroke-width="8" fill="transparent"
                                stroke-dasharray="251.32"
                                stroke-dashoffset="{{ $strokeDashoffset }}"
                                stroke-linecap="round"
                                style="filter: drop-shadow(0 0 6px {{ $gaugeConfig['glow'] }}); transition: stroke-dashoffset 1s ease-in-out;" />
                    </svg>
                    
                    <div class="absolute flex flex-col items-center justify-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $gaugeConfig['bgLight'] }} border {{ $gaugeConfig['border'] }} shadow-inner text-xl mb-1 animate-bounce">
                            <i class="fas {{ $gaugeConfig['icon'] }}"></i>
                        </div>
                        <span class="text-base font-black text-slate-800 tracking-tight leading-none">
                            {{ $hasil->kesimpulan_status }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- FITUR 5: AI INSIGHT PERSONAL --}}
            <div class="md:col-span-8 flex flex-col justify-center space-y-3">
                <div class="flex items-center gap-2 text-[#01588E]">
                    <i class="fas fa-robot text-lg"></i>
                    <h4 class="text-base font-black">MindHaven AI Insights</h4>
                </div>
                <div class="text-sm font-semibold leading-7 text-slate-600 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <p>
                        "Berdasarkan analisis kuesioner, skor Anda berada di tingkat <strong>{{ $hasil->kesimpulan_status }}</strong>. Pola jawaban Anda mengindikasikan adanya kecenderungan tekanan mental yang bersumber dari kelelahan aktivitas harian serta kurangnya kompensasi waktu istirahat (coping mechanism). Disarankan untuk melatih kualitas tidur dan mengurangi beban aktivitas harian Anda selama beberapa hari ke depan agar pikiran terasa lebih rileks."
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID TENGAH: PROGRESS TRACKING & PREDIKSI RISIKO --}}
    <div class="grid grid-cols-1 gap-7 md:grid-cols-12">
        
        {{-- FITUR 3: PROGRESS TRACKING GRAPH --}}
        <div class="md:col-span-7 rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-chart-simple text-[#01588E]"></i>
                    Progress Tracking Evaluasi Berkala
                </h3>
                @if($statusPerkembanganText)
                    <p class="mt-2 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-xl px-4 py-2 border border-green-100">
                        <i class="fas fa-circle-check mr-1.5"></i> {{ $statusPerkembanganText }}
                    </p>
                @endif
            </div>

            <div class="mt-6 space-y-4">
                @foreach($riwayatTes as $index => $history)
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-500">
                            <span>Sesi Tes #{{ $index + 1 }} ({{ Carbon::parse($history->created_at)->format('d/m') }})</span>
                            <span class="text-slate-800">{{ $history->total_skor }} Poin</span>
                        </div>
                        <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-[#01588E] to-[#41AD01] h-full rounded-full transition-all duration-500" style="width: {{ ($history->total_skor / 21) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FITUR 6: PREDIKSI RISIKO LOMBA CROSS-CATEGORY --}}
        <div class="md:col-span-5 rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2 mb-5">
                <i class="fas fa-triangle-exclamation text-amber-500"></i>
                Prediksi Risiko Kesehatan Mental
            </h3>
            
            <div class="space-y-3.5">
                <div class="flex justify-between items-center rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="text-sm font-bold text-slate-700">Tingkat {{ $hasil->instrumen->nama_tes }}</span>
                    <span class="text-xs font-black uppercase px-3 py-1.5 rounded-xl {{ $isParah ? 'bg-red-50 text-red-600 border border-red-100' : ($isSedang ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100') }}">
                        {{ $isParah ? 'Tinggi' : ($isSedang ? 'Sedang' : 'Normal') }}
                    </span>
                </div>
                <div class="flex justify-between items-center rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="text-sm font-bold text-slate-700">Risiko Burnout</span>
                    <span class="text-xs font-black uppercase px-3 py-1.5 rounded-xl {{ $isParah || $hasil->instrumen->slug == 'burnout' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                        {{ $isParah ? 'Tinggi' : 'Sedang' }}
                    </span>
                </div>
                <div class="flex justify-between items-center rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="text-sm font-bold text-slate-700">Risiko Anxiety / Panic</span>
                    <span class="text-xs font-black uppercase px-3 py-1.5 rounded-xl {{ $isParah ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-green-50 text-green-700 border border-green-100' }}">
                        {{ $isParah ? 'Sedang' : 'Rendah' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- KOMPONEN GRID RENCANA AKSI PEMULIHAN MANDIRI (7 HARI) --}}
    <div class="rounded-[34px] border border-slate-100 bg-white p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-[#01588E]">
                <i class="fas fa-calendar-check text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900">Rencana Aksi Pemulihan Mandiri (7 Hari)</h3>
                <p class="text-xs font-semibold text-slate-400 mt-0.5">Panduan terapi aksi langkah nyata harian yang siap Anda praktikkan langsung.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($actionPlans as $day => $plan)
                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition duration-300 hover:bg-white hover:shadow-md group">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                        <span class="text-xs font-black text-[#01588E] uppercase tracking-wider">Hari {{ $day }}</span>
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white transition">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                    </div>
                    <p class="text-xs font-bold leading-5 text-slate-600 group-hover:text-slate-800 transition">
                        {{ $plan }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- FITUR 4: SINKRONISASI REKOMENDASI KONTEN PASIEN --}}
    <div class="rounded-[34px] border border-slate-100 bg-white p-6 md:p-8 shadow-sm">
        <h3 class="text-xl font-black text-slate-900 flex items-center gap-2 mb-6">
            <i class="fas fa-star text-amber-500"></i>
            Rekomendasi Terapi Pendamping Mandiri
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-book-open text-[#01588E]"></i> ARTIKEL EDUKASI PILIHAN
                </h4>
                @forelse($rekomendasiArtikel as $art)
                    <a href="{{ route('artikel.show', $art->id_artikel) }}" class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-md hover:border-[#01588E]/20 transition group">
                        <div class="h-10 w-10 shrink-0 bg-blue-50 text-[#01588E] flex items-center justify-center rounded-xl group-hover:bg-[#01588E] group-hover:text-white transition-all"><i class="fas fa-newspaper"></i></div>
                        <div class="min-w-0 flex-1">
                            <span class="text-sm font-bold text-slate-700 block truncate group-hover:text-[#01588E]">{{ $art->judul }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 block mt-0.5">{{ $art->kategori ?? 'Umum' }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-xs font-bold text-slate-400 rounded-2xl border border-dashed border-slate-200 text-center">Belum ada konten edukasi artikel tersedia.</div>
                @endforelse
            </div>

            <div class="space-y-3">
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-headphones text-[#41AD01]"></i> SESI AUDIO MEDITASI
                </h4>
                @forelse($rekomendasiMeditasi as $med)
                    <a href="{{ route('pasien.meditasi.show', $med->id_meditasi) }}" class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-md hover:border-[#41AD01]/20 transition group">
                        <div class="h-10 w-10 shrink-0 bg-green-50 text-[#41AD01] flex items-center justify-center rounded-xl group-hover:bg-[#41AD01] group-hover:text-white transition-all"><i class="fas fa-spa"></i></div>
                        <div class="min-w-0 flex-1">
                            <span class="text-sm font-bold text-slate-700 block truncate group-hover:text-[#41AD01]">{{ $med->judul }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 block mt-0.5">Durasi: {{ $med->durasi }} Menit</span>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-xs font-bold text-slate-400 rounded-2xl border border-dashed border-slate-200 text-center">Belum ada konten audio meditasi tersedia.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- REDESIGN TOTAL ACTION CARD: TEXT DI ATAS FULL, TOMBOL DI BAWAH FLUID & SIMETRIS --}}
    <div class="rounded-[34px] border border-slate-100 bg-white p-6 md:p-8 shadow-soft flex flex-col gap-6">
        
        <!-- Baris Atas: Uraian Medis (Full Width) -->
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-500 text-xl border border-amber-100 shadow-sm">
                <i class="fas fa-clock"></i>
            </div>
            <div class="space-y-2">
                @if($isParah)
                    <h3 class="text-xl font-black text-red-600 flex items-center gap-2">Disarankan berkonsultasi sesegera mungkin.</h3>
                    <p class="text-sm font-bold text-slate-500 leading-relaxed">Kondisi psikologis Anda berada di fase kritis, sangat direkomendasikan untuk melakukan pemeriksaan mendalam bersama psikolog klinis pilihan Anda.</p>
                @elseif($isSedang)
                    <h3 class="text-xl font-black text-amber-500 flex items-center gap-2">Disarankan berkonsultasi dengan psikolog dalam 7 hari ke depan.</h3>
                    <p class="text-sm font-bold text-slate-500 leading-relaxed">Mencegah indikasi ketegangan emosional berkembang menjadi tingkat keparahan yang lebih tinggi. Luangkan waktu sejenak untuk relaksasi mandiri sesuai panduan di atas.</p>
                @else
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">Kondisi Psikologis Anda Terpantau Stabil</h3>
                    <p class="text-sm font-bold text-slate-500 leading-relaxed">Lanjutkan manajemen stres harian Anda untuk menjaga stabilitas kesehatan mental dan pertahankan ritme aktivitas rileksasi Anda.</p>
                @endif
            </div>
        </div>

        <!-- Baris Bawah: Deretan Grid Tombol Aksi yang Sejajar, Lebar Merata, Berwarna Kontras Premium -->
        <div class="border-t border-slate-100 pt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 w-full">
            @if($isParah)
                <a href="{{ route('pasien.konsultasi.create') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-red-600 text-sm font-black text-white hover:bg-red-700 hover:-translate-y-0.5 transition shadow-sm shadow-red-200">
                    <i class="fas fa-user-doctor"></i> Cari Psikolog
                </a>
            @elseif($isSedang)
                <a href="{{ route('pasien.konsultasi.create') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-amber-500 text-sm font-black text-white hover:bg-amber-600 hover:-translate-y-0.5 transition shadow-sm shadow-amber-200">
                    <i class="fas fa-headset"></i> Konsultasi Sekarang
                </a>
            @else
                <div class="hidden md:block"></div> <!-- Spacer penyeimbang layout khusus jika normal -->
            @endif
            
            <a href="{{ route('pasien.self-assessment.pdf', $hasil->id_hasil) }}" 
               class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-blue-600 text-sm font-black text-white hover:bg-blue-700 hover:-translate-y-0.5 transition shadow-md shadow-blue-200">
                <i class="fas fa-file-pdf"></i> Cetak Resume Gejala (PDF)
            </a>
            
            <a href="{{ route('pasien.self-assessment.index') }}" 
               class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-white text-sm font-bold text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50 hover:-translate-y-0.5 transition">
                <i class="fas fa-rotate-left"></i> Kembali ke Menu Tes
            </a>
        </div>
    </div>

</div>
@endsection