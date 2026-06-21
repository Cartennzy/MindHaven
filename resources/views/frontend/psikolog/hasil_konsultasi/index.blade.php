@extends('frontend.layouts.psikolog')

@section('title', 'Hasil Konsultasi')
@section('page-title', 'Hasil Konsultasi')

@section('content')

@php
    $namaPasienHasil = function ($konsultasi) {
        return optional($konsultasi->pasien)->nama_lengkap
            ?? optional(optional($konsultasi->pasien)->user)->name
            ?? 'Pasien';
    };

    $emailPasienHasil = function ($konsultasi) {
        return optional(optional($konsultasi->pasien)->user)->email ?? '-';
    };

    $inisialPasienHasil = function ($konsultasi) use ($namaPasienHasil) {
        return strtoupper(substr($namaPasienHasil($konsultasi), 0, 1));
    };

    $totalHasil = $konsultasis->count();
@endphp

<div class="space-y-7">

    <div class="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#061A33] via-[#01588E] to-[#12B76A] p-7 shadow-[0_28px_90px_rgba(1,88,142,0.18)] md:p-8">
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-[#B7E3FF]/20 blur-3xl"></div>

        <div class="relative z-10">
            <p class="text-sm font-medium tracking-wide text-white/75">
                Rekam Hasil Konsultasi
            </p>

            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-white md:text-5xl">
                Hasil Konsultasi
            </h1>

            <p class="mt-4 max-w-3xl text-sm font-medium leading-7 text-white/75 md:text-base">
                Data konsultasi pasien yang sudah memiliki catatan, diagnosa, dan saran terapi dari psikolog.
            </p>
        </div>
    </div>

    <div class="rounded-[36px] border border-white bg-white p-6 shadow-[0_22px_75px_rgba(15,23,42,0.08)] md:p-7">

        <div class="mb-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-[#061A33]">
                    Riwayat Hasil Konsultasi
                </h2>

                <p class="mt-2 text-sm font-medium leading-6 text-slate-400">
                    Setiap hasil konsultasi ditampilkan dalam card agar lebih mudah dibaca.
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-2xl bg-[#EEF4FF] px-5 py-3 text-sm font-semibold text-[#155EEF]">
                <span class="h-2.5 w-2.5 rounded-full bg-[#155EEF]"></span>
                {{ $totalHasil }} Data
            </div>
        </div>

        @if($konsultasis->count() > 0)

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                @foreach($konsultasis as $konsultasi)

                    <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-[#FBFCFE] p-5 shadow-[0_14px_45px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-[0_24px_75px_rgba(15,23,42,0.10)]">

                        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-[#E8F6FF] blur-2xl transition group-hover:bg-[#DDF4FF]"></div>

                        <div class="relative z-10">

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex min-w-0 items-center gap-4">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#EEF4FF] text-lg font-semibold text-[#155EEF]">
                                        {{ $inisialPasienHasil($konsultasi) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-lg font-semibold text-[#061A33]">
                                            {{ $namaPasienHasil($konsultasi) }}
                                        </h3>

                                        <p class="mt-1 truncate text-xs font-medium text-slate-400">
                                            {{ $emailPasienHasil($konsultasi) }}
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 rounded-full bg-[#E9FBEF] px-4 py-2 text-xs font-semibold text-[#12B76A]">
                                    Selesai
                                </span>

                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Tanggal Konsultasi</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700">
                                        {{ $konsultasi->tanggal_konsultasi ?? optional($konsultasi->created_at)->format('d M Y') ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Jam Konsultasi</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700">
                                        {{ $konsultasi->jam_konsultasi ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100 sm:col-span-2">
                                    <p class="text-xs font-medium text-slate-400">Diagnosa</p>
                                    <p class="mt-1 line-clamp-3 text-sm font-medium leading-6 text-slate-700">
                                        {{ optional($konsultasi->detailKonsultasi)->diagnosis_awal ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100 sm:col-span-2">
                                    <p class="text-xs font-medium text-slate-400">Saran Terapi</p>
                                    <p class="mt-1 line-clamp-3 text-sm font-medium leading-6 text-slate-700">
                                        {{ optional($konsultasi->detailKonsultasi)->rencana_penanganan ?? '-' }}
                                    </p>
                                </div>

                            </div>

                            <div class="mt-5 flex justify-end">
                                <a href="{{ route('psikolog.konsultasi.show', ['konsultasi' => $konsultasi->id_konsultasi]) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#061A33] px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#01588E]">
                                    Detail
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
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <h3 class="mt-5 text-xl font-semibold text-[#061A33]">
                    Belum Ada Hasil Konsultasi
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm font-medium leading-6 text-slate-400">
                    Data akan muncul setelah psikolog mengisi hasil konsultasi.
                </p>
            </div>

        @endif

    </div>

</div>

@endsection