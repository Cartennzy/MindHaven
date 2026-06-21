@extends('frontend.layouts.psikolog')

@section('title', 'Detail Rekam Medis Pasien - MindHaven')
@section('page_title', 'Detail Pasien')
@section('page_subtitle', 'Tinjau rekam medis dan histori interaksi klinis pasien Anda.')

@section('content')

@php
    use Carbon\Carbon;

    $namaPasien = $pasien->nama_lengkap ?? $pasien->user->name ?? 'Pasien';
    $inisialPasien = strtoupper(substr($namaPasien, 0, 1));

    // Hitung umur otomatis dari database
    $umurPasien = $pasien->tanggal_lahir 
        ? Carbon::parse($pasien->tanggal_lahir)->age . ' Tahun' 
        : '-';

    $genderText = match($pasien->jenis_kelamin) {
        'laki-laki' => 'Laki-Laki',
        'perempuan' => 'Perempuan',
        default => ucfirst($pasien->jenis_kelamin ?? '-'),
    };
@endphp

<div class="mx-auto w-full space-y-6 pb-12">

    {{-- TOP PROFILE HEADER CARD WITH KEMBALI BUTTON --}}
    <div class="relative overflow-hidden rounded-[30px] border border-white/60 bg-white/80 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.02)] backdrop-blur-md md:p-8">
        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-gradient-to-br from-blue-50 to-indigo-50/50 opacity-70 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-5">
                {{-- Avatar Premium Match dengan Aksen Panel --}}
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#01588E] to-[#0488B8] text-2xl font-bold text-white shadow-md shadow-blue-900/10">
                    {{ $inisialPasien }}
                </div>

                <div class="min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        ID Pasien Resmi: #{{ $pasien->id_pasien }}
                    </span>
                    <h1 class="mt-0.5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $namaPasien }}
                    </h1>
                    <p class="mt-1 text-sm font-medium text-slate-500 flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-[#01588E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $pasien->user->email ?? $pasien->email ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- AKSI HEADER: BUTTON KEMBALI DAN BADGE STATUS SEJAJAR --}}
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('psikolog.pasien.index') }}"
                   class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7 m0 0l7-7 m-7 7h18"/>
                    </svg>
                    Kembali
                </a>

                <span class="inline-flex h-9 items-center rounded-xl bg-emerald-50 border border-emerald-100 px-3.5 text-xs font-bold text-emerald-700 uppercase tracking-wide">
                    <span class="mr-2 h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Pasien Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- LAYOUT SPLIT GRIDS --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        {{-- BAGIAN KIRI: HISTORI SISI KONSULTASI (FLUID TALL) --}}
        <div class="lg:col-span-8 rounded-[30px] border border-white/60 bg-white/80 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.02)] backdrop-blur-md md:p-8 flex flex-col">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <h2 class="text-xl font-bold text-[#061A33] tracking-tight">Riwayat Konsultasi Sesi</h2>
                <p class="mt-1 text-xs font-semibold text-slate-400">Daftar rekam log tatap muka dan interaksi klinis terdahulu bersama Anda.</p>
            </div>

            <div class="space-y-4 flex-1">
                @forelse($pasien->konsultasis as $konsultasi)
                    @php
                        $badgeColor = match($konsultasi->status) {
                            'selesai' => 'bg-emerald-50 border-emerald-100 text-emerald-700',
                            'diproses' => 'bg-blue-50 border-blue-100 text-blue-700',
                            'pending' => 'bg-amber-50 border-amber-100 text-amber-700',
                            default => 'bg-slate-50 border-slate-100 text-slate-600',
                        };
                    @endphp
                    
                    <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition duration-300 hover:border-slate-200 hover:shadow-[0_12px_30px_rgba(1,88,142,0.04)]">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="min-w-0">
                                <h4 class="text-base font-bold text-slate-800 truncate group-hover:text-[#01588E] transition-colors">
                                    Konsultasi Masalah: {{ $konsultasi->topik_konsultasi ?? 'Sesi Klinis' }}
                                </h4>
                                
                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs font-semibold text-slate-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                        </svg>
                                        {{ $konsultasi->tanggal_konsultasi ? \Carbon\Carbon::parse($konsultasi->tanggal_konsultasi)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                    
                                    @if($konsultasi->jam_konsultasi)
                                        <span class="hidden sm:inline text-slate-200">|</span>
                                        <span class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($konsultasi->jam_konsultasi)->format('H:i') }} WIB
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="shrink-0 self-start sm:self-center">
                                <span class="inline-flex h-7 items-center rounded-lg border px-3 text-[10px] font-bold uppercase tracking-wider {{ $badgeColor }}">
                                    {{ $konsultasi->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm mb-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-1.414.293l-2.414 2.414a1 1 0 01-1.414 0L11.414 13.293a1 1 0 00-1.414-.293H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Belum ada riwayat sesi</p>
                        <p class="text-xs font-semibold text-slate-400 max-w-[280px] mx-auto mt-1">Data interaksi tatap muka medis pasien ini belum terekam di sistem.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- BAGIAN KANAN: DATA PARAMETER DEMOGRAFIS --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="rounded-[30px] border border-white/60 bg-white/80 p-6 shadow-[0_20px_50px_rgba(15,23,42,0.02)] backdrop-blur-md md:p-8">
                <div class="mb-5 border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-bold text-[#061A33] tracking-tight">Informasi Demografis</h2>
                    <p class="text-xs font-semibold text-slate-400 mt-1">Data parameter fisik dan kontak seluler pasien.</p>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Kalkulasi Umur Pasien</span>
                        <h4 class="font-bold text-[#061A33] text-base mt-1">
                            {{ $umurPasien }}
                        </h4>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Identitas Gender</span>
                        <h4 class="font-bold text-[#061A33] text-base mt-1">
                            {{ $genderText }}
                        </h4>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Nomor Kontak Seluler</span>
                        <h4 class="font-bold text-[#061A33] text-base mt-1 tracking-wide">
                            {{ $pasien->no_telepon ?? '-' }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection