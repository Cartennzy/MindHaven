@extends('frontend.layouts.app')

@section('title', 'Buat Konsultasi - MindHaven')
@section('page_title', 'Buat Konsultasi')
@section('page_subtitle', 'Pilih topik, psikolog, lalu lihat detail sebelum pembayaran.')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $metodeKomunikasi = [
        [
            'nama' => 'Konseling via Chat',
            'value' => 'chat',
            'deskripsi' => 'Konsultasi nyaman melalui pesan teks.',
            'icon' => 'fas fa-comments',
        ],
        [
            'nama' => 'Buat Janji Tatap Muka',
            'value' => 'temu_janji',
            'deskripsi' => 'Atur jadwal konsultasi langsung dengan psikolog.',
            'icon' => 'fas fa-location-dot',
        ],
        [
            'nama' => 'Konsultasi Lewat Video',
            'value' => 'video_call',
            'deskripsi' => 'Konsultasi jarak jauh melalui panggilan video.',
            'icon' => 'fas fa-video',
        ],
    ];

    $gambarTopik = [
        'Stres' => asset('assets/images/stress.png'),
        'Gangguan Kecemasan' => asset('assets/images/gangguan_kecemasan.png'),
        'Depresi' => asset('assets/images/depresi.png'),
        'Keluarga & Hubungan' => asset('assets/images/hubungan_keluarga.png'),
        'Trauma' => asset('assets/images/trauma.png'),
        'Gangguan Mood' => asset('assets/images/gangguan_mood.png'),
    ];

    $topicKeywords = [
        'stres' => 'stres, stress, tekanan, burnout, kelelahan mental',
        'gangguan_kecemasan' => 'gangguan_kecemasan, gangguan kecemasan, kecemasan, cemas, anxiety, panic, panic attack, panik, overthinking, emotional regulation, regulasi emosi',
        'depresi' => 'depresi, depression, sedih, kehilangan motivasi',
        'keluarga_hubungan' => 'keluarga_hubungan, keluarga hubungan, keluarga, hubungan, relationship, relasi, pasangan, pertemanan',
        'trauma' => 'trauma, traumatis, ptsd, luka emosional',
        'gangguan_mood' => 'gangguan_mood, gangguan mood, mood, mood swing, suasana hati, emosi tidak stabil, bipolar, mood disorder',
        'lainnya' => 'lainnya, umum, general, konseling umum',
    ];
@endphp

