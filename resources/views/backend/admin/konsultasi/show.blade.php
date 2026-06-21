@extends('backend.layouts.app')

@section('title', 'Detail Konsultasi')

@section('content')

@php
    use Carbon\Carbon;

    $pasien = $konsultasi->pasien;
    $psikolog = $konsultasi->psikolog;
    $detail = $konsultasi->detailKonsultasi;
    $pembayaran = $konsultasi->pembayaran;
    $rujukan = $konsultasi->rujukanPsikiater;

    $namaPasien = $pasien->nama_lengkap ?? $pasien->user->name ?? '-';
    $emailPasien = $pasien->user->email ?? '-';

    $namaPsikolog = $psikolog->nama_lengkap ?? $psikolog->user->name ?? '-';
    $emailPsikolog = $psikolog->email ?? $psikolog->user->email ?? '-';

    $namaRumahSakit = $rujukan->rumahSakit->nama_rumahsakit
        ?? $rujukan->psikiater->rumahSakit->nama_rumahsakit
        ?? '-';

    $namaPsikiater = $rujukan->psikiater->nama_lengkap ?? '-';

    $tanggal = $konsultasi->tanggal_konsultasi
        ? Carbon::parse($konsultasi->tanggal_konsultasi)->translatedFormat('d F Y')
        : '-';

    $jam = $konsultasi->jam_konsultasi
        ? Carbon::parse($konsultasi->jam_konsultasi)->format('H:i')
        : '-';

    $statusClass = match ($konsultasi->status) {
        'selesai' => 'bg-emerald-100 text-emerald-700',
        'diproses' => 'bg-sky-100 text-sky-700',
        'pending' => 'bg-amber-100 text-amber-700',
        'dibatalkan' => 'bg-rose-100 text-rose-700',
        default => 'bg-slate-100 text-slate-700',
    };

    $metodeText = match ($konsultasi->metode_konsultasi) {
        'chat' => 'Chat',
        'video_call' => 'Video Call',
        'temu_janji' => 'Temu Janji',
        default => '-',
    };
@endphp

<div class="space-y-6">

    <section class="rounded-[2rem] bg-white p-7 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-4xl font-semibold text-[#01588E]">
                    Detail Konsultasi
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Informasi lengkap konsultasi pasien dan hasil dari psikolog.
                </p>
            </div>

            <span class="w-fit rounded-full px-5 py-3 text-sm font-semibold capitalize {{ $statusClass }}">
                {{ $konsultasi->status ?? '-' }}
            </span>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
            <h2 class="text-xl font-semibold text-slate-900">Data Pasien</h2>

            <div class="mt-5 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Nama Pasien</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $namaPasien }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Email</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $emailPasien }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">No Telepon</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $pasien->no_telepon ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
            <h2 class="text-xl font-semibold text-slate-900">Data Psikolog</h2>

            <div class="mt-5 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Nama Psikolog</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $namaPsikolog }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Email</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $emailPsikolog }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Spesialisasi</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $psikolog->spesialisasi ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
            <h2 class="text-xl font-semibold text-slate-900">Jadwal Konsultasi</h2>

            <div class="mt-5 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Tanggal</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $tanggal }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Jam</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $jam }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Metode</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $metodeText }}</p>
                </div>
            </div>
        </div>

    </section>

    <section class="rounded-[2rem] bg-white p-7 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
        <h2 class="text-2xl font-semibold text-slate-900">
            Hasil Konsultasi
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Ringkasan hasil atau arahan dari psikolog.
        </p>

        <div class="mt-7 space-y-5">

            <div class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-100">
                <p class="text-sm font-semibold text-slate-500">Catatan / Keluhan Utama</p>
                <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-800">
                    {{ $detail->keluhan_utama ?? $konsultasi->keluhan ?? '-' }}
                </p>
            </div>

            <div class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-100">
                <p class="text-sm font-semibold text-slate-500">Observasi</p>
                <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-800">
                    {{ $detail->hasil_observasi ?? '-' }}
                </p>
            </div>

            <div class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-100">
                <p class="text-sm font-semibold text-slate-500">Diagnosa</p>
                <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-800">
                    {{ $detail->diagnosis_awal ?? '-' }}
                </p>
            </div>

            <div class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-100">
                <p class="text-sm font-semibold text-slate-500">Rencana Penanganan / Saran Terapi</p>
                <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-800">
                    {{ $detail->rencana_penanganan ?? '-' }}
                </p>
            </div>

            <div class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-100">
                <p class="text-sm font-semibold text-slate-500">Laporan Asesmen Psikologis / Tindak Lanjut</p>
                <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-800">
                    {{ $detail->laporan_asesmen_psikologis ?? '-' }}
                </p>
            </div>

            <div class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-100">
                <p class="text-sm font-semibold text-slate-500">Perlu Rujukan Psikiater</p>
                <p class="mt-3 text-sm font-semibold text-slate-800">
                    {{ $detail && $detail->perlu_rujukan ? 'Ya, perlu rujukan' : 'Tidak' }}
                </p>
            </div>

        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        <div class="rounded-[2rem] bg-white p-7 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
            <h2 class="text-2xl font-semibold text-slate-900">
                Data Pembayaran
            </h2>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Status Pembayaran</p>
                    <p class="mt-2 text-base font-semibold capitalize text-slate-900">
                        {{ $pembayaran->status_pembayaran ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Metode Pembayaran</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">
                        {{ $pembayaran->metode_pembayaran ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Total Pembayaran</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">
                        Rp {{ number_format($pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] bg-white p-7 shadow-[0_18px_55px_rgba(15,23,42,.06)]">
            <h2 class="text-2xl font-semibold text-slate-900">
                Data Rujukan Psikiater
            </h2>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Nomor Rujukan</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">
                        {{ $rujukan->nomor_rujukan ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Psikiater</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">
                        {{ $namaPsikiater }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-500">Rumah Sakit</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">
                        {{ $namaRumahSakit }}
                    </p>
                </div>
            </div>
        </div>

    </section>

    <div class="flex justify-end">
        <a href="{{ route('admin.konsultasi.index') }}"
           class="rounded-2xl bg-[#01588E] px-6 py-4 text-sm font-semibold text-white hover:bg-[#01446e]">
            Kembali
        </a>
    </div>

</div>

@endsection