@extends('frontend.layouts.guest')

@section('title', 'Artikel Kesehatan - MindHaven')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="min-h-screen bg-white text-slate-900">

    <section class="relative overflow-hidden bg-gradient-to-br from-[#06182E] via-[#0A3D66] to-[#03101F] px-8 py-8 text-white md:px-12">

        <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-[#41AD01]/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full bg-[#01588E]/40 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl">

            <header class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white shadow-[0_10px_30px_rgba(255,255,255,0.12)] transition group-hover:scale-105">
                        <img src="{{ asset('assets/images/logo_polos.png') }}"
                             alt="MindHaven Logo"
                             class="h-8 w-8 object-contain">
                    </div>

                    <div>
                        <h1 class="text-xl font-black tracking-tight text-white">
                            MindHaven
                        </h1>
                        <p class="text-xs font-semibold text-white/75">
                            Digital Mental Care
                        </p>
                    </div>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}"
                       class="rounded-xl bg-white px-5 py-2.5 text-sm font-black text-[#01588E] transition hover:-translate-y-1 hover:bg-[#41AD01] hover:text-white">
                        Home
                    </a>

                    <a href="{{ route('login') }}"
                       class="hidden rounded-xl border border-white/25 bg-white/10 px-5 py-2.5 text-sm font-black text-white backdrop-blur-xl transition hover:bg-white hover:text-[#01588E] sm:inline-flex">
                        Login
                    </a>
                </div>
            </header>

            <div class="py-20">
                <div class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-black text-[#01588E]">
                    <span class="h-2 w-2 rounded-full bg-[#41AD01]"></span>
                    Artikel Public MindHaven
                </div>

                <h2 class="mt-6 max-w-3xl text-4xl font-black leading-tight tracking-tight text-white md:text-6xl">
                    Artikel kesehatan mental untuk edukasi dan pemahaman diri.
                </h2>

                <p class="mt-5 max-w-2xl text-base leading-8 text-blue-100">
                    Baca artikel edukasi dari MindHaven seputar kesehatan mental, pengelolaan emosi, konsultasi, dan pemulihan diri.
                </p>
            </div>

        </div>
    </section>

    <section class="px-8 py-14 md:px-12">
        <div class="mx-auto max-w-7xl">

            @if($artikels->count() > 0)

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

                    @foreach($artikels as $artikel)

                        <a href="{{ route('artikel.show', $artikel->id_artikel) }}"
                           class="group overflow-hidden rounded-[2rem] border border-slate-100 bg-white p-4 shadow-[0_18px_50px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-2 hover:shadow-[0_24px_70px_rgba(1,88,142,0.18)]">

                            <div class="h-56 overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-[#01588E] to-[#41AD01]">
                                @if($artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}"
                                         alt="{{ $artikel->judul }}"
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-white">
                                        <svg class="h-14 w-14" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4V5Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h8M8 17h4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="rounded-full bg-[#41AD01]/10 px-3 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#41AD01]">
                                        {{ $artikel->kategori ?? 'Artikel' }}
                                    </p>

                                    @if($artikel->tanggal_publish)
                                        <p class="rounded-full bg-[#01588E]/10 px-3 py-1 text-xs font-bold text-[#01588E]">
                                            {{ $artikel->tanggal_publish->format('d M Y') }}
                                        </p>
                                    @endif
                                </div>

                                <h3 class="mt-3 line-clamp-2 text-xl font-black leading-snug text-slate-950">
                                    {{ $artikel->judul }}
                                </h3>

                                <p class="mt-3 line-clamp-3 text-sm leading-7 text-slate-600">
                                    {{ Str::limit(strip_tags($artikel->konten), 150) }}
                                </p>

                                <div class="mt-5 space-y-2">
                                    <p class="text-xs font-semibold text-slate-500">
                                        Penulis:
                                        <span class="font-black text-slate-700">
                                            {{ $artikel->penulis ?? 'Tim MindHaven' }}
                                        </span>
                                    </p>

                                    @if($artikel->sumber_artikel)
                                        <p class="text-xs font-semibold text-slate-500">
                                            Sumber:
                                            <span class="font-black text-slate-700">
                                                {{ $artikel->sumber_artikel }}
                                            </span>
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-5 inline-flex items-center gap-2 text-sm font-black text-[#01588E]">
                                    Baca Selengkapnya
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </div>

                        </a>

                    @endforeach

                </div>

                <div class="mt-10">
                    {{ $artikels->links() }}
                </div>

            @else

                <div class="rounded-[2rem] border border-slate-100 bg-slate-50 p-10 text-center">
                    <h3 class="text-2xl font-black text-slate-950">
                        Belum Ada Artikel
                    </h3>
                    <p class="mt-3 text-sm font-semibold leading-7 text-slate-500">
                        Artikel yang dipublikasikan admin akan tampil di halaman ini.
                    </p>
                </div>

            @endif

        </div>
    </section>

</div>

@endsection