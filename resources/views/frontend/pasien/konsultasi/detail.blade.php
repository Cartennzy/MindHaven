@extends('frontend.layouts.app')

@section('title', 'Detail Psikolog - MindHaven')
@section('page_title', 'Detail Psikolog')
@section('page_subtitle', 'Review profil psikolog sebelum melanjutkan pembayaran.')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Konsultasi;
    use Carbon\Carbon;

    $foto = $psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)
        ? asset('storage/' . $psikolog->foto_profil)
        : null;

    $alamatBersih = $psikolog->alamat ?? '-';
    $bio = $psikolog->bio ?? null;
    $pendidikan = $psikolog->pendidikan ?? null;
    $strPsikolog = $psikolog->str_psikolog ?? null;
    $sipPsikolog = $psikolog->sip_psikolog ?? null;

    $metodeKonsultasiText = 'Chat, Video Call, Temu Janji';
    $jadwalPraktik = '-';
    
    if ($psikolog->relationLoaded('jadwalPraktiks') && $psikolog->jadwalPraktiks->count()) {
        $jadwalPraktik = $psikolog->jadwalPraktiks
        ->map(function ($jadwal) {
            return $jadwal->hari .
                ' (' .
                substr($jadwal->jam_mulai, 0, 5) .
                ' - ' .
                substr($jadwal->jam_selesai, 0, 5) .
                ')';
        })
        ->implode(', ');
    }

    $topikKonsultasi = request('topik_konsultasi')
        ?? request('topik_konseling')
        ?? $psikolog->spesialisasi
        ?? 'Konsultasi Psikologi';

    $totalPasien = $totalPasien ?? Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
        ->distinct('id_pasien')
        ->count('id_pasien');

    $totalKonsultasi = $totalKonsultasi ?? Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
        ->count();

    $biayaAdmin = 5000;
    $biayaPsikolog = $psikolog->biaya_consultasi ?? $psikolog->biaya_konsultasi ?? 0;
    $totalBayar = $biayaPsikolog + $biayaAdmin;

    // Hitung rata-rata rating dinamis dari database internal MindHaven per-psikolog
    $ulasanQuery = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)->whereNotNull('skor_rating');
    $totalReview = $ulasanQuery->count();
    $ratingAvg = $totalReview > 0 ? round($ulasanQuery->avg('skor_rating'), 1) : null;

    // Pemetaan hari Indonesia ke nomor hari (0 = Minggu, 1 = Senin, dst) buat pencarian sebulan ke depan
    $hariMapAngka = [
        'Minggu' => 0,
        'Senin' => 1,
        'Selasa' => 2,
        'Rabu' => 3,
        'Kamis' => 4,
        'Jumat' => 5,
        'Sabtu' => 6
    ];
@endphp

