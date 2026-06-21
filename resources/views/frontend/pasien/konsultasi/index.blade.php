@extends('frontend.layouts.app')

@section('title', 'Konsultasi Saya - MindHaven')
@section('page_title', 'Konsultasi Saya')
@section('page_subtitle', 'Kelola jadwal dan status konsultasi kesehatan mental Anda.')

@section('content')

<div class="space-y-7 max-w-6xl mx-auto">

    {{-- REDESIGN TOTAL HERO HEADER - FULL GRADIENT PREMIUM SAAS AESTHETIC --}}
    <div class="relative overflow-hidden rounded-[34px] bg-gradient-to-r from-[#01588E] via-[#0372A6] to-[#49C5B6] p-6 shadow-[0_20px_50px_rgba(1,88,142,0.2)] md:p-8">
        <!-- Efek Glow Seni Digital Elegan -->
        <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 h-44 w-44 rounded-full bg-black/10 blur-2xl"></div>

        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <!-- Wrapper Ikon dengan Efek Glassmorphic -->
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[24px] bg-white/15 border border-white/20 text-white shadow-inner">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                    </svg>
                </div>
                <div>
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/10 px-3.5 py-1 text-[11px] font-black uppercase tracking-[0.14em] text-white backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#A7E36F] animate-pulse"></span>
                        Panel Kontrol Pasien
                    </span>
                    <h3 class="text-3xl font-black tracking-tight text-white">Konsultasi Saya</h3>
                    <p class="mt-1 text-sm font-semibold text-white/85">Kelola dan pantau seluruh riwayat interaksi sesi klinis Anda bersama Psikolog.</p>
                </div>
            </div>

            <!-- Tombol Aksi Kontras Putih-Hijau Segar -->
            <a href="{{ route('pasien.konsultasi.create') }}"
               class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-white px-6 text-sm font-black text-[#01588E] shadow-[0_12px_24px_rgba(0,0,0,0.1)] hover:-translate-y-0.5 hover:bg-slate-50 transition duration-300 whitespace-nowrap">
                <svg class="h-5 w-5 text-[#41AD01]" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                </svg>
                Buat Konsultasi Baru
            </a>
        </div>
    </div>

    {{-- DESKTOP LAYOUT - UPGRADED PREMIUM COLORFUL SAAS CARDS --}}
    <div class="hidden space-y-6 xl:block">
        @forelse($konsultasis as $konsultasi)
            @php
                $cardDesign = match($konsultasi->status) {
                    'selesai' => [
                        'text' => 'Selesai Sesi',
                        'badgeClass' => 'border-emerald-200 bg-emerald-100/70 text-emerald-800',
                        'cardBg' => 'bg-gradient-to-br from-white via-[#F4FDF9] to-[#E8FBF4]',
                        'cardBorder' => 'border-emerald-100/70',
                        'iconBg' => 'bg-emerald-500 text-white',
                        'iconShadow' => 'shadow-[0_8px_20px_rgba(16,185,129,0.25)]',
                        'hoverBorder' => 'hover:border-emerald-300'
                    ],
                    'diproses' => [
                        'text' => 'Sedang Diproses',
                        'badgeClass' => 'border-sky-200 bg-sky-100/70 text-sky-800',
                        'cardBg' => 'bg-gradient-to-br from-white via-[#F4FAFF] to-[#E8F4FF]',
                        'cardBorder' => 'border-sky-100/70',
                        'iconBg' => 'bg-sky-500 text-white',
                        'iconShadow' => 'shadow-[0_8px_20px_rgba(14,165,233,0.25)]',
                        'hoverBorder' => 'hover:border-sky-300'
                    ],
                    'dibatalkan' => [
                        'text' => 'Dibatalkan',
                        'badgeClass' => 'border-rose-200 bg-rose-100/70 text-rose-800',
                        'cardBg' => 'bg-gradient-to-br from-white via-[#FFF5F6] to-[#FFEBEF]',
                        'cardBorder' => 'border-rose-100/70',
                        'iconBg' => 'bg-rose-500 text-white',
                        'iconShadow' => 'shadow-[0_8px_20px_rgba(244,63,94,0.25)]',
                        'hoverBorder' => 'hover:border-rose-300'
                    ],
                    default => [
                        'text' => 'Menunggu Persetujuan',
                        'badgeClass' => 'border-amber-200 bg-amber-100/70 text-amber-800',
                        'cardBg' => 'bg-gradient-to-br from-white via-[#FFFDF5] to-[#FFF9E6]',
                        'cardBorder' => 'border-amber-100/70',
                        'iconBg' => 'bg-amber-500 text-white',
                        'iconShadow' => 'shadow-[0_8px_20px_rgba(245,158,11,0.25)]',
                        'hoverBorder' => 'hover:border-amber-300'
                    ],
                };
            @endphp

            <div class="group relative overflow-hidden rounded-[30px] border {{ $cardDesign['cardBorder'] }} {{ $cardDesign['cardBg'] }} p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:shadow-[0_22px_50px_rgba(1,88,142,0.07)] hover:-translate-y-0.5 {{ $cardDesign['hoverBorder'] }}">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b from-[#01588E] to-[#49C5B6]"></div>

                <div class="relative flex flex-col gap-6">
                    <div class="flex items-start justify-between w-full">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $cardDesign['iconBg'] }} {{ $cardDesign['iconShadow'] }} text-xl transition-all duration-300 group-hover:scale-105">
                                <i class="fas fa-user-doctor"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-slate-900 leading-tight">{{ $konsultasi->psikolog->nama_lengkap ?? '-' }}</h4>
                                <p class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider"><i class="fas fa-id-card-clip mr-1"></i> {{ $konsultasi->psikolog->spesialisasi ?? 'Tenaga Ahli MindHaven' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex h-9 items-center rounded-xl border px-4 text-xs font-black uppercase tracking-wider shadow-sm {{ $cardDesign['badgeClass'] }}">
                            {{ $cardDesign['text'] }}
                        </span>
                    </div>

                    <div class="rounded-2xl border border-white/60 bg-white/50 backdrop-blur-sm p-4 shadow-inner">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5"><i class="fas fa-comment-medical mr-1"></i> Uraian Keluhan Utama</span>
                        <p class="text-sm font-semibold text-slate-700 leading-relaxed line-clamp-2">
                            {{ $konsultasi->keluhan ?? 'Tidak ada deskripsi keluhan tambahan.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-t border-b border-slate-200/50 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-white text-emerald-600 border border-slate-100 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-calendar-day"></i></div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Tanggal Sesi</span>
                                <span class="text-sm font-black text-slate-800 mt-0.5 block">{{ $konsultasi->tanggal_konsultasi }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-white text-amber-600 border border-slate-100 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-clock"></i></div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Jam Pelaksanaan</span>
                                <span class="text-sm font-black text-slate-800 mt-0.5 block">{{ $konsultasi->jam_konsultasi }} WIB</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-white text-blue-600 border border-slate-100 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-wallet"></i></div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Biaya Sesi</span>
                                <span class="text-sm font-black text-[#01588E] mt-0.5 block">Rp {{ number_format($konsultasi->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between w-full pt-1">
                        <p class="text-xs font-semibold text-slate-400"><i class="fas fa-circle-info mr-1"></i> Mohon persiapkan koneksi internet stabil sebelum sesi dimulai.</p>
                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('pasien.konsultasi.show', $konsultasi->id_konsultasi) }}"
                               class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white/90 backdrop-blur-sm shadow-sm px-4 text-xs font-black text-slate-600 hover:bg-slate-50 transition whitespace-nowrap">
                                <i class="fas fa-list-check"></i> Detail Lengkap
                            </a>

                            @if($konsultasi->status == 'diproses')
                                <a href="{{ route('pasien.konsultasi.sesi', $konsultasi->id_konsultasi) }}"
                                   class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#41AD01] to-[#62C91B] px-5 text-xs font-black text-white shadow-sm hover:scale-[1.01] transition whitespace-nowrap">
                                    <i class="fas fa-video"></i> Masuk Sesi Konsultasi
                                </a>
                            @endif

                            @if($konsultasi->status == 'selesai')
                                <a href="{{ route('pasien.konsultasi.hasil', $konsultasi->id_konsultasi) }}"
                                   class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-5 text-xs font-black text-white shadow-sm hover:scale-[1.01] transition whitespace-nowrap">
                                    <i class="fas fa-square-poll-vertical"></i> Lihat Hasil Medis
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- UPGRADE ELEMEN @EMPTY DESKTOP - MULTI-TONE GRADIENT DYNAMIC CARD --}}
            <div class="rounded-[34px] border border-[#BFE7F3] bg-gradient-to-br from-white via-[#F4FAFF] to-[#EFFDF9] p-10 text-center shadow-[0_20px_50px_rgba(1,88,142,0.05)] md:p-14">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[26px] bg-gradient-to-br from-[#01588E] to-[#49C5B6] text-white shadow-[0_12px_30px_rgba(1,88,142,0.25)] animate-pulse">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z"/>
                    </svg>
                </div>

                <h3 class="mt-7 text-2xl font-black text-slate-900">Belum Ada Riwayat Konsultasi Aktif</h3>
                <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-7 text-slate-400">
                    Anda belum menjadwalkan sesi tatap muka klinis bersama Tim Psikolog MindHaven. Mulai konsultasi Anda sekarang untuk mendapatkan penanganan profesional.
                </p>

                <a href="{{ route('pasien.konsultasi.create') }}"
                   class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-7 text-sm font-black text-white shadow-[0_12px_24px_rgba(1,88,142,0.2)] hover:-translate-y-0.5 transition duration-300">
                    <i class="fas fa-calendar-plus"></i> Jadwalkan Sesi Sekarang
                </a>
            </div>
        @endforelse
    </div>

    {{-- MOBILE & TABLET LAYOUT - FLUID MOBILE RESPONSIVE --}}
    <div class="grid grid-cols-1 gap-5 xl:hidden">
        @forelse($konsultasis as $konsultasi)
            @php
                $cardDesignMobile = match($konsultasi->status) {
                    'selesai' => [
                        'text' => 'Selesai',
                        'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                        'bg' => 'bg-gradient-to-br from-white via-[#F4FDF9] to-[#E8FBF4]',
                        'border' => 'border-emerald-100/80',
                        'iconColor' => 'text-emerald-500'
                    ],
                    'diproses' => [
                        'text' => 'Diproses',
                        'badge' => 'border-sky-200 bg-sky-50 text-sky-700',
                        'bg' => 'bg-gradient-to-br from-white via-[#F4FAFF] to-[#E8F4FF]',
                        'border' => 'border-sky-100/80',
                        'iconColor' => 'text-sky-500'
                    ],
                    'dibatalkan' => [
                        'text' => 'Batal',
                        'badge' => 'border-rose-200 bg-rose-50 text-rose-700',
                        'bg' => 'bg-gradient-to-br from-white via-[#FFF5F6] to-[#FFEBEF]',
                        'border' => 'border-rose-100/80',
                        'iconColor' => 'text-rose-500'
                    ],
                    default => [
                        'text' => 'Pending',
                        'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
                        'bg' => 'bg-gradient-to-br from-white via-[#FFFDF5] to-[#FFF9E6]',
                        'border' => 'border-amber-100/80',
                        'iconColor' => 'text-amber-500'
                    ],
                };
            @endphp

            <div class="relative overflow-hidden rounded-[28px] border {{ $cardDesignMobile['border'] }} {{ $cardDesignMobile['bg'] }} p-5 shadow-sm">
                <div class="flex flex-col gap-4 pt-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white {{ $cardDesignMobile['iconColor'] }} text-base border border-slate-100 shadow-sm">
                                <i class="fas fa-user-doctor"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-base font-black text-slate-900 truncate leading-tight">{{ $konsultasi->psikolog->nama_lengkap ?? '-' }}</h4>
                                <p class="text-[10px] font-bold text-slate-500 truncate mt-0.5 uppercase tracking-wide">{{ $konsultasi->psikolog->spesialisasi ?? 'Psikolog MindHaven' }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 rounded-xl border border-white px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $cardDesignMobile['badge'] }}">
                            {{ $cardDesignMobile['text'] }}
                        </span>
                    </div>

                    <div class="rounded-xl bg-white/60 backdrop-blur-sm p-3 border border-white/40 shadow-inner">
                        <p class="text-xs font-semibold leading-relaxed text-slate-600 line-clamp-2">
                            {{ $konsultasi->keluhan ?? 'Tidak ada uraian keluhan.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs border-t border-b border-slate-200/40 py-3">
                        <div class="space-y-0.5">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Waktu Pelaksanaan</span>
                            <p class="font-black text-slate-800 truncate">{{ $konsultasi->tanggal_konsultasi }} · {{ $konsultasi->jam_konsultasi }}</p>
                        </div>
                        <div class="space-y-0.5 text-right">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Total Biaya Sesi</span>
                            <p class="font-black text-[#01588E]">Rp {{ number_format($konsultasi->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-0.5">
                        <div class="flex gap-2">
                            <a href="{{ route('pasien.konsultasi.show', $konsultasi->id_konsultasi) }}"
                               class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white text-xs font-black text-slate-600 hover:bg-slate-50 transition">
                                <i class="fas fa-list-check"></i> Detail Sesi
                            </a>

                            @if($konsultasi->status == 'diproses')
                                <a href="{{ route('pasien.konsultasi.sesi', $konsultasi->id_konsultasi) }}"
                                   class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#41AD01] to-[#62C91B] text-xs font-black text-white transition shadow-sm">
                                    <i class="fas fa-video"></i> Masuk Sesi
                                </a>
                            @endif

                            @if($konsultasi->status == 'selesai')
                                <a href="{{ route('pasien.konsultasi.hasil', $konsultasi->id_konsultasi) }}"
                                   class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#01588E] to-[#0488B8] text-xs font-black text-white transition shadow-sm">
                                    <i class="fas fa-square-poll-vertical"></i> Hasil Medis
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- UPGRADE ELEMEN @EMPTY MOBILE - MULTI-TONE GRADIENT FOR MOBILE SCREEN --}}
            <div class="rounded-[28px] border border-[#BFE7F3] bg-gradient-to-br from-white via-[#F4FAFF] to-[#EFFDF9] p-8 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#01588E] to-[#49C5B6] text-white shadow-md">
                    <i class="fas fa-calendar-xmark text-lg"></i>
                </div>
                <h4 class="mt-4 text-lg font-black text-slate-900">Belum Ada Sesi Konsultasi</h4>
                <p class="mx-auto mt-2 text-xs font-semibold leading-relaxed text-slate-400">Jadwal sesi konsultasi klinis Anda akan tersusun rapi di sini.</p>
                <a href="{{ route('pasien.konsultasi.create') }}"
                   class="mt-4 inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#01588E] px-5 text-xs font-black text-white shadow-sm">
                    Jadwalkan Sesi Pertama
                </a>
            </div>
        @endforelse
    </div>

</div>

@endsection