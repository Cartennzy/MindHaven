@extends('frontend.layouts.psikolog')

@section('title', 'Dashboard Psikolog')
@section('page-title', 'Dashboard')

@section('content')

@php
    use Carbon\Carbon;
    use App\Models\Konsultasi;

    $psikologLogin = Auth::user()->psikolog ?? null;

    $namaPasienDashboard = function ($konsultasi) {
        return $konsultasi->pasien->nama_lengkap
            ?? $konsultasi->pasien->user->name
            ?? 'Pasien';
    };

    $persenPending = $totalKonsultasi > 0 ? ($totalKonsultasiPending / $totalKonsultasi) * 100 : 0;
    $persenSelesai = $totalKonsultasi > 0 ? ($totalKonsultasiSelesai / $totalKonsultasi) * 100 : 0;
    $persenRekamMedis = $totalKonsultasi > 0 ? ($totalRekamMedis / max($totalKonsultasi, 1)) * 100 : 0;
    $tanggalSekarang = Carbon::now()->translatedFormat('d F Y');

    // Menghitung rata-rata rating psikolog yang sedang login secara realtime
    $rataRating = 0;
    $totalReview = 0;
    if ($psikologLogin) {
        $ulasanQuery = Konsultasi::where('id_psikolog', $psikologLogin->id_psikolog)
            ->whereNotNull('skor_rating');
            
        $totalReview = $ulasanQuery->count();
        $rataRating = $totalReview > 0 ? round($ulasanQuery->avg('skor_rating'), 1) : 0;
    }
@endphp

