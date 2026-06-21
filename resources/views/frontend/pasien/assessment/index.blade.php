@extends('frontend.layouts.app')

@section('title', 'Self-Assessment Gratis - MindHaven')
@section('page_title', 'Self-Assessment')
@section('page_subtitle', 'Kenali kondisi kesehatan mental Anda secara dini melalui tes mandiri gratis.')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    @if (session('error'))
        <div class="rounded-[28px] border border-red-100 bg-red-50 px-6 py-5 text-sm font-bold text-red-700 shadow-sm flex items-center gap-3">
            <i class="fas fa-circle-exclamation text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- REDESIGN TOTAL HERO BANNER - FULL GRADIENT PREMIUM SAAS AESTHETIC --}}
    <div class="relative overflow-hidden rounded-[34px] bg-gradient-to-r from-[#061A33] via-[#01588E] to-[#49C5B6] px-6 py-10 text-white md:px-10 md:py-12 shadow-[0_20px_50px_rgba(1,88,142,0.2)]">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 h-72 w-72 rounded-full bg-[#41AD01]/10 blur-3xl"></div>
        <div class="absolute inset-0 opacity-[0.06]"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;">
        </div>

        <div class="relative max-w-3xl">
            <div class="mb-4 inline-flex items-center rounded-full bg-white/20 border border-white/10 px-4 py-1.5 text-xs font-black text-white backdrop-blur-sm">
                <i class="fas fa-heart-pulse mr-2 text-green-300"></i>
                100% Gratis & Terprivasi
            </div>
            <h2 class="text-3xl font-black leading-tight tracking-tight md:text-5xl">
                Ukur & Pahami Kondisi Psikologis Anda
            </h2>
            <p class="mt-4 text-base font-semibold leading-8 text-blue-50/90 md:text-lg">
                Asesmen ini diadaptasi dari instrumen psikometrik standar klinis internasional untuk mendeteksi indikasi awal masalah emosional Anda. Hasil tes bersifat rahasia.
            </p>
        </div>
    </div>

    {{-- GRID KATEGORI TES - UPGRADED WITH INTERACTIVE CARDS --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach($instrumens as $instrumen)
            @php
                // Tentukan icon kustom & gradien penanda berdasarkan slug kategori
                $cardConfig = match($instrumen->slug) {
                    'stres' => ['icon' => 'fa-brain', 'color' => 'from-sky-500 to-blue-600', 'bgLight' => 'bg-sky-50 text-sky-600', 'shadowGlow' => 'shadow-sky-100'],
                    'burnout' => ['icon' => 'fa-fire-flame-curved', 'color' => 'from-orange-500 to-amber-600', 'bgLight' => 'bg-orange-50 text-orange-600', 'shadowGlow' => 'shadow-orange-100'],
                    'kecemasan' => ['icon' => 'fa-bolt', 'color' => 'from-violet-500 to-indigo-600', 'bgLight' => 'bg-violet-50 text-violet-600', 'shadowGlow' => 'shadow-violet-100'],
                    'depresi' => ['icon' => 'fa-cloud-showers-heavy', 'color' => 'from-teal-500 to-emerald-600', 'bgLight' => 'bg-teal-50 text-teal-600', 'shadowGlow' => 'shadow-teal-100'],
                    default => ['icon' => 'fa-notes-medical', 'color' => 'from-slate-500 to-slate-600', 'bgLight' => 'bg-slate-50 text-slate-600', 'shadowGlow' => 'shadow-slate-100'],
                };
            @endphp

            <div class="group relative flex flex-col justify-between overflow-hidden rounded-[30px] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_20px_50px_rgba(1,88,142,0.08)] hover:border-slate-200">
                <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-slate-50/50 transition-all duration-500 group-hover:scale-150 group-hover:bg-gradient-to-br {{ $cardConfig['color'] }} group-hover:opacity-10"></div>
                
                <div class="relative z-10">
                    {{-- Icon Box dengan Efek Bayangan Lembut --}}
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $cardConfig['bgLight'] }} border border-slate-50 {{ $cardConfig['shadowGlow'] }} shadow-md transition-all duration-300 group-hover:scale-105">
                        <i class="fas {{ $cardConfig['icon'] }} text-2xl"></i>
                    </div>

                    <h3 class="mt-6 text-xl font-black text-slate-900 transition duration-300 group-hover:text-[#01588E]">
                        Tes {{ $instrumen->nama_tes }}
                    </h3>

                    <p class="mt-3 text-xs font-bold leading-relaxed text-slate-400">
                        {{ $instrumen->deskripsi }}
                    </p>

                    <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-50 border border-slate-100 px-3 py-1.5 text-xs font-black text-slate-500">
                        <i class="far fa-file-lines text-[#01588E]"></i>
                        {{ $instrumen->pertanyaans_count }} Butir Soal
                    </div>
                </div>

                {{-- Action Button Row --}}
                <div class="relative z-10 mt-8">
                    <a href="{{ route('pasien.self-assessment.show', $instrumen->slug) }}" 
                       class="inline-flex w-full h-12 items-center justify-center gap-2 rounded-2xl bg-slate-50 px-5 text-sm font-black text-[#01588E] ring-1 ring-[#01588E]/10 transition-all duration-300 hover:bg-[#01588E] hover:text-white hover:shadow-md">
                        Mulai Tes Sekarang
                        <i class="fas fa-arrow-right text-[10px] transition-transform duration-300 group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection