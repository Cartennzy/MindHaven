@extends('frontend.layouts.app')

@section('title', 'Dashboard Pasien - MindHaven')
@section('page_title', 'Dashboard Pasien')
@section('page_subtitle', 'Kelola layanan konsultasi, pembayaran, hasil konsultasi, dan rujukan Anda.')

@section('content')
<div class="space-y-6">

    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-[2rem] bg-[#01588E] p-6 text-white shadow-[0_24px_70px_rgba(1,88,142,0.20)]">
        <div class="absolute inset-0 bg-gradient-to-br from-[#01588E] via-[#0574A7] to-[#073C63]"></div>
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#41AD01]/25 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-[#41AD01]/10 blur-3xl"></div>

        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;">
        </div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/15 px-4 py-2 text-xs font-semibold text-white backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-[#41AD01] shadow-[0_0_14px_rgba(65,173,1,0.9)]"></span>
                    MindHaven Care
                </div>

                <h1 class="mt-5 text-4xl font-black tracking-tight text-white md:text-5xl">
                    Halo, {{ auth()->user()->name ?? 'Pasien' }}
                </h1>

                <p class="mt-4 max-w-3xl text-base leading-7 text-blue-100">
                    Pantau konsultasi, pembayaran, hasil konsultasi, dan surat rujukan dalam satu dashboard yang rapi dan mudah digunakan.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ url('/pasien/konsultasi/create') }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-bold text-[#01588E] shadow-[0_16px_35px_rgba(255,255,255,0.18)] transition duration-300 hover:-translate-y-0.5 hover:bg-[#41AD01] hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Buat Konsultasi
                </a>

                <a href="{{ url('/pasien/konsultasi') }}"
                   class="inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:bg-white/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                    </svg>
                    Riwayat Konsultasi
                </a>
            </div>
        </div>
    </section>

    {{-- STATISTIC CARDS --}}
    <section class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

        <a href="{{ url('/pasien/konsultasi') }}"
           class="group rounded-[1.6rem] border border-slate-100 bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:border-[#01588E]/20 hover:shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Konsultasi</p>
                    <h2 class="mt-3 text-4xl font-black text-[#061A33]">
                        {{ $totalKonsultasi ?? 0 }}
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E8F1FF] text-[#01588E] transition duration-300 group-hover:bg-[#01588E] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                    </svg>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                <p class="text-xs font-medium text-slate-400">Lihat riwayat konsultasi</p>
                <span class="text-sm font-bold text-[#01588E]">Buka</span>
            </div>
        </a>

        <a href="{{ url('/pasien/pembayaran') }}"
           class="group rounded-[1.6rem] border border-slate-100 bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:border-[#41AD01]/20 hover:shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Pembayaran</p>
                    <h2 class="mt-3 text-4xl font-black text-[#061A33]">
                        {{ $totalPembayaran ?? 0 }}
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E9FBEF] text-[#41AD01] transition duration-300 group-hover:bg-[#41AD01] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3V7Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h4"/>
                    </svg>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                <p class="text-xs font-medium text-slate-400">Lihat riwayat pembayaran</p>
                <span class="text-sm font-bold text-[#41AD01]">Buka</span>
            </div>
        </a>

        <a href="{{ url('/pasien/hasil-konsultasi') }}"
           class="group rounded-[1.6rem] border border-slate-100 bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Hasil Konsultasi</p>
                    <h2 class="mt-3 text-4xl font-black text-[#061A33]">
                        {{ $totalHasilKonsultasi ?? $totalKonsultasiSelesai ?? $totalKonsultasi ?? 0 }}
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-800 transition duration-300 group-hover:bg-slate-900 group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1Z"/>
                    </svg>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                <p class="text-xs font-medium text-slate-400">Lihat hasil konsultasi</p>
                <span class="text-sm font-bold text-slate-900">Buka</span>
            </div>
        </a>

        <a href="{{ url('/pasien/rujukan-psikiater') }}"
           class="group rounded-[1.6rem] border border-slate-100 bg-white p-5 shadow-[0_14px_40px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:border-violet-200 hover:shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Rujukan</p>
                    <h2 class="mt-3 text-4xl font-black text-[#061A33]">
                        {{ $totalRujukan ?? 0 }}
                    </h2>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-700 transition duration-300 group-hover:bg-violet-600 group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 10h6M12 7v6"/>
                    </svg>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                <p class="text-xs font-medium text-slate-400">Lihat surat rujukan</p>
                <span class="text-sm font-bold text-violet-700">Buka</span>
            </div>
        </a>

    </section>

    {{-- ACTION PANEL --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-12">

        <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.05)] xl:col-span-8">
            <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-black text-[#061A33]">
                        Akses Cepat
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pilih layanan yang ingin digunakan.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <a href="{{ url('/pasien/konsultasi/create') }}"
                   class="group flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 transition duration-300 hover:border-[#01588E]/20 hover:bg-white hover:shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#01588E] text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Buat Konsultasi</h3>
                            <p class="mt-1 text-xs text-slate-500">Mulai konsultasi baru</p>
                        </div>
                    </div>

                    <svg class="h-5 w-5 text-slate-300 transition group-hover:text-[#01588E]" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ url('/pasien/konsultasi') }}"
                   class="group flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 transition duration-300 hover:border-[#41AD01]/20 hover:bg-white hover:shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#41AD01] text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Riwayat Konsultasi</h3>
                            <p class="mt-1 text-xs text-slate-500">Pantau jadwal dan status</p>
                        </div>
                    </div>

                    <svg class="h-5 w-5 text-slate-300 transition group-hover:text-[#41AD01]" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ url('/pasien/pembayaran') }}"
                   class="group flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 transition duration-300 hover:border-slate-300 hover:bg-white hover:shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3V7Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h4"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Pembayaran</h3>
                            <p class="mt-1 text-xs text-slate-500">Cek tagihan dan transaksi</p>
                        </div>
                    </div>

                    <svg class="h-5 w-5 text-slate-300 transition group-hover:text-slate-900" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <a href="{{ url('/pasien/profile') }}"
                   class="group flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 transition duration-300 hover:border-[#01588E]/20 hover:bg-white hover:shadow-[0_12px_30px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#E8F1FF] text-[#01588E]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 21a8 8 0 0116 0"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Profil Pasien</h3>
                            <p class="mt-1 text-xs text-slate-500">Kelola data akun</p>
                        </div>
                    </div>

                    <svg class="h-5 w-5 text-slate-300 transition group-hover:text-[#01588E]" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.05)] xl:col-span-4">
            <h2 class="text-xl font-black text-[#061A33]">
                Status Akun
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Akun pasien aktif dan siap digunakan.
            </p>

            <div class="mt-6 rounded-[1.5rem] bg-gradient-to-br from-[#E8F1FF] to-[#E9FBEF] p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Kesiapan Layanan</p>
                        <h3 class="mt-2 text-3xl font-black text-[#061A33]">100%</h3>
                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#41AD01] text-white">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 h-2 overflow-hidden rounded-full bg-white">
                    <div class="h-full w-full rounded-full bg-[#41AD01]"></div>
                </div>
            </div>
        </div>

    </section>

</div>
@endsection