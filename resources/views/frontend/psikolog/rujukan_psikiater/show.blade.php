@extends('frontend.layouts.psikolog')

@section('title', 'Detail Surat Rujukan')
@section('page-title', 'Surat Rujukan')

@section('content')

@php
    $pasien = $rujukanPsikiater->pasien;
    $userPasien = $pasien->user ?? null;

    $psikolog = $rujukanPsikiater->psikolog;
    $userPsikolog = $psikolog->user ?? null;

    $konsultasi = $rujukanPsikiater->konsultasi;
    $detailKonsultasi = $konsultasi->detailKonsultasi ?? null;

    $psikiater = $rujukanPsikiater->psikiater;
    $rumahSakit = optional($psikiater)->rumahSakit;

    $namaRumahSakit = $rumahSakit->nama_rumahsakit ?? '-';
    $alamatRumahSakit = $rumahSakit->alamat ?? '-';
    $teleponRumahSakit = $rumahSakit->no_telepon ?? '-';

    $namaPasien = $pasien->nama_lengkap ?? $userPasien->name ?? '-';
    $emailPasien = $userPasien->email ?? '-';
    $namaPsikolog = $psikolog->nama_lengkap ?? $userPsikolog->name ?? '-';

    $nomorRujukan = $rujukanPsikiater->nomor_rujukan ?? 'Belum tersedia';

    $diagnosaAwal = $rujukanPsikiater->diagnosa_awal
        ?? $detailKonsultasi->diagnosis_awal
        ?? '-';

    $catatanTambahan = $rujukanPsikiater->catatan_rujukan
        ?? $rujukanPsikiater->catatan_psikolog
        ?? $detailKonsultasi->laporan_asesmen_psikologis
        ?? '-';

    $umurPasien = '-';

    if (!empty($pasien->tanggal_lahir)) {
        try {
            $umurPasien = \Carbon\Carbon::parse($pasien->tanggal_lahir)->age . ' tahun';
        } catch (\Exception $e) {
            $umurPasien = '-';
        }
    }
@endphp

<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-2xl border border-green-100 bg-green-50 px-5 py-4 text-sm font-medium text-green-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.06)] md:p-7">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-[#01588E]">
                    Detail Surat Rujukan Psikiater
                </p>

                <h1 class="mt-2 text-2xl font-semibold tracking-tight text-[#061A33] md:text-3xl">
                    {{ $namaPasien }}
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Nomor rujukan: {{ $nomorRujukan }}
                </p>
            </div>

            <a href="{{ route('psikolog.rujukan-psikiater.index') }}"
               class="inline-flex w-fit items-center gap-3 rounded-2xl bg-[#061A33] px-5 py-3 text-sm font-medium text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#01588E]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="rounded-[30px] border border-white bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.06)] md:p-7">

        <div class="mb-7 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-[#061A33]">
                    Informasi Rujukan
                </h2>

                <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-400">
                    Detail surat rujukan pasien yang dibuat dari hasil konsultasi.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-[#F8FAFC] px-5 py-4">
                <p class="text-xs font-medium text-slate-400">Psikolog</p>
                <p class="mt-1 text-sm font-medium text-[#061A33]">
                    {{ $namaPsikolog }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            <div class="rounded-[28px] bg-[#FBFCFE] p-6 ring-1 ring-slate-100">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EEF4FF] text-[#155EEF]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-[#061A33]">Data Pasien</h3>
                        <p class="mt-1 text-sm font-medium text-slate-400">Informasi pasien.</p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">Nama Pasien</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $namaPasien }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">Email</p>
                        <p class="mt-2 break-all text-sm font-medium text-slate-700">{{ $emailPasien }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">No. Telepon</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $pasien->no_telepon ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">Umur</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $umurPasien }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] bg-[#FBFCFE] p-6 ring-1 ring-slate-100">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#E9FBEF] text-[#12B76A]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-[#061A33]">Psikiater Tujuan</h3>
                        <p class="mt-1 text-sm font-medium text-slate-400">Tujuan rujukan pasien.</p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">Nama Psikiater</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $psikiater->nama_lengkap ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">Spesialisasi</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $psikiater->spesialisasi ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100 sm:col-span-2">
                        <p class="text-xs font-medium text-slate-400">Rumah Sakit</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $namaRumahSakit }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                        <p class="text-xs font-medium text-slate-400">No. Telepon RS</p>
                        <p class="mt-2 text-sm font-medium text-slate-700">{{ $teleponRumahSakit }}</p>
                    </div>

                    <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100 sm:col-span-2">
                        <p class="text-xs font-medium text-slate-400">Alamat Rumah Sakit</p>
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-700">{{ $alamatRumahSakit }}</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-6 rounded-[28px] bg-[#FBFCFE] p-6 ring-1 ring-slate-100">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FFF4E5] text-[#F59E0B]">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-[#061A33]">Isi Rujukan</h3>
                    <p class="mt-1 text-sm font-medium text-slate-400">Diagnosis dan alasan rujukan pasien.</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4">
                <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                    <p class="text-xs font-medium text-slate-400">Diagnosis Awal</p>
                    <p class="mt-2 text-sm font-medium leading-7 text-slate-700">{{ $diagnosaAwal }}</p>
                </div>

                <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                    <p class="text-xs font-medium text-slate-400">Alasan Rujukan</p>
                    <p class="mt-2 text-sm font-medium leading-7 text-slate-700">{{ $rujukanPsikiater->alasan_rujukan ?? '-' }}</p>
                </div>

                <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
                    <p class="text-xs font-medium text-slate-400">Catatan Tambahan</p>
                    <p class="mt-2 whitespace-pre-line text-sm font-medium leading-7 text-slate-700">{{ $catatanTambahan }}</p>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection