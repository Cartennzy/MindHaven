@extends('frontend.layouts.guest')

@section('title', 'MindHaven - Digital Mental Care')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<style>
    html { scroll-behavior: smooth; }
    body { background: #F4FBFF; }

    .mh-page {
        background:
            radial-gradient(circle at 12% 8%, rgba(40, 174, 218, .16), transparent 30%),
            radial-gradient(circle at 88% 12%, rgba(1, 88, 142, .10), transparent 32%),
            linear-gradient(180deg, #F4FBFF 0%, #FFFFFF 46%, #EEF8FC 100%);
    }

    .mh-container { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }

    .mh-scroll-progress {
        position: fixed; left: 0; top: 0; z-index: 9999; width: 0%; height: 4px;
        background: linear-gradient(90deg, #28AEDA, #7DDCEF, #01588E);
        box-shadow: 0 0 18px rgba(40, 174, 218, .45);
        transition: width .08s linear;
    }

    .mh-glass-nav {
        background: linear-gradient(135deg, rgba(40, 174, 218, .82), rgba(1, 88, 142, .60));
        border: 1px solid rgba(255, 255, 255, .58);
        backdrop-filter: blur(22px); -webkit-backdrop-filter: blur(22px);
        box-shadow: 0 18px 55px rgba(14, 116, 144, .22), inset 0 1px 0 rgba(255,255,255,.58);
    }

    .mh-nav-link { position: relative; }
    .mh-nav-link::after {
        content: ""; position: absolute; left: 16px; right: 16px; bottom: 7px; height: 2px;
        border-radius: 999px; background: #fff; transform: scaleX(0); transform-origin: center; transition: transform .25s ease;
    }
    .mh-nav-link:hover::after { transform: scaleX(1); }

    .mh-line-pattern {
        background-image:
            linear-gradient(rgba(111, 179, 199, .08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(111, 179, 199, .08) 1px, transparent 1px);
        background-size: 28px 28px;
    }

    .mh-dot-pattern {
        background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.18) 1px, transparent 0);
        background-size: 22px 22px;
    }

    .mh-hero-bg {
        min-height: 780px;
        background-image:
            linear-gradient(90deg, rgba(244, 251, 255, .94) 0%, rgba(244, 251, 255, .82) 35%, rgba(244, 251, 255, .36) 62%, rgba(244, 251, 255, .12) 100%),
            linear-gradient(180deg, rgba(244, 251, 255, .76) 0%, rgba(244, 251, 255, .05) 46%, rgba(244, 251, 255, .74) 100%),
            url('{{ asset('assets/images/landing-section.png') }}');
        background-size: cover; background-position: center right; background-repeat: no-repeat;
    }

    .mh-hero-bg::before {
        content: ""; position: absolute; inset: 0; pointer-events: none; opacity: .45;
        background-image:
            linear-gradient(rgba(111, 179, 199, .055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(111, 179, 199, .055) 1px, transparent 1px);
        background-size: 28px 28px;
    }

    .mh-hero-bg::after {
        content: ""; position: absolute; right: -80px; bottom: -120px; width: 520px; height: 520px;
        border-radius: 999px; background: radial-gradient(circle, rgba(40, 174, 218, .14), transparent 67%); pointer-events: none;
    }

    .mh-reveal, .mh-reveal-left, .mh-reveal-right {
        opacity: 0;
        transition: opacity .8s cubic-bezier(.2,.8,.2,1), transform .8s cubic-bezier(.2,.8,.2,1);
    }

    .mh-reveal { transform: translateY(34px); }
    .mh-reveal-left { transform: translateX(-42px); }
    .mh-reveal-right { transform: translateX(42px); }

    .mh-reveal.mh-show,
    .mh-reveal-left.mh-show,
    .mh-reveal-right.mh-show {
        opacity: 1;
        transform: translate(0, 0);
    }

    .mh-hover {
        position: relative;
        overflow: hidden;
        transition: transform .32s ease, box-shadow .32s ease, border-color .32s ease, background .32s ease;
    }

    .mh-hover::after {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        transform: translateX(-130%);
        background: linear-gradient(105deg, transparent, rgba(255,255,255,.32), transparent);
        transition: transform .8s ease;
    }

    .mh-hover:hover {
        transform: translateY(-7px);
        border-color: rgba(31, 182, 230, .46);
        box-shadow: 0 28px 75px rgba(14, 116, 144, .15), 0 0 0 1px rgba(31,182,230,.12);
    }

    .mh-hover:hover::after { transform: translateX(130%); }

    .mh-btn { transition: transform .25s ease, box-shadow .25s ease, background .25s ease, color .25s ease; }
    .mh-btn:hover { transform: translateY(-3px); }

    .mh-shine { position: relative; overflow: hidden; }
    .mh-shine::after {
        content: "";
        position: absolute;
        inset: 0;
        transform: translateX(-120%);
        background: linear-gradient(105deg, transparent, rgba(255,255,255,.38), transparent);
        transition: transform .75s ease;
    }

    .mh-shine:hover::after { transform: translateX(120%); }

    .mh-pulse { animation: mhPulse 2.8s ease-in-out infinite; }

    @keyframes mhPulse {
        0%,100% { box-shadow: 0 0 0 0 rgba(40,174,218,.32); }
        50% { box-shadow: 0 0 0 13px rgba(40,174,218,0); }
    }

    .mh-page h1, .mh-page h2, .mh-page h3 {
        font-weight: 750;
        letter-spacing: -0.035em;
    }

    .mh-page p { text-rendering: optimizeLegibility; }

    .mh-soft-card {
        background: linear-gradient(145deg, rgba(255,255,255,.98), rgba(232,248,255,.92));
        border: 1px solid rgba(190, 231, 244, .90);
        box-shadow: 0 20px 55px rgba(14,116,144,.10), inset 0 1px 0 rgba(255,255,255,.90);
    }

    .mh-premium-section {
        background: linear-gradient(135deg, #F7FCFF 0%, #FFFFFF 45%, #EAF8FD 100%);
    }

    .mh-pro-card {
        background: linear-gradient(145deg, rgba(255,255,255,.98), rgba(239,250,255,.94));
        border: 1px solid rgba(204,238,249,.9);
        box-shadow: 0 20px 55px rgba(14,116,144,.12);
    }

    .mh-feature-visual {
        min-height: 560px;
        background:
            linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.06)),
            radial-gradient(circle at 70% 25%, rgba(255,255,255,.22), transparent 30%);
        border: 1px solid rgba(255,255,255,.22);
        box-shadow: 0 34px 90px rgba(0,45,70,.28), inset 0 1px 0 rgba(255,255,255,.18);
    }

    .mh-feature-card {
        background: rgba(255,255,255,.92);
        border: 1px solid rgba(255,255,255,.76);
        backdrop-filter: blur(22px);
        -webkit-backdrop-filter: blur(22px);
        box-shadow: 0 24px 70px rgba(1,88,142,.18);
    }

    @media (max-width: 1023px) {
        .mh-hero-bg {
            min-height: auto;
            background-image:
                linear-gradient(90deg, rgba(244, 251, 255,.96), rgba(244, 251, 255,.84)),
                url('{{ asset('assets/images/landing-section.png') }}');
            background-position: center top;
        }

        .mh-feature-visual { min-height: auto; }
    }

    @media (max-width: 767px) {
        .mh-container { width: min(100% - 24px, 1180px); }

        .mh-hero-bg {
            background-image:
                linear-gradient(180deg, rgba(244, 251, 255,.97), rgba(244, 251, 255,.90)),
                url('{{ asset('assets/images/landing-section.png') }}');
            background-position: center top;
        }
    }
</style>

<div class="mh-scroll-progress" id="mhScrollProgress"></div>

<div class="mh-page min-h-screen overflow-hidden text-slate-900">

    {{-- HERO --}}
    <section id="home" class="mh-hero-bg relative overflow-hidden pb-12">
        <div class="relative mh-container pt-4">
            <header class="mh-glass-nav sticky top-4 z-50 flex items-center justify-between rounded-[1.6rem] px-4 py-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white shadow-[0_12px_28px_rgba(255,255,255,.35)]">
                        <img src="{{ asset('assets/images/logo_polos.png') }}" alt="MindHaven Logo" class="h-8 w-8 object-contain">
                    </div>

                    <div class="leading-tight">
                        <h1 class="text-base font-semibold text-white">MindHaven</h1>
                        <p class="text-xs font-medium text-white/85">Digital Mental Care</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-1 lg:flex">
                    <a href="#home" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Home</a>
                    <a href="#layanan" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Services</a>
                    <a href="#fitur" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Fitur</a>
                    <a href="#psikolog" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Psikolog</a>
                    <a href="#testimoni" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Testimoni</a>
                    <a href="#mitra" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Mitra</a>
                    <a href="#artikel" class="mh-nav-link rounded-xl px-4 py-2.5 text-sm font-medium text-white/90 hover:bg-white/12">Artikel</a>
                </nav>

                <div class="hidden items-center gap-3 sm:flex">
                    <a href="{{ route('login') }}" class="mh-btn rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-[#159AC8] shadow-[0_12px_28px_rgba(255,255,255,.24)] hover:bg-[#EEF9FF]">Login</a>
                    <a href="{{ route('register') }}" class="mh-btn mh-shine rounded-full bg-[#1B4252] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_14px_30px_rgba(27,66,82,.22)] hover:bg-[#143544]">Daftar</a>
                </div>

                <button type="button" id="mobileMenuButton" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/18 text-white lg:hidden">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>
            </header>

            <div id="mobileMenu" class="relative z-40 mt-3 hidden rounded-[1.5rem] bg-[#70AFC2]/95 p-4 shadow-2xl backdrop-blur-xl lg:hidden">
                <div class="grid gap-2">
                    <a href="#home" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Home</a>
                    <a href="#layanan" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Services</a>
                    <a href="#fitur" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Features</a>
                    <a href="#psikolog" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Psikolog</a>
                    <a href="#testimoni" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Testimoni</a>
                    <a href="#mitra" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Mitra</a>
                    <a href="#artikel" class="rounded-xl px-4 py-3 text-sm font-medium text-white hover:bg-white/15">Blog</a>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a href="{{ route('login') }}" class="rounded-xl bg-white px-4 py-3 text-center text-sm font-semibold text-[#159AC8]">Login</a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-[#1B4252] px-4 py-3 text-center text-sm font-semibold text-white">Daftar</a>
                    </div>
                </div>
            </div>

            <div class="relative grid min-h-[650px] grid-cols-1 items-center gap-10 py-14 lg:grid-cols-2 lg:py-16">
                <div class="mh-reveal-left relative z-10">
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/76 px-4 py-2 text-xs font-semibold text-[#159AC8] shadow-[0_12px_28px_rgba(76,139,158,.10)] backdrop-blur-xl">
                        <span class="h-2 w-2 rounded-full bg-[#28AEDA] mh-pulse"></span>
                        Digital mental care platform
                    </div>

                    <h2 class="max-w-2xl text-4xl font-bold leading-tight tracking-tight text-[#172033] md:text-5xl lg:text-6xl">
                        Konsultasi mental lebih tenang dan terarah.
                    </h2>

                    <p class="mt-6 max-w-xl text-sm leading-8 text-[#4F6675] md:text-base">
                        MindHaven membantu pasien terhubung dengan psikolog profesional, mengelola hasil konsultasi, meditasi digital, artikel edukasi, dan rujukan psikiater dalam satu sistem yang rapi.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="{{ route('register') }}" class="mh-btn mh-shine inline-flex items-center gap-3 rounded-full bg-[#28AEDA] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_rgba(82,162,186,.26)] hover:bg-[#149DCC]">
                            Mulai
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-[#159AC8]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </span>
                        </a>

                        <a href="#layanan" class="mh-btn inline-flex items-center gap-3 rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#159AC8] shadow-[0_16px_34px_rgba(76,139,158,.14)] hover:bg-[#EEF9FF]">
                            Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- KATEGORI LAYANAN --}}
    <section id="layanan" class="bg-white px-4 pb-16 pt-20">
        <div class="mh-container">
            <div class="mh-reveal mx-auto max-w-2xl text-center">
                <p class="text-sm font-bold uppercase tracking-[0.28em] text-[#28AEDA]">MindHaven Services</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#172033] md:text-5xl">Layanan Utama</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-8 text-[#6B7D87] md:text-base">Tiga fitur inti yang menjadi alur utama sistem: konsultasi, hasil, dan rujukan.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-3">
                <a href="{{ route('register') }}" class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#159AC8] shadow-[0_12px_30px_rgba(14,116,144,.12)]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                        </svg>
                    </div>

                    <h3 class="mt-6 text-xl font-bold text-[#172033]">Konsultasi</h3>
                    <p class="mt-3 text-sm leading-8 text-[#6B7D87]">Pasien memilih psikolog, jadwal, metode konsultasi, dan menyampaikan keluhan dengan alur yang jelas.</p>
                    <div class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#159AC8]">Mulai konsultasi <span>→</span></div>
                </a>

                <a href="#fitur" class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#159AC8] shadow-[0_12px_30px_rgba(14,116,144,.12)]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13H7V3Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M10 13h6M10 17h6"/>
                        </svg>
                    </div>

                    <h3 class="mt-6 text-xl font-bold text-[#172033]">Hasil</h3>
                    <p class="mt-3 text-sm leading-8 text-[#6B7D87]">Psikolog mengisi catatan, observasi, diagnosis awal, dan rencana penanganan secara terstruktur.</p>
                    <div class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#159AC8]">Lihat alur <span>→</span></div>
                </a>

                <a href="#fitur" class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#159AC8] shadow-[0_12px_30px_rgba(14,116,144,.12)]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 10h6M12 7v6"/>
                        </svg>
                    </div>

                    <h3 class="mt-6 text-xl font-bold text-[#172033]">Rujukan</h3>
                    <p class="mt-3 text-sm leading-8 text-[#6B7D87]">Jika perlu tindak lanjut, psikolog dapat membuat surat rujukan psikiater yang bisa dilihat pasien.</p>
                    <div class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-[#159AC8]">Tindak lanjut <span>→</span></div>
                </a>
            </div>
        </div>
    </section>

    {{-- WORLD CLASS MENTAL CARE --}}
    <section id="fitur" class="relative overflow-hidden px-4 py-24 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-[#39BDE8] via-[#1BA6D3] to-[#075D8C]"></div>
        <div class="absolute inset-0 mh-dot-pattern opacity-50"></div>
        <div class="absolute left-[-120px] top-[-120px] h-[360px] w-[360px] rounded-full bg-white/18 blur-3xl"></div>
        <div class="absolute right-[-120px] bottom-[-160px] h-[420px] w-[420px] rounded-full bg-[#012D46]/24 blur-3xl"></div>
        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-white/16 to-transparent"></div>

        <div class="relative mh-container grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
            <div class="mh-reveal-left lg:col-span-5">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-white/80">World-Class Mental Care</p>
                <h2 class="mt-5 max-w-xl text-4xl font-bold leading-tight tracking-[-0.04em] md:text-5xl">Sistem konsultasi mental yang rapi.</h2>
                <p class="mt-6 max-w-lg text-base font-medium leading-8 text-white/88">MindHaven menyatukan konsultasi pasien, hasil psikolog, pembayaran, dan rujukan lanjutan dalam satu alur yang mudah dipahami.</p>

                <div class="mt-8 grid max-w-lg grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-white/14 p-4 backdrop-blur-md ring-1 ring-white/18">
                        <p class="text-2xl font-bold">01</p>
                        <p class="mt-1 text-xs font-semibold text-white/80">Pilih psikolog</p>
                    </div>

                    <div class="rounded-2xl bg-white/14 p-4 backdrop-blur-md ring-1 ring-white/18">
                        <p class="text-2xl font-bold">02</p>
                        <p class="mt-1 text-xs font-semibold text-white/80">Konsultasi</p>
                    </div>

                    <div class="rounded-2xl bg-white/14 p-4 backdrop-blur-md ring-1 ring-white/18">
                        <p class="text-2xl font-bold">03</p>
                        <p class="mt-1 text-xs font-semibold text-white/80">Hasil & rujukan</p>
                    </div>
                </div>

                <a href="{{ route('register') }}" class="mh-btn mt-9 inline-flex items-center gap-3 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-[#159AC8] shadow-[0_18px_40px_rgba(255,255,255,.16)] hover:bg-[#F4FBFF]">
                    Mulai Konsultasi
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>

            <div class="mh-reveal-right lg:col-span-7">
                <div class="mh-feature-visual relative min-h-[560px] overflow-hidden rounded-[2.5rem] p-6 md:p-8">
                    <div class="absolute right-8 top-16 z-0 h-[450px] w-[450px] rounded-full bg-white/16 blur-[110px]"></div>

                    <div class="absolute bottom-0 right-4 z-30 hidden h-[535px] w-[390px] overflow-visible md:block">
                        <img
                            src="{{ asset('assets/images/psikolog-hero.png') }}"
                            alt="Psikolog MindHaven"
                            class="h-full w-full object-contain object-bottom drop-shadow-[0_38px_90px_rgba(0,36,58,.34)]">
                    </div>

                    <div class="relative z-10 grid max-w-[415px] gap-5">
                        <div class="mh-feature-card rounded-[1.7rem] p-6 text-[#172033] md:mr-8">
                            <h3 class="text-lg font-bold">Konsultasi</h3>
                            <p class="mt-2 text-sm font-medium leading-7 text-[#667985]">Pasien memilih psikolog, jadwal, metode, dan mengisi keluhan.</p>
                        </div>

                        <div class="mh-feature-card rounded-[1.7rem] p-6 text-[#172033] md:ml-12 md:mr-2">
                            <h3 class="text-lg font-bold">Hasil Konsultasi</h3>
                            <p class="mt-2 text-sm font-medium leading-7 text-[#667985]">Catatan observasi dan rencana penanganan tersimpan rapi.</p>
                        </div>

                        <div class="mh-feature-card rounded-[1.7rem] p-6 text-[#172033] md:mr-10">
                            <h3 class="text-lg font-bold">Rujukan</h3>
                            <p class="mt-2 text-sm font-medium leading-7 text-[#667985]">Psikolog dapat membuat rujukan jika pasien perlu tindak lanjut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT & WHY --}}
    <section class="mh-premium-section px-4 py-20">
        <div class="mh-container grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-stretch">
            <div class="mh-reveal-left lg:col-span-5">
                <div class="h-full overflow-hidden rounded-[2rem] bg-white p-8 shadow-[0_26px_70px_rgba(16,73,91,.12)]">
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#159AC8]">About Us</p>
                    <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-[#172033] md:text-4xl">MindHaven membuat layanan mental lebih mudah diakses.</h2>
                    <p class="mt-5 text-sm leading-8 text-[#6B7D87]">Platform ini menjadi jembatan antara pasien dan psikolog profesional dengan alur konsultasi yang jelas, tampilan modern, dan data layanan yang tertata.</p>

                    <div class="mt-8 grid gap-4">
                        <div class="rounded-3xl bg-[#EEF9FF] p-5 ring-1 ring-[#D7EDF4]">
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-[#159AC8] font-bold">P</div>
                                <div>
                                    <p class="text-base font-bold text-[#172033]">Untuk Pasien</p>
                                    <p class="mt-2 text-sm leading-7 text-[#6B7D87]">Konsultasi, pembayaran, hasil, artikel, meditasi, dan rujukan tersusun dalam satu akses.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-white p-5 ring-1 ring-[#D7EDF4]">
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#EEF9FF] text-[#159AC8] font-bold">Ψ</div>
                                <div>
                                    <p class="text-base font-bold text-[#172033]">Untuk Psikolog</p>
                                    <p class="mt-2 text-sm leading-7 text-[#6B7D87]">Mengelola sesi, mengisi hasil konsultasi, dan membuat rujukan secara profesional.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mh-reveal-right lg:col-span-7">
                <div class="h-full rounded-[2rem] bg-gradient-to-br from-[#0F6D95] to-[#064762] p-7 text-white shadow-[0_26px_70px_rgba(1,88,142,.20)]">
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-white/75">Why MindHaven</p>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight md:text-4xl">Alur jelas, data rapi, tindak lanjut mudah.</h2>
                    <p class="mt-4 max-w-2xl text-sm leading-8 text-white/78">Setiap bagian dibuat agar pengguna tidak bingung saat memulai konsultasi sampai menerima hasil dari psikolog.</p>

                    <div class="mt-8 grid gap-4 md:grid-cols-3">
                        <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-md ring-1 ring-white/15">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#159AC8] text-sm font-bold">01</div>
                            <h3 class="mt-5 font-bold">Alur Bertahap</h3>
                            <p class="mt-2 text-xs leading-6 text-white/76">Pasien diarahkan dari pilih psikolog sampai melihat hasil konsultasi.</p>
                        </div>

                        <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-md ring-1 ring-white/15">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#159AC8] text-sm font-bold">02</div>
                            <h3 class="mt-5 font-bold">Data Terstruktur</h3>
                            <p class="mt-2 text-xs leading-6 text-white/76">Catatan konsultasi tersimpan sesuai kebutuhan pasien dan psikolog.</p>
                        </div>

                        <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-md ring-1 ring-white/15">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#159AC8] text-sm font-bold">03</div>
                            <h3 class="mt-5 font-bold">Rujukan Lanjutan</h3>
                            <p class="mt-2 text-xs leading-6 text-white/76">Psikolog dapat membuat rujukan psikiater ketika pasien membutuhkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PSIKOLOG PROFESIONAL --}}
    <section id="psikolog" class="bg-white px-4 py-20">
        <div class="mh-container">
            <div class="mh-reveal mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#159AC8]">Professional Psychologist</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#172033] md:text-4xl">Psikolog Profesional</h2>
                    <p class="mt-3 max-w-xl text-sm leading-8 text-[#6B7D87]">Profil psikolog ditampilkan dari data psikolog terdaftar pada sistem MindHaven.</p>
                </div>

                <a href="{{ route('register') }}" class="mh-btn inline-flex rounded-full bg-[#28AEDA] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_rgba(82,162,186,.25)] hover:bg-[#149DCC]">
                    Mulai Konsultasi
                </a>
            </div>

            @if(isset($psikologs) && $psikologs->count() > 0)
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    @foreach($psikologs->take(3) as $psikolog)
                        <div class="mh-reveal mh-hover mh-pro-card rounded-3xl p-5">
                            <div class="h-64 overflow-hidden rounded-[1.5rem] bg-[#EAF8FD]">
                                @if(isset($psikolog->foto_profil) && $psikolog->foto_profil)
                                    <img src="{{ asset('storage/' . $psikolog->foto_profil) }}" alt="{{ $psikolog->nama_lengkap }}" class="h-full w-full object-cover object-top transition duration-500 hover:scale-105">
                                @else
                                    <img src="{{ asset('assets/images/psikolog-hero.png') }}" alt="Psikolog MindHaven" class="h-full w-full object-cover object-top transition duration-500 hover:scale-105">
                                @endif
                            </div>

                            <div class="mt-5">
                                <h3 class="text-lg font-bold text-[#172033]">{{ $psikolog->nama_lengkap ?? 'Psikolog MindHaven' }}</h3>
                                <p class="mt-1 text-sm font-semibold text-[#159AC8]">{{ $psikolog->spesialisasi ?? 'Psikolog Profesional' }}</p>
                                <p class="mt-3 line-clamp-3 text-sm leading-7 text-[#6B7D87]">{{ $psikolog->bio ?? 'Siap membantu pasien melalui konsultasi yang aman, terarah, dan profesional.' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="mh-reveal mh-hover mh-pro-card rounded-3xl p-5">
                            <div class="h-64 overflow-hidden rounded-[1.5rem] bg-[#EAF8FD]">
                                <img src="{{ asset('assets/images/psikolog-hero.png') }}" alt="Psikolog MindHaven" class="h-full w-full object-cover object-top">
                            </div>

                            <h3 class="mt-5 text-lg font-bold text-[#172033]">Psikolog Profesional</h3>
                            <p class="mt-1 text-sm font-semibold text-[#159AC8]">MindHaven Partner</p>
                            <p class="mt-3 text-sm leading-7 text-[#6B7D87]">Data psikolog akan tampil otomatis ketika controller mengirim variabel psikolog ke halaman home.</p>
                        </div>
                    @endfor
                </div>
            @endif
        </div>
    </section>

    {{-- TESTIMONI (APA KATA MEREKA) --}}
    <section id="testimoni" class="bg-[#F8FDFF] border-t border-b border-[#E3F5FC] px-4 py-20">
        <div class="mh-container">
            <div class="mh-reveal mx-auto max-w-2xl text-center mb-12">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#159AC8]">Testimonials</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#172033] md:text-4xl">Apa Kata Mereka</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-8 text-[#6B7D87] md:text-base">Cerita nyata dari mereka yang telah menemukan ketenangan dan arah baru bersama MindHaven.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @if(isset($testimonialsData) && count($testimonialsData) > 0)
                    {{-- RENDERING REALTIME DATABASE TESTIMONIALS --}}
                    @foreach($testimonialsData as $testi)
                        <div class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 text-amber-400 mb-4">
                                    @for($i = 1; $i <= ($testi->bintang ?? 5); $i++)
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="#fbbf24">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-sm leading-7 text-[#4F6675] font-medium italic">"{{ $testi->ulasan }}"</p>
                            </div>
                            <div class="mt-6 flex items-center gap-3.5 pt-5 border-t border-[#E6F4F8]">
                                <div class="h-11 w-11 shrink-0 rounded-full bg-[#D5F1FC] flex items-center justify-center font-bold text-[#159AC8]">
                                    {{ strtoupper(substr($testi->nama ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#172033]">{{ $testi->nama ?? 'Anonim' }}</h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- FALLBACK DATA DUMMY JIKA TABEL DATABASE KOSONG --}}
                    <div class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="#fbbf24">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm leading-7 text-[#4F6675] font-medium italic">"Alur aplikasinya sangat rapi. Saya bisa memilih psikolog yang sesuai dengan kebutuhan saya, dan hasil konsultasi serta catatan penanganannya tersimpan dengan sangat terstruktur. Sangat membantu!"</p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-5 border-t border-[#E6F4F8]">
                            <div class="h-11 w-11 shrink-0 rounded-full bg-[#D5F1FC] flex items-center justify-center font-bold text-[#159AC8]">R</div>
                            <div>
                                <h4 class="text-sm font-bold text-[#172033]">Rian Amanda</h4>
                            </div>
                        </div>
                    </div>

                    <div class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="#fbbf24">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm leading-7 text-[#4F6675] font-medium italic">"Sebagai seseorang yang sibuk, fitur meditasi digital dan sistem penjadwalan otomatis di MindHaven membuat terapi kesehatan mental terasa jauh lebih rileks, fleksibel, dan tidak melelahkan."</p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-5 border-t border-[#E6F4F8]">
                            <div class="h-11 w-11 shrink-0 rounded-full bg-[#D5F1FC] flex items-center justify-center font-bold text-[#159AC8]">S</div>
                            <div>
                                <h4 class="text-sm font-bold text-[#172033]">Siti Sarah</h4>
                            </div>
                        </div>
                    </div>

                    <div class="mh-reveal mh-hover mh-soft-card rounded-[2rem] p-7 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="#fbbf24">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm leading-7 text-[#4F6675] font-medium italic">"Fitur rujukan psikiater terintegrasi dengan sangat baik saat saya butuh penanganan medis lanjutan. Transparansi data rekam medis di platform digital ini benar-benar luar biasa."</p>
                        </div>
                        <div class="mt-6 flex items-center gap-3.5 pt-5 border-t border-[#E6F4F8]">
                            <div class="h-11 w-11 shrink-0 rounded-full bg-[#D5F1FC] flex items-center justify-center font-bold text-[#159AC8]">D</div>
                            <div>
                                <h4 class="text-sm font-bold text-[#172033]">Dimas Pratama</h4>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-12 text-center mh-reveal">
                <a href="{{ route('register') }}" class="mh-btn mh-shine inline-flex items-center gap-3 rounded-full bg-[#28AEDA] px-7 py-3.5 text-sm font-bold text-white shadow-[0_18px_38px_rgba(82,162,186,.26)] hover:bg-[#149DCC]">
                    Tulis Ceritamu Sendiri
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- PARTNERSHIP CTA MITRA --}}
    <section id="mitra" class="relative overflow-hidden bg-[#EEF9FF] px-4 py-20">
        <div class="absolute inset-0 mh-line-pattern opacity-70"></div>

        <div class="relative mh-container">
            <div class="overflow-hidden rounded-[2.4rem] bg-white shadow-[0_28px_80px_rgba(14,116,144,.14)]">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="mh-reveal-left p-8 md:p-12">
                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#159AC8]">Partnership Program</p>
                        <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-[#172033] md:text-5xl">Bergabung sebagai mitra psikolog.</h2>
                        <p class="mt-5 max-w-xl text-sm leading-8 text-[#6B7D87] md:text-base">
                            Psikolog profesional dapat mengajukan kerja sama secara online. Setelah diverifikasi, akun dapat digunakan untuk mengelola layanan konsultasi pasien.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('psikolog.register') }}" class="mh-btn mh-shine inline-flex items-center justify-center rounded-full bg-[#28AEDA] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_rgba(82,162,186,.30)] hover:bg-[#149DCC]">
                                Ajukan Mitra
                            </a>

                            <a href="{{ route('backend.login') }}" class="mh-btn inline-flex items-center justify-center rounded-full bg-[#EEF9FF] px-6 py-3 text-sm font-semibold text-[#159AC8] hover:bg-[#F7FCFE]">
                                Masuk 
                            </a>
                        </div>
                    </div>

                    <div class="mh-reveal-right bg-gradient-to-br from-[#28AEDA] to-[#0477A7] p-8 text-white md:p-12">
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-white/72">Benefit Mitra</p>
                        <h3 class="mt-3 text-2xl font-bold">Fitur untuk psikolog</h3>

                        <div class="mt-7 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl bg-white/13 p-5 ring-1 ring-white/15 backdrop-blur-md">
                                <h4 class="font-bold">Kelola Sesi</h4>
                                <p class="mt-2 text-sm leading-7 text-white/78">Pantau konsultasi pasien dalam tampilan yang rapi.</p>
                            </div>

                            <div class="rounded-3xl bg-white/13 p-5 ring-1 ring-white/15 backdrop-blur-md">
                                <h4 class="font-bold">Input Hasil</h4>
                                <p class="mt-2 text-sm leading-7 text-white/78">Catatan konsultasi tersimpan secara terstruktur.</p>
                            </div>

                            <div class="rounded-3xl bg-white/13 p-5 ring-1 ring-white/15 backdrop-blur-md">
                                <h4 class="font-bold">Buat Rujukan</h4>
                                <p class="mt-2 text-sm leading-7 text-white/78">Rujukan psikiater dapat dibuat saat pasien butuh tindak lanjut.</p>
                            </div>

                            <div class="rounded-3xl bg-white/13 p-5 ring-1 ring-white/15 backdrop-blur-md">
                                <h4 class="font-bold">Profil Tampil</h4>
                                <p class="mt-2 text-sm leading-7 text-white/78">Profil psikolog aktif dapat ditampilkan pada sistem.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ARTICLE --}}
    <section id="artikel" class="bg-white px-4 py-20">
        <div class="mh-container">
            <div class="mh-reveal mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#159AC8]">Education Content</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#172033] md:text-4xl">Artikel Kesehatan MindHaven</h2>
                </div>

                <div class="max-w-xl md:text-right">
                    <p class="text-sm leading-7 text-[#6B7D87]">Konten edukasi kesehatan mental untuk membantu pengguna memahami kondisi mental dengan lebih baik.</p>
                    <a href="{{ route('artikel.index') }}" class="mh-btn mt-4 inline-flex rounded-full bg-[#28AEDA] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_rgba(82,162,186,.25)] hover:bg-[#149DCC]">
                        Lihat Artikel
                    </a>
                </div>
            </div>

            @if(isset($artikels) && $artikels->count() > 0)
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    @foreach($artikels as $artikel)
                        <a href="{{ route('artikel.show', ['artikel' => $artikel->id_artikel]) }}" class="mh-reveal mh-hover group overflow-hidden rounded-3xl border border-[#D7EDF4] bg-white p-4 shadow-[0_18px_45px_rgba(76,139,158,.10)]">
                            <div class="h-44 overflow-hidden rounded-2xl bg-gradient-to-br from-[#A7DCEB] to-[#6EB9CF]">
                                @if(isset($artikel->gambar) && $artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                @elif(isset($artikel->cover) && $artikel->cover)
                                    <img src="{{ asset('storage/' . $artikel->cover) }}" alt="{{ $artikel->judul }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-white">
                                        <svg class="h-14 w-14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4V5Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h8M8 17h4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <h3 class="mt-4 line-clamp-2 text-lg font-bold text-[#172033]">
                                {{ $artikel->judul }}
                            </h3>

                            <p class="mt-2 line-clamp-3 text-sm leading-7 text-[#6B7D87]">
                                {{ Str::limit(strip_tags($artikel->konten ?? $artikel->isi ?? ''), 120) }}
                            </p>

                            <div class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#159AC8]">
                                Baca
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="mh-reveal rounded-3xl border border-[#D7EDF4] bg-[#EEF9FF] p-10 text-center">
                    <p class="text-sm font-medium text-[#5F6F7C]">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="relative overflow-hidden bg-[#0B2F3D] px-4 pt-16 text-white">
        <div class="absolute left-[-120px] top-[-120px] h-72 w-72 rounded-full bg-[#28AEDA]/20 blur-3xl"></div>
        <div class="absolute right-[-120px] bottom-[-120px] h-80 w-80 rounded-full bg-[#41AD01]/10 blur-3xl"></div>

        <div class="relative mh-container">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-lg">
                            <img src="{{ asset('assets/images/logo_polos.png') }}"
                                 alt="MindHaven Logo"
                                 class="h-10 w-10 object-contain">
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold tracking-tight">MindHaven</h3>
                            <p class="mt-1 text-sm font-medium text-white/70">Digital Mental Care</p>
                        </div>
                    </div>

                    <p class="mt-6 max-w-md text-sm leading-8 text-white/70">
                        Platform konsultasi kesehatan mental digital yang membantu pasien terhubung
                        dengan psikolog profesional, mengelola hasil konsultasi, dan mendapatkan
                        rujukan lanjutan secara lebih rapi.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}"
                           class="rounded-full bg-[#28AEDA] px-5 py-3 text-sm font-bold text-white shadow-[0_16px_35px_rgba(40,174,218,.25)] transition hover:-translate-y-1 hover:bg-[#159AC8]">
                            Mulai Konsultasi
                        </a>

                        <a href="{{ route('psikolog.register') }}"
                           class="rounded-full bg-white/10 px-5 py-3 text-sm font-bold text-white ring-1 ring-white/15 transition hover:-translate-y-1 hover:bg-white/15">
                            Jadi Mitra
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="text-base font-bold">Navigasi</h4>
                    <div class="mt-5 grid gap-3 text-sm text-white/70">
                        <a href="#home" class="transition hover:text-white">Home</a>
                        <a href="#layanan" class="transition hover:text-white">Services</a>
                        <a href="#fitur" class="transition hover:text-white">Features</a>
                        <a href="#psikolog" class="transition hover:text-white">Psikolog</a>
                        <a href="#testimoni" class="transition hover:text-white">Testimoni</a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="text-base font-bold">Layanan</h4>
                    <div class="mt-5 grid gap-3 text-sm text-white/70">
                        <a href="#layanan" class="transition hover:text-white">Konsultasi</a>
                        <a href="#fitur" class="transition hover:text-white">Hasil Konsultasi</a>
                        <a href="#fitur" class="transition hover:text-white">Rujukan</a>
                        <a href="{{ route('artikel.index') }}" class="transition hover:text-white">Artikel</a>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <h4 class="text-base font-bold">Akses Cepat</h4>

                    <div class="mt-5 grid gap-3 text-sm text-white/70">
                        <a href="{{ route('login') }}" class="transition hover:text-white">Login Pasien</a>
                        <a href="{{ route('register') }}" class="transition hover:text-white">Daftar Pasien</a>
                        <a href="{{ route('psikolog.register') }}" class="transition hover:text-white">Daftar Mitra Psikolog</a>
                        <a href="{{ route('backend.login') }}" class="transition hover:text-white">Login Admin</a>
                    </div>

                    <div class="mt-6 rounded-3xl bg-white/8 p-5 ring-1 ring-white/10">
                        <p class="text-sm font-semibold text-white">MindHaven Support</p>
                        <p class="mt-2 text-xs leading-6 text-white/65">
                            Sistem dibuat untuk mendukung konsultasi pasien, psikolog, pembayaran,
                            hasil konsultasi, dan rujukan psikiater.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-12 border-t border-white/10 py-6">
                <div class="flex flex-col justify-between gap-4 text-sm text-white/55 md:flex-row md:items-center">
                    <p>© {{ date('Y') }} MindHaven. All rights reserved.</p>

                    <div class="flex flex-wrap gap-5">
                        <a href="#home" class="transition hover:text-white">Privacy</a>
                        <a href="#home" class="transition hover:text-white">Terms</a>
                        <a href="#mitra" class="transition hover:text-white">Partnership</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<a href="{{ route('chatbot.index') }}"
   class="fixed bottom-6 right-6 z-[9998] flex items-center gap-3 rounded-full bg-gradient-to-r from-[#28AEDA] to-[#01588E] px-5 py-4 text-sm font-semibold text-white shadow-[0_20px_45px_rgba(1,88,142,.25)] transition hover:-translate-y-1">
    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
        </svg>
    </span>
    MindHaven Assistant
</a>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const progressBar = document.getElementById('mhScrollProgress');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });

            mobileMenu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    mobileMenu.classList.add('hidden');
                });
            });
        }

        const revealElements = document.querySelectorAll('.mh-reveal, .mh-reveal-left, .mh-reveal-right');

        const revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('mh-show');
                }
            });
        }, { threshold: 0.12 });

        revealElements.forEach(function (element, index) {
            element.style.transitionDelay = Math.min(index * 45, 220) + 'ms';
            revealObserver.observe(element);
        });

        function updateScrollEffects() {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

            if (progressBar) {
                progressBar.style.width = scrollPercent + '%';
            }
        }

        updateScrollEffects();
        window.addEventListener('scroll', updateScrollEffects, { passive: true });
    });
</script>

@endsection