<div class="space-y-7">

    @if ($errors->any())
        <div class="rounded-[28px] border border-red-100 bg-red-50 px-6 py-5 text-sm font-medium text-red-700">
            <p class="mb-2 text-base font-semibold">Ada data yang belum benar:</p>
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-[28px] border border-green-100 bg-green-50 px-6 py-5 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-[28px] border border-red-100 bg-red-50 px-6 py-5 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-7 xl:grid-cols-12">

        <div class="xl:col-span-8">
            <div class="overflow-hidden rounded-[34px] border border-slate-100 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)]">

                <div class="relative overflow-hidden bg-gradient-to-br from-[#061A33] via-[#01588E] to-[#39A900] px-6 py-8 text-white md:px-8 md:py-10">
                    <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-28 -left-24 h-72 w-72 rounded-full bg-[#41AD01]/30 blur-3xl"></div>
                    <div class="absolute inset-0 opacity-[0.10]"
                         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;">
                    </div>

                    <div class="relative flex flex-col gap-6 md:flex-row md:items-center">
                        @if($foto)
                            <img src="{{ $foto }}"
                                 alt="{{ $psikolog->nama_lengkap }}"
                                 class="h-40 w-36 rounded-[28px] object-cover shadow-xl ring-4 ring-white/25">
                        @else
                            <div class="flex h-40 w-36 shrink-0 items-center justify-center rounded-[28px] bg-white/18 text-5xl font-semibold text-white shadow-xl ring-4 ring-white/25">
                                {{ strtoupper(substr($psikolog->nama_lengkap, 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="mb-4 flex flex-wrap gap-2.5">
                                <span class="inline-flex items-center rounded-full bg-white/18 px-4 py-2 text-xs font-medium text-white backdrop-blur">
                                    <i class="fas fa-circle-check mr-2"></i>
                                    Terverifikasi
                                </span>

                                <span class="inline-flex items-center rounded-full bg-[#41AD01]/35 px-4 py-2 text-xs font-medium text-white backdrop-blur">
                                    <i class="fas fa-user-shield mr-2"></i>
                                    Aktif
                                </span>

                                <span class="inline-flex items-center rounded-full bg-white/18 px-4 py-2 text-xs font-medium text-white backdrop-blur">
                                    <i class="fas fa-calendar-check mr-2"></i>
                                    Jadwal tersedia
                                </span>
                            </div>

                            <h2 class="text-3xl font-semibold leading-tight tracking-tight md:text-5xl">
                                {{ $psikolog->nama_lengkap }}
                            </h2>

                            <p class="mt-4 max-w-3xl text-base font-normal leading-8 text-blue-50 md:text-lg">
                                {{ $psikolog->spesialisasi ?? 'Psikolog MindHaven' }}
                            </p>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <span class="rounded-2xl bg-white/16 px-4 py-3 text-sm font-medium backdrop-blur">
                                    <i class="fas fa-briefcase mr-2"></i>
                                    {{ $psikolog->pengalaman ?? 0 }} tahun pengalaman
                                </span>

                                <span class="rounded-2xl bg-white/16 px-4 py-3 text-sm font-medium backdrop-blur">
                                    <i class="fas fa-headset mr-2"></i>
                                    Chat, Video Call, Temu Janji
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-[26px] border border-[#01588E]/10 bg-[#01588E]/5 p-5">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Biaya Konsultasi</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">
                                Rp {{ number_format($biayaPsikolog, 0, ',', '.') }}
                            </h3>
                        </div>

                        <div class="rounded-[26px] border border-[#41AD01]/10 bg-[#41AD01]/5 p-5">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#41AD01] shadow-sm">
                                <i class="fas fa-certificate text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Status</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">
                                Terverifikasi
                            </h3>
                        </div>

                        <div class="rounded-[26px] border border-slate-100 bg-slate-50 p-5">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                                <i class="fas fa-star text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Rating</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900 flex items-center gap-1.5">
                                @if($totalReview > 0)
                                    {{ $ratingAvg }} <span class="text-xs font-bold text-slate-400">({{ $totalReview }} Ulasan)</span>
                                @else
                                    Belum Ada
                                @endif
                            </h3>
                        </div>
                    </div>

                    <div class="mt-7 rounded-[30px] border border-slate-100 bg-slate-50 p-6 md:p-7">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                                <i class="fas fa-user-doctor text-xl"></i>
                            </div>

                            <div>
                                <h3 class="text-2xl font-semibold text-slate-900">
                                    Tentang Psikolog
                                </h3>

                                <p class="mt-4 text-base font-normal leading-8 text-slate-600">
                                    {{ $bio ?? 'Psikolog ini telah terverifikasi oleh admin MindHaven dan tersedia untuk membantu proses konsultasi kesehatan mental sesuai kebutuhan pasien.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-7 rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm md:p-7">
                        <h3 class="text-2xl font-semibold text-slate-900">
                            Informasi Profesional
                        </h3>

                        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Nama Lengkap</p>
                                <h4 class="mt-2 text-base font-semibold text-slate-900">
                                    {{ $psikolog->nama_lengkap ?? '-' }}
                                </h4>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Spesialisasi</p>
                                <h4 class="mt-2 text-base font-semibold leading-7 text-slate-900">
                                    {{ $psikolog->spesialisasi ?? '-' }}
                                </h4>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Pengalaman Kerja</p>
                                <h4 class="mt-2 text-base font-semibold text-slate-900">
                                    {{ $psikolog->pengalaman ?? 0 }} Tahun
                                </h4>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Email</p>
                                <h4 class="mt-2 break-all text-base font-semibold text-slate-900">
                                    {{ $psikolog->user->email ?? $psikolog->email ?? '-' }}
                                </h4>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-5 md:col-span-2">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat Praktik / Domisili</p>
                                <h4 class="mt-2 text-base font-semibold leading-7 text-slate-900">
                                    {{ $alamatBersih ?: '-' }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="mt-7 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>

                            <h3 class="mt-5 text-xl font-semibold text-slate-900">
                                Pendidikan
                            </h3>

                            <p class="mt-3 text-sm font-normal leading-7 text-slate-600">
                                {{ $pendidikan ?? 'Belum tersedia.' }}
                            </p>
                        </div>

                        <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#41AD01]/10 text-[#41AD01]">
                                <i class="fas fa-id-card text-xl"></i>
                            </div>

                            <h3 class="mt-5 text-xl font-semibold text-slate-900">
                                Legalitas Praktik
                            </h3>

                            <div class="mt-3 space-y-2 text-sm font-normal leading-7 text-slate-600">
                                <p>STR: {{ $strPsikolog ?? 'Belum tersedia' }}</p>
                                <p>SIP Psikolog: {{ $sipPsikolog ?? 'Belum tersedia' }}</p>
                            </div>
                        </div>

                        <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                                <i class="fas fa-headset text-xl"></i>
                            </div>

                            <h3 class="mt-5 text-xl font-semibold text-slate-900">
                                Metode Konsultasi
                            </h3>

                            <p class="mt-3 text-sm font-normal leading-7 text-slate-600">
                                {{ $metodeKonsultasiText }}
                            </p>
                        </div>

                        <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                                <i class="fas fa-calendar-days text-xl"></i>
                            </div>

                            <h3 class="mt-5 text-xl font-semibold text-slate-900">
                                Jadwal Praktik (1 Bulan Kedepan)
                            </h3>

                            @if($psikolog->jadwalPraktiks->count())
                                <div class="mt-4 max-h-[450px] overflow-y-auto space-y-3.5 pr-1">
                                    @php
                                        $hasSchedule = false;
                                        $today = Carbon::now('Asia/Jakarta');
                                    @endphp

                                    @for($i = 1; $i <= 30; $i++)
                                        @php
                                            $currentCalDate = $today->copy()->addDays($i);
                                            $dayOfWeekNum = $currentCalDate->dayOfWeek;

                                            $cocokJadwal = $psikolog->jadwalPraktiks->first(function($j) use ($dayOfWeekNum, $hariMapAngka) {
                                                return ($hariMapAngka[$j->hari] ?? null) === $dayOfWeekNum;
                                            });
                                        @endphp

                                        @if($cocokJadwal)
                                            @php
                                                $hasSchedule = true;
                                                $dateString = $currentCalDate->toDateString();

                                                $totalBooked = Konsultasi::where('id_psikolog', $psikolog->id_psikolog)
                                                    ->where('tanggal_konsultasi', $dateString)
                                                    ->whereIn('status', ['diproses', 'selesai', 'pending'])
                                                    ->count();

                                                // UPDATE MUTLAK: Jika target kuota 1 booking per hari, ubah perbandingannya ke >= 1
                                                $isFull = $totalBooked >= 1;
                                                $badgeStyle = $isFull ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-green-50 text-green-700 border border-green-100';
                                                $badgeText = $isFull ? 'Penuh' : 'Tersedia';
                                            @endphp

                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50/50 px-5 py-4 transition hover:border-[#01588E]/20 hover:bg-white hover:shadow-sm">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-base font-black text-slate-900">
                                                            {{ $cocokJadwal->hari }}
                                                        </span>
                                                        <span class="rounded-lg px-2.5 py-1 text-[10px] font-black tracking-wide uppercase {{ $badgeStyle }}">
                                                            {{ $badgeText }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs font-semibold text-slate-400">
                                                        Tanggal: <span class="text-slate-600 font-bold">{{ $currentCalDate->translatedFormat('d M Y') }}</span>
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-2 self-start sm:self-center">
                                                    <span class="inline-flex items-center rounded-xl bg-[#01588E]/5 border border-[#01588E]/10 px-3.5 py-2 text-xs font-black text-[#01588E]">
                                                        <i class="far fa-clock mr-1.5 opacity-70"></i>
                                                        {{ \Carbon\Carbon::parse($cocokJadwal->jam_mulai)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($cocokJadwal->jam_selesai)->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @endfor

                                    @if(!$hasSchedule)
                                        <p class="mt-3 text-sm text-slate-500">Jadwal praktik sebulan kedepan belum diatur.</p>
                                    @endif
                                </div>
                            @else
                                <p class="mt-3 text-sm text-slate-500">
                                    Jadwal praktik belum tersedia.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-7 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-[30px] bg-gradient-to-br from-[#01588E] to-[#01446e] p-6 text-white shadow-[0_22px_50px_rgba(1,88,142,0.18)]">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <p class="mt-5 text-sm font-medium text-blue-100">
                                Total Pasien
                            </p>
                            <h3 class="mt-2 text-3xl font-semibold">
                                {{ $totalPasien }}
                            </h3>
                        </div>

                        <div class="rounded-[30px] bg-gradient-to-br from-[#41AD01] to-[#2D7A00] p-6 text-white shadow-[0_22px_50px_rgba(65,173,1,0.18)]">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                                <i class="fas fa-comments text-xl"></i>
                            </div>
                            <p class="mt-5 text-sm font-medium text-green-50">
                                Total Konsultasi
                            </p>
                            <h3 class="mt-2 text-3xl font-semibold">
                                {{ $totalKonsultasi }}
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="xl:col-span-4">
            <div class="sticky top-6 space-y-6">

                <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-[0_24px_70px_rgba(15,23,42,0.08)]">
                    <div class="mb-5 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Ringkasan Pembayaran
                            </h3>
                            <p class="mt-1 text-sm font-normal text-slate-500">
                                Cek biaya sebelum lanjut.
                            </p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Topik Konsultasi</p>
                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-800">
                            {{ $topikKonsultasi }}
                        </p>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Biaya Psikolog</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                Rp {{ number_format($biayaPsikolog, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Biaya Admin</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                Rp {{ number_format($biayaAdmin, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex justify-between gap-4">
                                <span class="text-base font-semibold text-slate-900">Total Bayar</span>
                                <span class="text-right text-2xl font-semibold text-[#01588E]">
                                    Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('pasien.konsultasi.store') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="id_psikolog" value="{{ $psikolog->id_psikolog }}">
                        <input type="hidden" name="topik_konsultasi" value="{{ $topikKonsultasi }}">

                        <button type="submit"
                                class="group inline-flex w-full items-center justify-center rounded-2xl bg-[#41AD01] px-7 py-4 text-sm font-semibold text-white shadow-[0_18px_35px_rgba(65,173,1,0.22)] transition hover:-translate-y-0.5 hover:bg-[#329000]">
                            Bayar Sekarang
                            <i class="fas fa-arrow-right ml-3 transition group-hover:translate-x-1"></i>
                        </button>
                    </form>

                    <a href="{{ route('pasien.konsultasi.create') }}"
                       class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-slate-50 px-7 py-4 text-sm font-semibold text-slate-600 ring-1 ring-slate-200 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Ganti Psikolog
                    </a>
                </div>

                <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-[0_24px_70px_rgba(15,23,42,0.06)]">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Setelah Pembayaran
                    </h3>

                    <div class="mt-5 space-y-4">
                        <div class="flex gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#01588E]/10 text-sm font-semibold text-[#01588E]">
                                1
                            </div>
                            <p class="text-sm font-normal leading-6 text-slate-600">
                                Upload bukti atau selesaikan pembayaran.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#01588E]/10 text-sm font-semibold text-[#01588E]">
                                2
                            </div>
                            <p class="text-sm font-normal leading-6 text-slate-600">
                                Setelah pembayaran diterima, pilih metode Chat, Video Call, atau Temu Janji.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#41AD01]/10 text-sm font-semibold text-[#41AD01]">
                                3
                            </div>
                            <p class="text-sm font-normal leading-6 text-slate-600">
                                Pilih tanggal, jam, isi keluhan, lalu konsultasi berjalan setelah psikolog mengonfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection