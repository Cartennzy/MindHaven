@extends('frontend.layouts.app')

@section('title', 'Meditasi - MindHaven')
@section('page_title', 'Meditasi')
@section('page_subtitle', 'Daftar meditasi yang tersedia untuk pasien.')

@section('content')

<div class="space-y-7 max-w-6xl mx-auto">

    {{-- HEADER BANNER - FULL GRADIENT PREMIUM SAAS AESTHETIC --}}
    <div class="relative overflow-hidden rounded-[34px] bg-gradient-to-r from-[#01588E] via-[#0372A6] to-[#49C5B6] p-6 shadow-[0_20px_50px_rgba(1,88,142,0.2)] md:p-8">
        <!-- Efek Glow Seni Digital Elegan -->
        <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 h-44 w-44 rounded-full bg-black/10 blur-2xl"></div>

        <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <!-- Wrapper Ikon dengan Efek Glassmorphic -->
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[24px] bg-white/15 border border-white/20 text-white shadow-inner">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c4-3 6-6 6-10a6 6 0 1 0-12 0c0 4 2 7 6 10Z"/>
                    </svg>
                </div>

                <div>
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/10 px-3.5 py-1 text-[11px] font-black uppercase tracking-[0.14em] text-white backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#A7E36F] animate-pulse"></span>
                        Relaksasi Mental
                    </span>
                    <h2 class="text-3xl font-black tracking-tight text-white">Daftar Meditasi</h2>
                    <p class="mt-1 text-sm font-semibold text-white/85">Pilih meditasi yang telah ditambahkan oleh admin untuk membantu relaksasi dan kesehatan mental Anda.</p>
                </div>
            </div>

            <!-- Counter Data Bergaya Glassmorphism Minimalis -->
            <div class="rounded-[26px] border border-white/20 bg-white/10 px-6 py-4 text-center backdrop-blur-md shadow-inner min-w-[120px]">
                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-white/80">Total Audio</p>
                <p class="mt-0.5 text-4xl font-black text-white">{{ $meditasis->count() }}</p>
            </div>
        </div>
    </div>

    {{-- KATEGORI FILTER BADGES --}}
    @if(isset($kategoriMeditasi) && $kategoriMeditasi->count() > 0)
        <div class="flex flex-wrap gap-2.5 pt-1">
            <span class="inline-flex items-center rounded-2xl bg-[#01588E] px-5 h-10 text-xs font-black text-white shadow-[0_12px_24px_rgba(1,88,142,0.15)] cursor-pointer">
                Semua Konten
            </span>

            @foreach($kategoriMeditasi as $kategori)
                <span class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 h-10 text-xs font-black text-slate-600 shadow-sm hover:bg-slate-50 transition cursor-pointer">
                    {{ $kategori }}
                </span>
            @endforeach
        </div>
    @endif

    {{-- LIST MEDITASI GRID --}}
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($meditasis as $meditasi)
            <a href="{{ route('pasien.meditasi.show', ['meditasi' => $meditasi->id_meditasi]) }}"
               class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-gradient-to-br from-white via-[#F8FAFC] to-[#F1F5F9] p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:shadow-[0_22px_50px_rgba(1,88,142,0.07)] hover:-translate-y-1 hover:border-slate-200">
                
                {{-- Border Garis Atas Premium --}}
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#01588E] via-[#49C5B6] to-[#A7E36F]"></div>
                <div class="absolute -right-20 -top-20 h-44 w-44 rounded-full bg-[#49C5B6]/5 blur-3xl"></div>

                <div class="relative flex flex-col h-full justify-between">
                    <div>
                        {{-- Icon Box --}}
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E] border border-[#01588E]/5 shadow-inner transition-all duration-300 group-hover:scale-105">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c4-3 6-6 6-10a6 6 0 1 0-12 0c0 4 2 7 6 10Z"/>
                            </svg>
                        </div>

                        {{-- Kategori Badge Intern --}}
                        <p class="mt-5 inline-flex rounded-xl bg-sky-50 border border-sky-100 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-sky-700">
                            {{ $meditasi->kategori ?? 'Meditasi' }}
                        </p>

                        {{-- Judul Konten --}}
                        <h3 class="mt-3 text-xl font-black leading-tight text-slate-900 transition group-hover:text-[#01588E] line-clamp-2">
                            {{ $meditasi->judul }}
                        </h3>

                        {{-- Ringkasan Deskripsi --}}
                        <p class="mt-3 text-xs font-semibold leading-relaxed text-slate-500 line-clamp-3">
                            {{ $meditasi->deskripsi }}
                        </p>

                        {{-- AUDIO PREVIEW CONTAINER --}}
                        @if($meditasi->audio)
                            @php
                                $extension = strtolower(pathinfo($meditasi->audio, PATHINFO_EXTENSION));
                                $fileUrl = asset('storage/' . $meditasi->audio);
                            @endphp

                            <div class="mt-4 rounded-2xl border border-slate-100 bg-white p-3 shadow-inner" onclick="event.preventDefault(); event.stopPropagation();">
                                @if(in_array($extension, ['mp4', 'mov', 'webm']))
                                    <video controls class="w-full rounded-xl">
                                        <source src="{{ $fileUrl }}">
                                        Browser Anda tidak mendukung video.
                                    </video>
                                @else
                                    <audio controls class="w-full h-8">
                                        <source src="{{ $fileUrl }}">
                                        Browser Anda tidak mendukung audio.
                                    </audio>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Footer Info Box Parameter --}}
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-3 w-full">
                        <div class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-100 px-3 py-1.5 shadow-sm">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 text-xs">
                                <i class="fas fa-clock"></i>
                            </span>
                            <span class="text-xs font-black text-slate-700">
                                {{ $meditasi->durasi ?? '-' }} Menit
                            </span>
                        </div>

                        <div class="inline-flex items-center gap-1 text-xs font-black text-[#01588E] group-hover:translate-x-0.5 transition-transform">
                            Mulai Terapi <i class="fas fa-arrow-right text-[10px]"></i>
                        </div>
                    </div>

                </div>
            </a>
        @empty
            {{-- UPGRADE ELEMEN @EMPTY - MULTI-TONE GRADIENT DYNAMIC CARD SESUAI SCREENSHOT ACUAN --}}
            <div class="col-span-full rounded-[34px] border border-[#BFE7F3] bg-gradient-to-br from-white via-[#F4FAFF] to-[#EFFDF9] p-10 text-center shadow-[0_20px_50px_rgba(1,88,142,0.05)] md:p-14">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[26px] bg-gradient-to-br from-[#01588E] to-[#49C5B6] text-white shadow-[0_12px_30px_rgba(1,88,142,0.25)] animate-pulse">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c4-3 6-6 6-10a6 6 0 1 0-12 0c0 4 2 7 6 10Z"/>
                    </svg>
                </div>

                <h3 class="mt-7 text-2xl font-black text-slate-900">Belum Ada Sesi Meditasi</h3>
                <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-7 text-slate-400">
                    Katalog audio panduan relaksasi batin belum tersedia. Seluruh berkas audio terapi relaksasi emosional yang dirilis oleh sistem admin akan terangkum lengkap di halaman ini.
                </p>

                <a href="{{ route('pasien.dashboard') }}"
                   class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-7 text-sm font-black text-white shadow-[0_12px_24px_rgba(1,88,142,0.15)] hover:-translate-y-0.5 transition duration-300">
                    <i class="fas fa-home"></i> Kembali ke Dashboard
                </a>
            </div>
        @endforelse
    </section>

</div>

@endsection