<div class="space-y-8">

    @if (session('success'))
        <div class="rounded-3xl border border-green-100 bg-green-50 px-6 py-5 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-3xl border border-red-100 bg-red-50 px-6 py-5 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[32px] bg-gradient-to-br from-[#f8fbff] via-white to-[#f3fff0] p-6 shadow-soft md:p-8">
        <div class="mb-6">
            <h2 class="text-3xl font-black text-slate-800 md:text-4xl">
                Jelajahi Topik Konseling Umum
            </h2>
            <p class="mt-2 text-base font-medium text-slate-500">
                Pilih topik yang paling sesuai dengan kondisi Anda.
            </p>

            @error('topik_konsultasi')
                <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
            @foreach($topikKonseling as $topik)
                @php
                    $keywordTopik = strtolower($topik['keyword']);
                    $keywordGabungan = $topicKeywords[$keywordTopik] ?? $keywordTopik;
                @endphp

                <button type="button"
                        class="topic-card group rounded-3xl border-2 {{ old('topik_konsultasi') === $topik['nama'] ? 'active' : '' }} {{ $errors->has('topik_konsultasi') ? 'error-box' : 'border-slate-100' }} bg-white p-5 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#01588E]/40 hover:shadow-xl"
                        data-topic="{{ $topik['nama'] }}"
                        data-keyword="{{ $keywordTopik }}"
                        data-keywords="{{ strtolower($keywordGabungan) }}"
                        data-description="{{ $topik['deskripsi'] }}">

                    <div class="topic-icon mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-[#EAF4FA] transition duration-300">
                        @if(isset($gambarTopik[$topik['nama']]))
                            <img src="{{ $gambarTopik[$topik['nama']] }}"
                                 alt="{{ $topik['nama'] }}"
                                 class="h-full w-full rounded-full object-cover">
                        @else
                            <i class="{{ $topik['icon'] }} text-3xl text-[#01588E]"></i>
                        @endif
                    </div>

                    <h3 class="topic-title mt-4 text-sm font-black text-slate-800">
                        {{ $topik['nama'] }}
                    </h3>
                </button>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-center">
            <div>
                <h3 id="selectedTopicTitle" class="text-2xl font-black text-slate-800">
                    Pilih salah satu topik terlebih dahulu.
                </h3>

                <p id="selectedTopicDescription" class="mt-4 max-w-3xl text-base font-medium leading-8 text-slate-600">
                    Setelah topik dipilih, lanjutkan dengan memilih psikolog yang sesuai.
                </p>

                <button type="button"
                        id="findExpertBtn"
                        class="mt-6 inline-flex items-center justify-center rounded-2xl bg-[#41AD01] px-8 py-4 text-sm font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#329000]">
                    Cari Ahli
                    <i class="fas fa-arrow-right ml-3"></i>
                </button>
            </div>

            <div class="hidden justify-center lg:flex">
                <div class="relative h-72 w-72 rounded-full bg-[#01588E]/10">
                    <div class="absolute -right-4 top-8 h-28 w-28 rounded-full bg-[#41AD01]/20"></div>
                    <div class="absolute bottom-4 left-2 h-24 w-24 rounded-full bg-[#01588E]/20"></div>
                    <div class="absolute inset-8 flex items-center justify-center rounded-full bg-white shadow-xl">
                        <i class="fas fa-user-doctor text-7xl text-[#01588E]"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('pasien.konsultasi.store') }}" method="POST" id="consultationForm" novalidate>
        @csrf

        <input type="hidden" name="topik_konsultasi" id="topikKonselingInput" value="{{ old('topik_konsultasi') }}">
        <input type="hidden" name="metode_konsultasi" id="metodeKomunikasiInput" value="{{ old('metode_konsultasi') }}">
        <input type="hidden" name="id_psikolog" id="psikologIdInput" value="{{ old('id_psikolog') }}">

        <div id="communicationSection" class="hidden space-y-6">
            <div class="mt-10">
                <h2 class="text-3xl font-black text-slate-800">
                    Pilih Metode Konsultasi
                </h2>
                <p class="mt-2 text-base font-medium text-slate-500">
                    Konseling yang efektif berawal dari rasa nyaman.
                </p>

                @error('metode_konsultasi')
                    <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach($metodeKomunikasi as $metode)
                    <button type="button"
                            class="method-card rounded-[32px] border-2 {{ old('metode_konsultasi') === $metode['value'] ? 'active' : '' }} {{ $errors->has('metode_konsultasi') ? 'error-box' : 'border-slate-100' }} bg-white p-8 text-left shadow-soft transition duration-300 hover:-translate-y-1 hover:border-[#01588E]/40 hover:shadow-xl"
                            data-method="{{ $metode['value'] }}">

                        <div class="method-icon mb-6 flex h-24 w-24 items-center justify-center rounded-[28px] bg-[#EAF4FA] transition duration-300">
                            <i class="{{ $metode['icon'] }} text-5xl text-[#01588E]"></i>
                        </div>

                        <h3 class="method-title text-2xl font-black text-slate-800">
                            {{ $metode['nama'] }}
                        </h3>

                        <p class="method-desc mt-4 text-lg font-medium leading-9 text-slate-500">
                            {{ $metode['deskripsi'] }}
                        </p>

                        <div class="method-action mt-8 inline-flex items-center text-lg font-black text-[#41AD01]">
                            Pilih Metode
                            <i class="fas fa-arrow-right ml-3"></i>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <div id="expertSection" class="hidden space-y-6">
            <div class="mt-10 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-3xl font-black text-slate-800">
                        Pilih Psikolog
                    </h2>
                    <p class="mt-2 text-base font-medium text-slate-500">
                        Psikolog berikut sudah diverifikasi oleh admin MindHaven.
                    </p>

                    @error('id_psikolog')
                        <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-2xl bg-[#01588E]/10 px-5 py-3 text-sm font-black text-[#01588E]">
                    <span id="expertCount">{{ $psikologs->count() }}</span> psikolog tersedia
                </div>
            </div>

            <div id="psychologistGrid" class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                @forelse($psikologs as $psikolog)
                    @php
                        $foto = $psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)
                            ? asset('storage/' . $psikolog->foto_profil)
                            : null;

                        $spesialisasiLower = strtolower($psikolog->spesialisasi ?? '');
                        $bioLower = strtolower($psikolog->bio ?? '');
                        $spesialisasiSearch = $spesialisasiLower . ' ' . str_replace('_', ' ', $spesialisasiLower) . ' ' . str_replace(' ', '_', $spesialisasiLower) . ' ' . $bioLower;

                        // Variabel penampung rata rating harian dinamis
                        $ratingAvg = round($psikolog->rata_rating ?? 0, 1);
                        $reviewCount = $psikolog->total_review ?? 0;
                    @endphp

                    <button type="button"
                            class="psychologist-card rounded-3xl border-2 {{ old('id_psikolog') == $psikolog->id_psikolog ? 'active' : '' }} {{ $errors->has('id_psikolog') ? 'error-box' : 'border-slate-100' }} bg-white p-5 text-left shadow-soft transition hover:-translate-y-1 hover:border-[#01588E]/40 hover:shadow-xl"
                            data-id="{{ $psikolog->id_psikolog }}"
                            data-name="{{ $psikolog->nama_lengkap }}"
                            data-specialization="{{ $spesialisasiSearch }}"
                            data-detail-url="{{ route('pasien.konsultasi.detail-psikolog', ['psikolog' => $psikolog->id_psikolog]) }}">
                        <div class="flex gap-5">
                            @if($foto)
                                <img src="{{ $foto }}"
                                     alt="{{ $psikolog->nama_lengkap }}"
                                     class="h-32 w-28 rounded-3xl object-cover shadow-md">
                            @else
                                <div class="flex h-32 w-28 shrink-0 items-center justify-center rounded-3xl bg-[#01588E]/10 text-4xl font-black text-[#01588E] shadow-md">
                                    {{ strtoupper(substr($psikolog->nama_lengkap, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-black leading-7 text-slate-800">
                                    {{ $psikolog->nama_lengkap }}
                                </h3>

                                <p class="mt-1 text-sm font-bold text-slate-500">
                                    {{ $psikolog->spesialisasi }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-black text-slate-600">
                                        <i class="fas fa-briefcase mr-1"></i>
                                        {{ $psikolog->pengalaman }} tahun
                                    </span>

                                    <span class="rounded-xl bg-green-100 px-3 py-2 text-xs font-black text-green-700">
                                        Terverifikasi
                                    </span>

                                    {{-- Sinkronisasi Badge Rating Psikolog Terbaik --}}
                                    <span class="rounded-xl bg-amber-50 border border-amber-200 px-3 py-2 text-xs font-black text-amber-700 flex items-center gap-1">
                                        <i class="fas fa-star text-amber-400"></i>
                                        @if($reviewCount > 0)
                                            {{ $ratingAvg }} ({{ $reviewCount }} Ulasan)
                                        @else
                                            Baru
                                        @endif
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <div class="text-lg font-black text-[#01588E]">
                                        Rp {{ number_format($psikolog->biaya_konsultasi, 0, ',', '.') }}
                                    </div>

                                    <span class="rounded-2xl bg-[#41AD01] px-5 py-3 text-sm font-black text-white">
                                        Pilih
                                    </span>
                                </div>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="rounded-3xl bg-white p-8 text-center shadow-soft xl:col-span-2">
                        <p class="text-sm font-bold text-slate-500">
                            Belum ada psikolog yang terverifikasi.
                        </p>
                    </div>
                @endforelse
            </div>

            <div id="noPsychologistMessage" class="hidden rounded-3xl border border-yellow-100 bg-yellow-50 p-6 text-sm font-bold text-yellow-700">
                Belum ada psikolog yang sesuai dengan topik ini. Silakan pilih topik lain atau pilih kategori Lainnya.
            </div>
        </div>

        <div id="dateSection" class="hidden mt-10 rounded-[32px] bg-white p-6 shadow-soft md:p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-black text-slate-800">
                    Pilih Tanggal Tersedia
                </h2>
                <p class="mt-2 text-base font-medium text-slate-500">
                    Pilih tanggal konsultasi yang tersedia untuk psikolog tersebut.
                </p>

                @error('tanggal_konsultasi')
                    <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="availableDateGrid" class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:grid-cols-4"></div>

            <input type="hidden"
                   name="tanggal_konsultasi"
                   id="tanggalKonsultasiInput"
                   value="{{ old('tanggal_konsultasi') }}">
        </div>

        <div id="scheduleSection" class="hidden mt-10 rounded-[32px] bg-white p-6 shadow-soft md:p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-black text-slate-800">
                    Lengkapi Jadwal Konsultasi
                </h2>
                <p class="mt-2 text-base font-medium text-slate-500">
                    Pastikan data konsultasi sudah sesuai sebelum disimpan.
                </p>
            </div>

            <div class="mb-6 rounded-3xl bg-[#01588E]/5 p-5">
                <p class="text-sm font-black text-[#01588E]">
                    Ringkasan Pilihan
                </p>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400">Topik</p>
                        <h4 id="summaryTopic" class="font-black text-slate-800">-</h4>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Metode</p>
                        <h4 id="summaryMethod" class="font-black text-slate-800">-</h4>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Psikolog</p>
                        <h4 id="summaryPsychologist" class="font-black text-slate-800">-</h4>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-slate-400">Tanggal</p>
                        <h4 id="summaryDate" class="font-black text-slate-800">-</h4>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label">Keluhan</label>
                <textarea name="keluhan"
                          rows="6"
                          class="form-input {{ $errors->has('keluhan') ? 'error-box' : '' }}"
                          placeholder="Ceritakan apa yang sedang Anda rasakan hari ini...">{{ old('keluhan') }}</textarea>

                @error('keluhan')
                    <p class="mt-2 text-sm font-black text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Jam Konsultasi</label>

                <div class="relative">
                    <input type="time"
                           name="jam_konsultasi"
                           value="{{ old('jam_konsultasi') }}"
                           class="form-input pr-20 {{ $errors->has('jam_konsultasi') ? 'error-box' : '' }}">

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-sm font-black text-[#01588E]">
                        WIB
                    </div>
                </div>

                @error('jam_konsultasi')
                    <p class="mt-2 text-sm font-black text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="btn-success">
                    Lanjut ke Pembayaran
                </button>

                <a href="{{ route('pasien.konsultasi.index') }}" class="btn-primary">
                    Kembali
                </a>
            </div>
        </div>
    </form>
</div>

<style>
    .topic-card.active,
    .method-card.active {
        border-color:#01588E !important;
        background:linear-gradient(135deg,#01588E 0%,#0878b8 100%) !important;
        box-shadow:0 22px 45px rgba(1,88,142,.28) !important;
        transform:translateY(-6px);
    }

    .topic-card.active .topic-icon,
    .method-card.active .method-icon {
        background:#ffffff !important;
    }

    .topic-card.active .topic-title,
    .method-card.active .method-title,
    .method-card.active .method-desc,
    .method-card.active .method-action {
        color:#ffffff !important;
    }

    .method-card.active .method-icon i {
        color:#01588E !important;
    }

    .psychologist-card.active,
    .date-card.active {
        border-color:#01588E !important;
        background:linear-gradient(135deg,#ffffff 0%,#eef8ff 100%) !important;
        box-shadow:0 22px 45px rgba(1,88,142,.20) !important;
        transform:translateY(-6px);
    }

    .psychologist-card.hidden-by-topic {
        display:none;
    }

    .error-box {
        border-color:#EF4444 !important;
        background:#FFF7F7 !important;
        box-shadow:0 0 0 4px rgba(239,68,68,.10) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const topicCards = document.querySelectorAll('.topic-card');
        const methodCards = document.querySelectorAll('.method-card');
        const psychologistCards = document.querySelectorAll('.psychologist-card');

        const findExpertBtn = document.getElementById('findExpertBtn');
        const communicationSection = document.getElementById('communicationSection');
        const expertSection = document.getElementById('expertSection');
        const dateSection = document.getElementById('dateSection');
        const scheduleSection = document.getElementById('scheduleSection');

        const topikInput = document.getElementById('topikKonselingInput');
        const metodeInput = document.getElementById('metodeKomunikasiInput');
        const psikologInput = document.getElementById('psikologIdInput');
        const tanggalInput = document.getElementById('tanggalKonsultasiInput');

        const selectedTopicTitle = document.getElementById('selectedTopicTitle');
        const selectedTopicDescription = document.getElementById('selectedTopicDescription');

        const summaryTopic = document.getElementById('summaryTopic');
        const summaryMethod = document.getElementById('summaryMethod');
        const summaryPsychologist = document.getElementById('summaryPsychologist');
        const summaryDate = document.getElementById('summaryDate');

        const expertCount = document.getElementById('expertCount');
        const noPsychologistMessage = document.getElementById('noPsychologistMessage');
        const availableDateGrid = document.getElementById('availableDateGrid');

        const consultationForm = document.getElementById('consultationForm');

        let selectedPsychologistName = '';

        function getJamWib() {
            const formatter = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            const waktu = formatter.format(new Date());
            const parts = waktu.replace('.', ':').split(':');

            return parseInt(parts[0], 10);
        }

        function isBookingClosed() {
            const jamWib = getJamWib();

            return jamWib < 6 || jamWib >= 21;
        }

        function showBookingClosedAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Booking Konsultasi Ditutup',
                text: 'Akses konsultasi hanya tersedia dari pukul 06.00 sampai 21.00 WIB. Silakan kembali pada jam operasional.',
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#01588E',
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: '#FFFFFF',
                color: '#1E293B'
            }).then(() => {
                window.location.href = "{{ route('pasien.konsultasi.index') }}";
            });
        }

        function showMindHavenAlert(title, text) {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#1E293B'
            });
        }

        function normalizeText(text) {
            return String(text || '')
                .toLowerCase()
                .replace(/_/g, ' ')
                .replace(/-/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function updateSummary() {
            summaryTopic.textContent = topikInput.value || '-';
            summaryMethod.textContent = metodeInput.value || '-';
            summaryPsychologist.textContent = selectedPsychologistName || '-';
            summaryDate.textContent = tanggalInput.value || '-';
        }

        function filterPsychologists(keyword, keywordsText) {
            let visibleCount = 0;

            const normalizedKeyword = normalizeText(keyword);
            const keywordList = String(keywordsText || keyword)
                .split(',')
                .map(item => normalizeText(item))
                .filter(Boolean);

            psychologistCards.forEach(function (card) {
                const specialization = normalizeText(card.dataset.specialization || '');
                const isOther = normalizedKeyword === 'lainnya';

                // Peningkatan akurasi pencocokan kata kunci agar sinkron dengan database admin
                const isMatch = keywordList.some(function (item) {
                    return specialization.includes(item) || item.includes(specialization);
                });

                if (isOther || isMatch) {
                    card.classList.remove('hidden-by-topic');
                    visibleCount++;
                } else {
                    card.classList.add('hidden-by-topic');
                    card.classList.remove('active');
                }
            });

            expertCount.textContent = visibleCount;
            noPsychologistMessage.classList.toggle('hidden', visibleCount !== 0);
        }

        function formatTanggal(date) {
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function toDateInputValue(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function generateAvailableDates() {
            availableDateGrid.innerHTML = '';
            tanggalInput.value = '';

            const today = new Date();
            let totalDate = 0;

            for (let i = 1; i <= 14; i++) {
                const date = new Date();
                date.setDate(today.getDate() + i);

                const day = date.getDay();

                if (day === 0) {
                    continue;
                }

                totalDate++;

                const dateValue = toDateInputValue(date);

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'date-card rounded-3xl border-2 border-slate-100 bg-white p-5 text-left shadow-sm transition hover:-translate-y-1 hover:border-[#01588E]/40 hover:shadow-xl';
                button.dataset.date = dateValue;

                button.innerHTML = `
                    <p class="text-sm font-black text-[#01588E]">Tersedia</p>
                    <h3 class="mt-2 text-lg font-black text-slate-800">${formatTanggal(date)}</h3>
                    <p class="mt-2 text-sm font-bold text-slate-500">Pilih tanggal ini</p>
                `;

                button.addEventListener('click', function () {
                    if (isBookingClosed()) {
                        showBookingClosedAlert();
                        return;
                    }

                    document.querySelectorAll('.date-card').forEach(function (item) {
                        item.classList.remove('active');
                    });

                    button.classList.add('active');
                    tanggalInput.value = button.dataset.date;

                    scheduleSection.classList.remove('hidden');
                    updateSummary();

                    scheduleSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });

                availableDateGrid.appendChild(button);
            }

            if (totalDate === 0) {
                availableDateGrid.innerHTML = `
                    <div class="rounded-3xl border border-yellow-100 bg-yellow-50 p-6 text-sm font-bold text-yellow-700 md:col-span-3 xl:col-span-4">
                        Belum ada tanggal tersedia untuk psikolog ini.
                    </div>
                `;
            }
        }

        topicCards.forEach(function (card) {
            card.addEventListener('click', function () {
                if (isBookingClosed()) {
                    showBookingClosedAlert();
                    return;
                }

                topicCards.forEach(function (item) {
                    item.classList.remove('active');
                    item.classList.remove('error-box');
                });

                card.classList.add('active');

                const topic = card.dataset.topic;
                const keyword = card.dataset.keyword;
                const keywordsText = card.dataset.keywords;
                const description = card.dataset.description;

                topikInput.value = topic;
                selectedTopicTitle.textContent = topic;
                selectedTopicDescription.textContent = description;

                metodeInput.value = '';
                psikologInput.value = '';
                tanggalInput.value = '';
                selectedPsychologistName = '';

                methodCards.forEach(function (item) {
                    item.classList.remove('active');
                });

                psychologistCards.forEach(function (item) {
                    item.classList.remove('active');
                });

                expertSection.classList.add('hidden');
                communicationSection.classList.add('hidden');
                dateSection.classList.add('hidden');
                scheduleSection.classList.add('hidden');

                filterPsychologists(keyword, keywordsText);
                updateSummary();
            });
        });

        findExpertBtn.addEventListener('click', function () {
            if (isBookingClosed()) {
                showBookingClosedAlert();
                return;
            }

            if (!topikInput.value) {
                showMindHavenAlert('Topik Belum Dipilih', 'Silakan pilih topik konseling terlebih dahulu.');
                return;
            }

            expertSection.classList.remove('hidden');
            communicationSection.classList.add('hidden');
            dateSection.classList.add('hidden');
            scheduleSection.classList.add('hidden');

            expertSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });

        psychologistCards.forEach(function (card) {
            card.addEventListener('click', function () {
                if (isBookingClosed()) {
                    showBookingClosedAlert();
                    return;
                }

                if (!topikInput.value) {
                    showMindHavenAlert('Topik Belum Dipilih', 'Silakan pilih topik konseling terlebih dahulu.');
                    return;
                }

                psychologistCards.forEach(function (item) {
                    item.classList.remove('active');
                    item.classList.remove('error-box');
                });

                card.classList.add('active');

                psikologInput.value = card.dataset.id;
                selectedPsychologistName = card.dataset.name;

                updateSummary();

                const selectedTopik = topikInput.value;

                setTimeout(function () {
                    const detailUrl = new URL(card.dataset.detailUrl, window.location.origin);
                    detailUrl.searchParams.set('topik_konsultasi', selectedTopik);
                    window.location.href = detailUrl.toString();
                }, 180);
            });
        });

        methodCards.forEach(function (card) {
            card.addEventListener('click', function () {
                if (isBookingClosed()) {
                    showBookingClosedAlert();
                    return;
                }

                if (!psikologInput.value) {
                    showMindHavenAlert('Psikolog Belum Dipilih', 'Silakan pilih psikolog terlebih dahulu.');
                    return;
                }

                methodCards.forEach(function (item) {
                    item.classList.remove('active');
                    item.classList.remove('error-box');
                });

                card.classList.add('active');

                metodeInput.value = card.dataset.method;
                tanggalInput.value = '';

                dateSection.classList.remove('hidden');
                scheduleSection.classList.add('hidden');

                generateAvailableDates();
                updateSummary();

                dateSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        consultationForm.addEventListener('submit', function (event) {
            if (isBookingClosed()) {
                event.preventDefault();
                showBookingClosedAlert();
                return;
            }

            if (!topikInput.value) {
                event.preventDefault();
                showMindHavenAlert('Topik Belum Dipilih', 'Silakan pilih topik konseling terlebih dahulu.');
                return;
            }

            if (!psikologInput.value) {
                event.preventDefault();
                showMindHavenAlert('Psikolog Belum Dipilih', 'Silakan pilih psikolog terlebih dahulu.');
                return;
            }

            if (!metodeInput.value) {
                event.preventDefault();
                showMindHavenAlert('Metode Belum Dipilih', 'Silakan pilih metode konsultasi terlebih dahulu.');
                return;
            }

            if (!tanggalInput.value) {
                event.preventDefault();
                showMindHavenAlert('Tanggal Belum Dipilih', 'Silakan pilih tanggal konsultasi terlebih dahulu.');
                return;
            }
        });

        if (isBookingClosed()) {
            showBookingClosedAlert();
            return;
        }

        if (topikInput.value) {
            const oldTopicCard = Array.from(topicCards).find(function (card) {
                return card.dataset.topic === topikInput.value;
            });

            if (oldTopicCard) {
                oldTopicCard.click();
            }
        }

        if (metodeInput.value) {
            const oldMethodCard = Array.from(methodCards).find(function (card) {
                return card.dataset.method === metodeInput.value;
            });

            if (oldMethodCard) {
                oldMethodCard.click();
            }
        }

        if (tanggalInput.value) {
            dateSection.classList.remove('hidden');
            scheduleSection.classList.remove('hidden');
            updateSummary();
        }
    });
</script>
@endsection