<div class="space-y-6">

    {{-- PROFILE HEADER --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#061A33] via-[#01588E] to-[#41AD01] p-7 shadow-[0_20px_60px_rgba(15,23,42,0.15)]">
        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-[#B7E3FF]/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-5">
                @if($psikologLogin && $psikologLogin->foto_profil)
                    <img src="{{ asset('storage/' . $psikologLogin->foto_profil) }}"
                         alt="Foto Profil"
                         class="h-24 w-24 rounded-full border-4 border-white/30 object-cover shadow-2xl">
                @else
                    <div class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white/20 bg-white/10 text-3xl font-bold text-white backdrop-blur">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div class="text-white">
                    <p class="text-sm font-medium text-white/80">Dashboard Psikolog</p>
                    <h1 class="mt-1 text-3xl font-black leading-tight">
                        {{ $psikologLogin->nama_lengkap ?? Auth::user()->name }}
                    </h1>

                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <span class="rounded-full bg-white/15 px-4 py-2 text-xs font-bold backdrop-blur">
                            {{ $psikologLogin->spesialisasi ?? 'Psikolog MindHaven' }}
                        </span>

                        <span class="rounded-full bg-[#12B76A]/20 px-4 py-2 text-xs font-bold text-white backdrop-blur">
                            Aktif
                        </span>
                        
                        {{-- Badge Penilaian Rating Realtime di Dashboard --}}
                        <span class="rounded-full bg-amber-400/20 border border-amber-400/30 px-4 py-2 text-xs font-bold text-amber-300 backdrop-blur flex items-center gap-1.5 shadow-sm">
                            <i class="fas fa-star text-amber-400"></i> 
                            @if($totalReview > 0)
                                {{ $rataRating }} / 5.0 ({{ $totalReview }} Ulasan)
                            @else
                                Belum Ada Rating
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('psikolog.profile') }}"
                   class="rounded-2xl bg-white px-5 py-3 text-sm font-bold text-[#01588E] shadow-lg transition hover:-translate-y-0.5 hover:bg-slate-100">
                    Edit Profil
                </a>

                <a href="{{ route('psikolog.konsultasi.index') }}"
                   class="rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">
                    Lihat Konsultasi
                </a>
            </div>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

        <a href="{{ route('psikolog.pasien.index') }}"
           class="group block rounded-[1.6rem] bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#155EEF]">Total Pasien</p>
                    <h2 class="mt-2 text-3xl font-semibold text-[#061A33]">{{ $totalPasien }}</h2>
                    <p class="mt-1 text-xs text-slate-400">Pasien aktif konsultasi</p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E8F1FF] text-[#155EEF] transition group-hover:scale-110">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('psikolog.konsultasi.index') }}"
           class="group block rounded-[1.6rem] bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#12B76A]">Konsultasi Hari Ini</p>
                    <h2 class="mt-2 text-3xl font-semibold text-[#061A33]">{{ $konsultasiHariIni }}</h2>
                    <p class="mt-1 text-xs text-slate-400">{{ $tanggalSekarang }}</p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E9FBEF] text-[#12B76A] transition group-hover:scale-110">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.82L3 20l1.25-3.33A7.46 7.46 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('psikolog.konsultasi.index') }}"
           class="group block rounded-[1.6rem] bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#F79009]">Total Konsultasi</p>
                    <h2 class="mt-2 text-3xl font-semibold text-[#061A33]">{{ $totalKonsultasi }}</h2>
                    <p class="mt-1 text-xs text-slate-400">Seluruh riwayat</p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FFF4DF] text-[#F79009] transition group-hover:scale-110">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('psikolog.pendapatan.index') }}"
           class="group block rounded-[1.6rem] bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 group-hover:text-[#12B76A]">Pendapatan</p>
                    <h2 class="mt-2 text-2xl font-semibold text-[#061A33]">
                        Rp {{ number_format($totalPendapatanPsikolog ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $totalPembayaranDiterima ?? 0 }} pembayaran diterima
                    </p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E9FBEF] text-[#12B76A] transition group-hover:scale-110">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>

    </div>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

        <div class="rounded-[1.8rem] bg-white p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)] xl:col-span-4">
            <h2 class="text-lg font-semibold text-[#061A33]">Ringkasan Konsultasi</h2>
            <p class="mt-1 text-sm text-slate-400">Status layanan pasien</p>

            <div class="mt-6 flex items-center justify-center">
                <div class="relative flex h-52 w-52 items-center justify-center rounded-full"
                     style="background: conic-gradient(#155EEF 0deg {{ $persenSelesai * 3.6 }}deg, #F79009 {{ $persenSelesai * 3.6 }}deg {{ ($persenSelesai + $persenPending) * 3.6 }}deg, #E6EEF8 {{ ($persenSelesai + $persenPending) * 3.6 }}deg 360deg);">
                    <div class="flex h-32 w-32 items-center justify-center rounded-full bg-white">
                        <div class="text-center">
                            <h3 class="text-3xl font-semibold text-[#061A33]">{{ $totalKonsultasi }}</h3>
                            <p class="text-xs text-slate-400">Konsultasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-3 gap-3">
                <div class="rounded-2xl bg-[#E8F1FF] p-3 text-center">
                    <p class="text-xs text-slate-500">Selesai</p>
                    <h4 class="mt-1 text-lg font-semibold text-[#155EEF]">{{ $totalKonsultasiSelesai }}</h4>
                </div>

                <div class="rounded-2xl bg-[#FFF4DF] p-3 text-center">
                    <p class="text-xs text-slate-500">Pending</p>
                    <h4 class="mt-1 text-lg font-semibold text-[#F79009]">{{ $totalKonsultasiPending }}</h4>
                </div>

                <div class="rounded-2xl bg-[#E9FBEF] p-3 text-center">
                    <p class="text-xs text-slate-500">Rekam</p>
                    <h4 class="mt-1 text-lg font-semibold text-[#12B76A]">{{ $totalRekamMedis }}</h4>
                </div>
            </div>
        </div>

        <div class="rounded-[1.8rem] bg-[#EEF4FF] p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)] xl:col-span-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-[#061A33]">Jadwal Konsultasi</h2>
                    <p class="mt-1 text-sm text-slate-400">Daftar konsultasi terbaru</p>
                </div>

                <a href="{{ route('psikolog.konsultasi.index') }}"
                   class="rounded-xl bg-white px-4 py-2 text-xs font-medium text-[#155EEF] transition hover:bg-[#155EEF] hover:text-white">
                    Lihat Semua
                </a>
            </div>

            <div class="mt-5 space-y-3">
                @forelse($jadwalKonsultasi as $konsultasi)
                    <a href="{{ route('psikolog.konsultasi.show', $konsultasi->id_konsultasi) }}"
                       class="flex items-center gap-4 rounded-2xl bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#155EEF] text-sm font-semibold text-white">
                            {{ strtoupper(substr($namaPasienDashboard($konsultasi), 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-semibold text-[#061A33]">
                                {{ $namaPasienDashboard($konsultasi) }}
                            </h3>
                            <p class="truncate text-xs text-slate-400">
                                {{ $konsultasi->keluhan ?? $konsultasi->topik_konseling ?? 'Konsultasi Kesehatan Mental' }}
                            </p>
                        </div>

                        <span class="rounded-xl bg-[#DCEBFF] px-3 py-1.5 text-xs font-medium text-[#155EEF]">
                            {{ ucfirst($konsultasi->status ?? 'proses') }}
                        </span>
                    </a>
                @empty
                    <div class="rounded-2xl bg-white p-8 text-center text-sm text-slate-400">
                        Belum ada jadwal konsultasi.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-[1.8rem] bg-white p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)] xl:col-span-3">
            <h2 class="text-lg font-semibold text-[#061A33]">Pasien Terbaru</h2>
            <p class="mt-1 text-sm text-slate-400">Detail konsultasi terakhir</p>

            @if($pasienTerbaru)
                <div class="mt-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#EEF4FF] text-lg font-semibold text-[#155EEF]">
                            {{ strtoupper(substr($namaPasienDashboard($pasienTerbaru), 0, 1)) }}
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-[#061A33]">
                                {{ $namaPasienDashboard($pasienTerbaru) }}
                            </h3>
                            <p class="text-xs text-slate-400">
                                ID: {{ $pasienTerbaru->pasien_id }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-2xl bg-[#F7FAFC] p-4">
                            <p class="text-xs text-slate-400">Status</p>
                            <p class="mt-1 font-medium text-[#061A33]">{{ ucfirst($pasienTerbaru->status ?? '-') }}</p>
                        </div>

                        <div class="rounded-2xl bg-[#F7FAFC] p-4">
                            <p class="text-xs text-slate-400">Tanggal</p>
                            <p class="mt-1 font-medium text-[#061A33]">
                                {{ $pasienTerbaru->created_at ? $pasienTerbaru->created_at->format('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('psikolog.konsultasi.show', $pasienTerbaru->id_konsultasi) }}"
                       class="mt-5 block rounded-2xl bg-[#061A33] px-5 py-3 text-center text-sm font-medium text-white transition hover:bg-[#155EEF]">
                        Detail Pasien
                    </a>
                </div>
            @else
                <div class="mt-5 rounded-2xl bg-[#F7FAFC] p-8 text-center text-sm text-slate-400">
                    Belum ada pasien terbaru.
                </div>
            @endif
        </div>

    </div>

    {{-- BOTTOM GRID --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="rounded-[1.8rem] bg-white p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)]">
            <h2 class="text-lg font-semibold text-[#061A33]">Performa Layanan</h2>
            <p class="mt-1 text-sm text-slate-400">Persentase konsultasi</p>

            <div class="mt-6 space-y-5">
                <div>
                    <div class="mb-2 flex justify-between text-sm text-slate-500">
                        <span>Pending</span>
                        <span>{{ round($persenPending) }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100">
                        <div class="h-2 rounded-full bg-[#F79009]" style="width: {{ min($persenPending, 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex justify-between text-sm text-slate-500">
                        <span>Selesai</span>
                        <span>{{ round($persenSelesai) }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100">
                        <div class="h-2 rounded-full bg-[#12B76A]" style="width: {{ min($persenSelesai, 100) }}%"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="rounded-[1.8rem] bg-[#EEF4FF] p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)] xl:col-span-2">
            <h2 class="text-lg font-semibold text-[#061A33]">Aktivitas Terbaru</h2>
            <p class="mt-1 text-sm text-slate-400">Riwayat aktivitas konsultasi</p>

            <div class="mt-5 space-y-3">
                @forelse($aktivitasTerbaru as $aktivitas)
                    <a href="{{ route('psikolog.konsultasi.show', $aktivitas->id_konsultasi) }}"
                       class="block rounded-2xl bg-white p-4 transition hover:-translate-y-1 hover:shadow-lg">
                        <h3 class="text-sm font-semibold text-[#061A33]">
                            {{ $namaPasienDashboard($aktivitas) }}
                        </h3>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $aktivitas->created_at ? $aktivitas->created_at->diffForHumans() : 'Baru saja' }}
                        </p>
                    </a>
                @empty
                    <div class="rounded-2xl bg-white p-8 text-center text-sm text-slate-400">
                        Belum ada aktivitas.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection