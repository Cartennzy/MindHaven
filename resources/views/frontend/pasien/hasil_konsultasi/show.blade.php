@extends('frontend.layouts.app')

@section('title', 'Detail Hasil Konsultasi - MindHaven')
@section('page_title', 'Detail Hasil Konsultasi')
@section('page_subtitle', 'Informasi lengkap hasil konsultasi psikolog.')

@section('content')

@php
    $detail = $konsultasi->detailKonsultasi;

    $namaPsikolog = $konsultasi->psikolog->nama_lengkap
        ?? $konsultasi->psikolog->user->name
        ?? '-';

    $tanggalHasil = $konsultasi->updated_at
        ? $konsultasi->updated_at->format('d F Y H:i')
        : '-';

    $tanggalKonsultasi = $konsultasi->tanggal_konsultasi ?? '-';
    $jamKonsultasi = $konsultasi->jam_konsultasi ?? '-';

    $metodeRaw = $konsultasi->metode_komunikasi ?? $konsultasi->metode_konsultasi ?? null;

    $metodeText = match($metodeRaw) {
        'online' => 'Online',
        'offline' => 'Offline',
        'chat' => 'Chat',
        'telepon' => 'Telepon',
        'video' => 'Video Call',
        'tatap_muka' => 'Tatap Muka',
        default => $metodeRaw ? ucfirst(str_replace('_', ' ', $metodeRaw)) : '-',
    };

    $topikKonsultasi = $konsultasi->topik_konseling
        ?? $konsultasi->topik_konsultasi
        ?? '-';

    $catatan = $detail->catatan
        ?? $detail->keluhan_utama
        ?? '-';

    $observasi = $detail->observasi
        ?? $detail->hasil_observasi
        ?? '-';

    $diagnosa = $detail->diagnosa
        ?? $detail->diagnosis_awal
        ?? '-';

    $saranTerapi = $detail->saran_terapi
        ?? $detail->rencana_penanganan
        ?? '-';

    $tindakLanjut = $detail->tindak_lanjut
        ?? $detail->laporan_asesmen_psikologis
        ?? '-';

    $statusKonsultasiText = match($konsultasi->status) {
        'selesai' => 'Selesai',
        'diproses' => 'Diproses',
        'pending' => 'Menunggu',
        'dibatalkan' => 'Dibatalkan',
        default => ucfirst($konsultasi->status ?? '-'),
    };

    $statusKonsultasiClass = match($konsultasi->status) {
        'selesai' => 'bg-green-50 text-green-700 border-green-200',
        'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
        'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
        default => 'bg-slate-50 text-slate-700 border-slate-200',
    };
@endphp

<div class="space-y-6">

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-[#01588E]">
                    Detail Hasil Konsultasi
                </p>

                <h2 class="mt-3 text-3xl font-black leading-tight text-slate-900 md:text-4xl">
                    Hasil Konsultasi Pasien
                </h2>

                <p class="mt-3 max-w-3xl text-sm font-medium leading-7 text-slate-500">
                    Ringkasan hasil konsultasi yang ditulis oleh psikolog berdasarkan sesi konsultasi pasien.
                </p>
            </div>

            <div class="rounded-2xl border {{ $statusKonsultasiClass }} px-5 py-3 text-sm font-black">
                {{ $statusKonsultasiText }}
            </div>
        </div>

        <div class="mt-7 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Psikolog</p>
                <p class="mt-2 text-base font-black text-slate-900">
                    {{ $namaPsikolog }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Tanggal Hasil</p>
                <p class="mt-2 text-base font-black text-slate-900">
                    {{ $tanggalHasil }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Topik Konseling</p>
                <p class="mt-2 text-base font-black text-slate-900">
                    {{ $topikKonsultasi }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

        <div class="space-y-5 xl:col-span-8">

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <p class="text-sm font-black text-[#01588E]">Keluhan Utama</p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Masalah yang Disampaikan Pasien
                </h3>

                <div class="mt-5 rounded-2xl bg-slate-50 p-5">
                    <p class="text-base font-medium leading-8 text-slate-700">
                        {{ $konsultasi->keluhan ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <p class="text-sm font-black text-[#01588E]">Catatan Konsultasi</p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Ringkasan Sesi
                </h3>

                <p class="mt-5 text-base font-medium leading-8 text-slate-700">
                    {{ $catatan }}
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <p class="text-sm font-black text-[#01588E]">Hasil Observasi</p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Pengamatan Psikolog
                </h3>

                <p class="mt-5 text-base font-medium leading-8 text-slate-700">
                    {{ $observasi }}
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <p class="text-sm font-black text-[#01588E]">Diagnosa Psikolog</p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Kesimpulan Profesional
                </h3>

                <p class="mt-5 text-base font-medium leading-8 text-slate-700">
                    {{ $diagnosa }}
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <p class="text-sm font-black text-[#01588E]">Saran & Terapi</p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Rekomendasi Penanganan
                </h3>

                <p class="mt-5 text-base font-medium leading-8 text-slate-700">
                    {{ $saranTerapi }}
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <p class="text-sm font-black text-[#01588E]">Tindak Lanjut</p>

                <h3 class="mt-2 text-xl font-black text-slate-900">
                    Langkah Berikutnya
                </h3>

                <p class="mt-5 text-base font-medium leading-8 text-slate-700">
                    {{ $tindakLanjut }}
                </p>
            </div>

        </div>

        <div class="xl:col-span-4">
            <div class="sticky top-6 space-y-5">

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-black text-slate-900">
                        Informasi Konsultasi
                    </h3>

                    <div class="mt-6 space-y-4">
                        <div class="border-b border-slate-100 pb-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Psikolog</p>
                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $namaPsikolog }}
                            </p>
                        </div>

                        <div class="border-b border-slate-100 pb-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Tanggal Hasil</p>
                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $tanggalHasil }}
                            </p>
                        </div>

                        <div class="border-b border-slate-100 pb-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Tanggal Konsultasi</p>
                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $tanggalKonsultasi }}
                            </p>
                        </div>

                        <div class="border-b border-slate-100 pb-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Jam Konsultasi</p>
                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $jamKonsultasi }}
                            </p>
                        </div>

                        <div class="border-b border-slate-100 pb-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Metode Komunikasi</p>
                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $metodeText }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Status Konsultasi</p>
                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $statusKonsultasiText }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-blue-100 bg-blue-50 p-6">
                    <h3 class="text-lg font-black text-[#01588E]">
                        Catatan Penting
                    </h3>

                    <p class="mt-3 text-sm font-medium leading-7 text-slate-600">
                        Hasil konsultasi ini merupakan dokumentasi layanan psikologis dan dapat digunakan sebagai acuan tindak lanjut sesuai arahan psikolog.
                    </p>
                </div>

                @if($konsultasi->rujukanPsikiater)
                    <div class="rounded-3xl border border-green-100 bg-green-50 p-6">
                        <h3 class="text-lg font-black text-green-700">
                            Surat Rujukan Tersedia
                        </h3>

                        <p class="mt-3 text-sm font-medium leading-7 text-slate-600">
                            Psikolog merekomendasikan pemeriksaan lanjutan ke psikiater.
                        </p>

                        <a href="{{ route('pasien.rujukan-psikiater.show', $konsultasi->rujukanPsikiater->id_rujukan) }}"
                           class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-[#01588E] px-5 py-4 text-sm font-black text-white transition hover:bg-[#01446d]">
                            Lihat Surat Rujukan
                        </a>
                    </div>
                @endif

                <a href="{{ route('pasien.hasil-konsultasi.index') }}"
                   class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-black text-slate-700 transition hover:bg-slate-50">
                    Kembali ke Daftar Hasil
                </a>

            </div>
        </div>

    </div>

</div>

@endsection