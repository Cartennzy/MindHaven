@extends('frontend.layouts.app')

@section('title', 'Surat Rujukan - MindHaven')
@section('page_title', 'Surat Rujukan')
@section('page_subtitle', 'Daftar surat rujukan psikiater dari hasil konsultasi Anda.')

@section('content')

<div class="space-y-7 max-w-6xl mx-auto">

    {{-- HEADER BANNER - FULL GRADIENT PREMIUM SAAS AESTHETIC --}}
    <div class="relative overflow-hidden rounded-[34px] bg-gradient-to-r from-[#01588E] via-[#0372A6] to-[#49C5B6] p-6 shadow-[0_20px_50px_rgba(1,88,142,0.2)] md:p-8">
        <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 h-44 w-44 rounded-full bg-black/10 blur-2xl"></div>

        <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[24px] bg-white/15 border border-white/20 text-white shadow-inner">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7V3z"/>
                    </svg>
                </div>

                <div>
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/10 px-3.5 py-1 text-[11px] font-black uppercase tracking-[0.14em] text-white backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#A7E36F] animate-pulse"></span>
                        Rekomendasi Lanjutan
                    </span>
                    <h2 class="text-3xl font-black tracking-tight text-white">Surat Rujukan Psikiater</h2>
                    <p class="mt-1 text-sm font-semibold text-white/85">Surat rujukan diberikan apabila psikolog menyarankan pemeriksaan atau penanganan lanjutan ke psikiater.</p>
                </div>
            </div>

            <div class="rounded-[26px] border border-white/20 bg-white/10 px-6 py-4 text-center backdrop-blur-md shadow-inner min-w-[120px]">
                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-white/80">Total Rujukan</p>
                <p class="mt-0.5 text-4xl font-black text-white">{{ $rujukans->count() }}</p>
            </div>
        </div>
    </div>

    {{-- LIST RUJUKAN --}}
    @if($rujukans->count() > 0)
        <div class="space-y-6">
            @foreach($rujukans as $rujukan)
                @php
                    $idRujukan = $rujukan->id_rujukan ?? $rujukan->id ?? null;

                    $rumahSakit = $rujukan->rumahSakit
                        ?? $rujukan->psikiater?->rumahSakit
                        ?? null;

                    $namaRumahSakit = $rumahSakit->nama_rumahsakit
                        ?? $rumahSakit->nama
                        ?? '-';

                    $tanggalRujukan = !empty($rujukan->tanggal_rujukan)
                        ? \Carbon\Carbon::parse($rujukan->tanggal_rujukan)->translatedFormat('d F Y')
                        : '-';
                @php

                <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-gradient-to-br from-white via-[#F8FAFC] to-[#F1F5F9] p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:shadow-[0_22px_50px_rgba(1,88,142,0.07)] hover:-translate-y-0.5 hover:border-slate-200">
                    <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b from-[#01588E] to-[#49C5B6]"></div>

                    <div class="relative flex flex-col gap-5">
                        
                        {{-- Row Header Dalam Card --}}
                        <div class="flex items-center gap-3.5">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-[#01588E] text-xl border border-slate-100 shadow-sm transition-all duration-300 group-hover:scale-105">
                                <i class="fas fa-file-medical"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Nomor Rujukan Resmi</span>
                                <h3 class="text-xl font-black text-slate-900 leading-tight mt-0.5">{{ $rujukan->nomor_rujukan ?? '-' }}</h3>
                                <p class="text-xs font-semibold text-slate-400 mt-1"><i class="fas fa-calendar-day mr-1"></i> Diterbitkan pada: {{ $tanggalRujukan }}</p>
                            </div>
                        </div>

                        {{-- Metadata Rujukan Box --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white border border-slate-100/80 p-4 rounded-2xl shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm text-[#01588E]"><i class="fas fa-user-doctor"></i></div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Psikiater Spesialis Jiwa</span>
                                    <span class="text-sm font-black text-slate-800 mt-0.5 block truncate">{{ $rujukan->psikiater->nama_lengkap ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm text-[#49C5B6]"><i class="fas fa-hospital"></i></div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Rumah Sakit Rujukan</span>
                                    <span class="text-sm font-black text-slate-800 mt-0.5 block truncate">{{ $namaRumahSakit }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Button Baris Bawah Card --}}
                        <div class="border-t border-slate-200/50 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
                            <p class="text-xs font-semibold text-slate-400"><i class="fas fa-info-circle mr-1"></i> Bawa cetakan surat ini saat mengunjungi faskes penanganan psikiatri lanjutan.</p>
                            
                            @if($idRujukan)
                                <a href="{{ route('pasien.rujukan-psikiater.show', ['rujukan_psikiater' => $idRujukan]) }}"
                                   class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-5 text-xs font-black text-white shadow-sm hover:scale-[1.01] transition whitespace-nowrap">
                                    <i class="fas fa-envelope-open-text"></i> Tinjau & Cetak Surat
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- UPGRADE ELEMEN @EMPTY - MULTI-TONE GRADIENT DYNAMIC CARD SESUAI SCREENSHOT ACUAN --}}
        <div class="rounded-[34px] border border-[#BFE7F3] bg-gradient-to-br from-white via-[#F4FAFF] to-[#EFFDF9] p-10 text-center shadow-[0_20px_50px_rgba(1,88,142,0.05)] md:p-14">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[26px] bg-gradient-to-br from-[#01588E] to-[#49C5B6] text-white shadow-[0_12px_30px_rgba(1,88,142,0.25)] animate-pulse">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7V3z"/>
                </svg>
            </div>

            <h3 class="mt-7 text-2xl font-black text-slate-900">Belum Ada Surat Rujukan</h3>
            <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-7 text-slate-400">
                Surat rujukan klinis akan tersedia otomatis apabila Tenaga Ahli Psikolog menyarankan pemeriksaan penanganan farmakoterapi lanjutan ke Dokter Spesialis Jiwa (Psikiater).
            </p>

            <a href="{{ route('pasien.konsultasi.index') }}"
               class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-7 text-sm font-black text-white shadow-[0_12px_24px_rgba(1,88,142,0.15)] hover:-translate-y-0.5 transition duration-300">
                <i class="fas fa-comment-medical"></i> Periksa Sesi Konsultasi
            </a>
        </div>
    @endif

</div>

@endsection