@extends('frontend.layouts.psikolog')

@section('title', 'Surat Rujukan')
@section('page-title', 'Surat Rujukan')

@section('content')

@php
    $totalRujukan = $rujukans->count();

    $badgeStatusRujukan = function ($status) {
        return match ($status) {
            'aktif', 'dibuat', 'menunggu' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
            'diproses' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
            'selesai' => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
            'ditolak', 'dibatalkan' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
            default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
        };
    };

    $labelStatusRujukan = function ($status) {
        return match ($status) {
            'aktif' => 'Aktif',
            'dibuat' => 'Dibuat',
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default => '-',
        };
    };

    $namaPasienRujukan = function ($rujukan) {
        return $rujukan->pasien->nama_lengkap
            ?? $rujukan->pasien->user->name
            ?? '-';
    };

    $emailPasienRujukan = function ($rujukan) {
        return $rujukan->pasien->user->email ?? '-';
    };

    $namaRumahSakit = function ($rujukan) {
        return $rujukan->psikiater->rumahSakit->nama_rumahsakit ?? '-';
    };

    $statusRujukan = function ($rujukan) {
        return $rujukan->status_rujukan
            ?? $rujukan->status
            ?? '-';
    };
@endphp

<div class="space-y-6 max-w-7xl mx-auto">

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- TOP HERO BANNER CARD - SINKRON SAMA PERSIS IMAGE_35CAC4.PNG --}}
    <div class="relative overflow-hidden rounded-[2.2rem] bg-gradient-to-r from-[#061A33] via-[#01588E] to-[#12B76A] px-7 py-9 shadow-[0_24px_70px_rgba(15,23,42,0.12)] md:px-9 md:py-11">
        <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wider text-white/75 uppercase">
                    Sistem Rujukan Psikiater
                </p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-white lg:text-5xl leading-none">
                    Surat Rujukan
                </h1>
                <p class="mt-4 max-w-2xl text-sm font-medium leading-relaxed text-white/80">
                    Daftar surat rujukan yang sudah dibuat dari hasil konsultasi pasien.
                </p>
            </div>

            {{-- AKSI BANNER KANAN: TOMBOL CREATE YANG SEMPAT HILANG & JUMLAH DATA COUNTER --}}
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{-- Mengarahkan ke form pembuatan rujukan klinis baru --}}
                <a href="{{ route('psikolog.rujukan-psikiater.create') }}"
                   class="inline-flex h-12 items-center gap-2 rounded-2xl bg-[#41AD01] px-5 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#329000] whitespace-nowrap">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Surat Rujukan
                </a>

                <div class="inline-flex h-12 items-center gap-2.5 rounded-2xl bg-white/15 px-5 text-sm font-bold text-white backdrop-blur border border-white/10 shadow-inner whitespace-nowrap">
                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                    {{ $totalRujukan }} Data Rujukan
                </div>
            </div>
        </div>
    </div>

    {{-- WRAPPER DATA LIST CARD --}}
    <div class="rounded-[2.5rem] border border-blue-100/50 bg-gradient-to-b from-[#F5F9FD] to-[#EDF4FA] p-6 shadow-[0_20px_60px_rgba(1,88,142,0.03)] md:p-8">

        <div class="mb-8 max-w-2xl">
            <h2 class="text-xl font-bold text-[#061A33] tracking-tight">
                Data Surat Rujukan
            </h2>
            <p class="mt-1 text-xs font-semibold text-slate-400 leading-relaxed">
                Surat rujukan dibuat melalui halaman detail konsultasi yang sudah selesai dan ditandai perlu rujukan.
            </p>
        </div>

        @if($rujukans->count() > 0)

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                @foreach($rujukans as $rujukan)

                    @php
                        $namaPasien = $namaPasienRujukan($rujukan);
                        $status = $statusRujukan($rujukan);
                    @endphp

                    {{-- CARD ITEM CONTAINER --}}
                    <div class="group relative overflow-hidden rounded-[2rem] border border-blue-200/40 bg-white/95 p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:bg-white hover:border-[#01588E]/30 hover:shadow-[0_20px_50px_rgba(1,88,142,0.06)]">
                        <div class="absolute -right-16 -top-16 h-36 w-36 rounded-full bg-[#E8F6FF] opacity-60 blur-2xl transition group-hover:bg-[#DDF4FF]"></div>

                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex min-w-0 items-center gap-3.5">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#061A33] to-[#01588E] text-sm font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($namaPasien, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="truncate text-base font-bold text-[#061A33]">
                                                {{ $namaPasien }}
                                            </h3>
                                            <p class="mt-0.5 truncate text-[11px] font-semibold text-slate-400">
                                                {{ $emailPasienRujukan($rujukan) }}
                                            </p>
                                        </div>
                                    </div>

                                    <span class="shrink-0 rounded-lg h-7 px-3 flex items-center justify-center text-[10px] font-bold uppercase tracking-wider {{ $badgeStatusRujukan($status) }}">
                                        {{ $labelStatusRujukan($status) }}
                                    </span>
                                </div>

                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div class="rounded-xl bg-[#F8FAFC] border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Psikiater Tujuan</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 truncate">
                                            {{ $rujukan->psikiater->nama_lengkap ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F8FAFC] border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Rumah Sakit</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 truncate">
                                            {{ $namaRumahSakit($rujukan) }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F8FAFC] border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Tanggal Rujukan</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 truncate">
                                            {{ $rujukan->tanggal_rujukan ? \Carbon\Carbon::parse($rujukan->tanggal_rujukan)->translatedFormat('d F Y') : '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F8FAFC] border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Nomor Rujukan</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 break-all truncate">
                                            {{ $rujukan->nomor_rujukan ?? 'Belum tersedia' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-3.5 border-t border-slate-100 flex justify-end">
                                <a href="{{ route('psikolog.rujukan-psikiater.show', $rujukan->id_rujukan) }}"
                                   class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#061A33] px-4 text-xs font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#01588E]">
                                    Detail Rujukan
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>

                @endforeach

            </div>

        @else

            {{-- EMPTY ARCHIVE VIEW --}}
            <div class="rounded-[2rem] border border-dashed border-blue-200 bg-white/60 px-6 py-16 text-center shadow-inner">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm border border-slate-100 mb-4">
                    <svg class="h-6 w-6 text-[#01588E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-[#061A33]">Belum Anda Surat Rujukan</h3>
                <p class="mx-auto mt-1 max-w-sm text-xs font-semibold leading-relaxed text-slate-400">
                    Surat rujukan akan muncul setelah psikolog membuat rujukan dari detail konsultasi pasien.
                </p>
            </div>

        @endif

    </div>

</div>

@endsection