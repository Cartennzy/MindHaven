@extends('frontend.layouts.psikolog')

@section('title', 'Data Pasien')
@section('page-title', 'Data Pasien')

@section('content')

@php
    $namaPasien = function ($pasien) {
        return $pasien->nama_lengkap
            ?? optional($pasien->user)->name
            ?? 'Pasien';
    };

    $emailPasien = function ($pasien) {
        return optional($pasien->user)->email ?? '-';
    };

    $inisialPasien = function ($pasien) use ($namaPasien) {
        return strtoupper(substr($namaPasien($pasien), 0, 1));
    };

    $totalPasien = $pasiens->count();
    $totalKonsultasi = $pasiens->sum('total_konsultasi');
@endphp

<div class="space-y-7">

    <div class="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#061A33] via-[#01588E] to-[#12B76A] p-7 shadow-[0_28px_90px_rgba(1,88,142,0.18)] md:p-8">
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-[#B7E3FF]/20 blur-3xl"></div>

        <div class="relative z-10">
            <p class="text-sm font-medium tracking-wide text-white/75">
                MindHaven Patient Management
            </p>

            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-white md:text-5xl">
                Data Pasien
            </h1>

            <p class="mt-4 max-w-2xl text-sm font-medium leading-7 text-white/75 md:text-base">
                Data pasien yang pernah melakukan konsultasi bersama psikolog.
            </p>

            <div class="mt-7 grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="rounded-[28px] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between gap-5">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                                Total Pasien
                            </p>

                            <h3 class="mt-4 text-4xl font-semibold text-[#061A33]">
                                {{ $totalPasien }}
                            </h3>

                            <p class="mt-2 text-sm font-medium text-slate-400">
                                Pasien terdaftar
                            </p>
                        </div>

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EEF4FF] text-[#155EEF]">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-[28px] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between gap-5">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                                Konsultasi
                            </p>

                            <h3 class="mt-4 text-4xl font-semibold text-[#12B76A]">
                                {{ $totalKonsultasi }}
                            </h3>

                            <p class="mt-2 text-sm font-medium text-slate-400">
                                Total aktivitas konsultasi
                            </p>
                        </div>

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#E9FBEF] text-[#12B76A]">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4-4-4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="rounded-[36px] border border-white bg-white p-6 shadow-[0_22px_75px_rgba(15,23,42,0.08)] md:p-7">

        <div class="mb-6 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

            <div>
                <h2 class="text-2xl font-semibold text-[#061A33]">
                    Seluruh Data Pasien
                </h2>

                <p class="mt-2 text-sm font-medium leading-6 text-slate-400">
                    Setiap pasien ditampilkan dalam card agar data lebih mudah dibaca.
                </p>
            </div>

            <div class="relative w-full xl:w-[340px]">
                <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                </svg>

                <input type="text"
                       placeholder="Cari pasien..."
                       class="w-full rounded-2xl border border-slate-200 bg-[#F8FAFC] px-5 py-4 pl-12 text-sm font-medium text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10">
            </div>

        </div>

        @if($pasiens->count() > 0)

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                @foreach ($pasiens as $pasien)

                    <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-[#FBFCFE] p-5 shadow-[0_14px_45px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-[0_24px_75px_rgba(15,23,42,0.10)]">

                        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-[#E8F6FF] blur-2xl transition group-hover:bg-[#DDF4FF]"></div>

                        <div class="relative z-10">

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex min-w-0 items-center gap-4">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#EEF4FF] text-lg font-semibold text-[#155EEF]">
                                        {{ $inisialPasien($pasien) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-lg font-semibold text-[#061A33]">
                                            {{ $namaPasien($pasien) }}
                                        </h3>

                                        <p class="mt-1 text-xs font-medium text-slate-400">
                                            ID Pasien: {{ $pasien->id_pasien }}
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 rounded-full bg-[#E9FBEF] px-4 py-2 text-xs font-semibold text-[#12B76A]">
                                    Aktif
                                </span>

                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100 sm:col-span-2">
                                    <p class="text-xs font-medium text-slate-400">Email Pasien</p>
                                    <p class="mt-1 break-all text-sm font-medium text-slate-700">
                                        {{ $emailPasien($pasien) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Total Konsultasi</p>
                                    <p class="mt-1 text-sm font-semibold text-[#155EEF]">
                                        {{ $pasien->total_konsultasi ?? 0 }} Konsultasi
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Status Pasien</p>
                                    <p class="mt-1 text-sm font-semibold text-[#12B76A]">
                                        Aktif
                                    </p>
                                </div>

                            </div>

                            <div class="mt-5 flex justify-end">
                                <a href="{{ route('psikolog.pasien.show', ['pasien' => $pasien->id_pasien]) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#061A33] px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#01588E]">
                                    Detail Pasien
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>

                @endforeach

            </div>

        @else

            <div class="rounded-[30px] border border-dashed border-slate-200 bg-[#F8FAFC] px-6 py-20 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                    <svg class="h-8 w-8"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                </div>

                <h3 class="mt-5 text-xl font-semibold text-[#061A33]">
                    Belum Ada Pasien
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm font-medium leading-6 text-slate-400">
                    Data pasien akan muncul setelah pasien melakukan konsultasi.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection