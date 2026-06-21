@extends('frontend.layouts.app')

@section('title', 'Detail Konsultasi - MindHaven')
@section('page_title', 'Detail Konsultasi')
@section('page_subtitle', 'Kelola detail jadwal dan informasi konsultasi Anda.')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $idKonsultasi = $konsultasi->id_konsultasi ?? $konsultasi->id ?? null;

    $psikolog = $konsultasi->psikolog;
    $userPsikolog = $psikolog->user ?? null;

    $namaPsikolog = $psikolog->nama_lengkap ?? $userPsikolog->name ?? 'Psikolog';
    $emailPsikolog = $userPsikolog->email ?? $psikolog->email ?? '-';

    $fotoPsikolog = null;

    if (!empty($psikolog->foto_profil) && Storage::disk('public')->exists($psikolog->foto_profil)) {
        $fotoPsikolog = asset('storage/' . $psikolog->foto_profil);
    }

    $status = strtolower($konsultasi->status ?? 'pending');

    $statusClass = match ($status) {
        'selesai' => 'bg-green-100 text-green-700 border-green-200',
        'diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
        'dibatalkan' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
    };

    $statusText = match ($status) {
        'selesai' => 'Selesai',
        'diproses' => 'Diproses',
        'dibatalkan' => 'Dibatalkan',
        default => 'Pending',
    };

    $pembayaran = $konsultasi->pembayaran ?? null;
    $detail = $konsultasi->detailKonsultasi ?? null;
    $rujukan = $konsultasi->rujukanPsikiater ?? null;

    $idRujukan = $rujukan->id_rujukan ?? $rujukan->id ?? null;

    $metodeKonsultasi = $konsultasi->metode_konsultasi ?? null;
    $topikKonsultasi = $konsultasi->topik_konsultasi ?? '-';
@endphp

<div class="space-y-8">

    @if(session('success'))
        <div class="rounded-3xl border border-green-100 bg-green-50 px-6 py-5 text-sm font-bold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-3xl border border-red-100 bg-red-50 px-6 py-5 text-sm font-bold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">

        <div class="xl:col-span-2 space-y-8">

            <div class="rounded-[34px] bg-white p-6 shadow-soft md:p-8">

                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

                    <div class="flex items-center gap-5">

                        @if($fotoPsikolog)
                            <img src="{{ $fotoPsikolog }}"
                                 alt="{{ $namaPsikolog }}"
                                 class="h-20 w-20 rounded-[24px] object-cover shadow-md">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-[24px] bg-[#01588E]/10 text-3xl font-black text-[#01588E] shadow-md">
                                {{ strtoupper(substr($namaPsikolog, 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <h2 class="text-2xl font-black text-slate-800">
                                {{ $namaPsikolog }}
                            </h2>

                            <p class="mt-1 text-sm font-bold text-slate-500">
                                {{ $emailPsikolog }}
                            </p>

                            <p class="mt-2 text-sm font-bold text-[#01588E]">
                                {{ $psikolog->spesialisasi ?? 'Psikolog MindHaven' }}
                            </p>
                        </div>

                    </div>

                    <div class="inline-flex w-fit items-center rounded-2xl border px-5 py-3 text-sm font-black {{ $statusClass }}">
                        {{ $statusText }}
                    </div>

                </div>

            </div>

            <div class="rounded-[34px] bg-white p-6 shadow-soft md:p-8">

                <div class="mb-8">
                    <h2 class="text-3xl font-black text-slate-800">
                        Informasi Konsultasi
                    </h2>

                    <p class="mt-2 text-sm font-semibold text-slate-500">
                        Detail jadwal, metode, dan keluhan konsultasi Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div class="rounded-[28px] bg-slate-50 p-6">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                            <i class="fas fa-calendar-days text-xl"></i>
                        </div>

                        <p class="text-sm font-bold text-slate-500">
                            Tanggal Konsultasi
                        </p>

                        <h3 class="mt-2 text-xl font-black text-slate-800">
                            {{ $konsultasi->tanggal_konsultasi ?? '-' }}
                        </h3>
                    </div>

                    <div class="rounded-[28px] bg-slate-50 p-6">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                            <i class="fas fa-clock text-xl"></i>
                        </div>

                        <p class="text-sm font-bold text-slate-500">
                            Jam Konsultasi
                        </p>

                        <h3 class="mt-2 text-xl font-black text-slate-800">
                            {{ $konsultasi->jam_konsultasi ?? '-' }}
                        </h3>
                    </div>

                    <div class="rounded-[28px] bg-slate-50 p-6">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                            <i class="fas fa-comments text-xl"></i>
                        </div>

                        <p class="text-sm font-bold text-slate-500">
                            Metode Konsultasi
                        </p>

                        <h3 class="mt-2 text-xl font-black text-slate-800">
                            {{ $metodeKonsultasi ?? '-' }}
                        </h3>
                    </div>

                    <div class="rounded-[28px] bg-slate-50 p-6">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#41AD01]/10 text-[#41AD01]">
                            <i class="fas fa-brain text-xl"></i>
                        </div>

                        <p class="text-sm font-bold text-slate-500">
                            Topik Konsultasi
                        </p>

                        <h3 class="mt-2 text-xl font-black text-slate-800">
                            {{ $topikKonsultasi }}
                        </h3>
                    </div>

                </div>

                <div class="mt-6 rounded-[28px] bg-slate-50 p-6">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                        <i class="fas fa-note-sticky text-xl"></i>
                    </div>

                    <p class="text-sm font-bold text-slate-500">
                        Keluhan Anda
                    </p>

                    <p class="mt-3 text-sm font-semibold leading-7 text-slate-700">
                        {{ $konsultasi->keluhan ?? 'Belum ada keluhan.' }}
                    </p>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                    @if($pembayaran && $pembayaran->status_pembayaran === 'diterima' && empty($metodeKonsultasi))
                        <a href="{{ route('pasien.konsultasi.metode', ['konsultasi' => $idKonsultasi]) }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-[#41AD01] px-7 py-4 text-sm font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#329000]">
                            Pilih Metode Konsultasi
                            <i class="fas fa-arrow-right ml-3"></i>
                        </a>
                    @endif

                    @if($detail)
                        <a href="{{ route('pasien.hasil-konsultasi.show', ['konsultasi' => $idKonsultasi]) }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] px-7 py-4 text-sm font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#01446e]">
                            Lihat Hasil Konsultasi
                            <i class="fas fa-file-medical ml-3"></i>
                        </a>
                    @endif

                    <a href="{{ route('pasien.konsultasi.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-slate-100 px-7 py-4 text-sm font-black text-slate-700 transition hover:-translate-y-1 hover:bg-slate-200">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Kembali
                    </a>

                </div>

            </div>

        </div>

        <div class="xl:col-span-1 space-y-6">

            <div class="rounded-[34px] bg-white p-6 shadow-soft">

                <h3 class="text-xl font-black text-slate-800">
                    Status Pembayaran
                </h3>

                <div class="mt-5 rounded-[28px] bg-slate-50 p-5">

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-bold text-slate-500">
                            Biaya Konsultasi
                        </span>

                        <span class="text-sm font-black text-slate-800">
                            Rp {{ number_format($konsultasi->harga ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-4 border-t border-slate-200 pt-4">
                        <p class="text-sm font-bold text-slate-500">
                            Status
                        </p>

                        @if($pembayaran)
                            <p class="mt-2 text-base font-black {{ $pembayaran->status_pembayaran === 'diterima' ? 'text-green-700' : 'text-yellow-700' }}">
                                {{ ucfirst($pembayaran->status_pembayaran ?? 'pending') }}
                            </p>
                        @else
                            <p class="mt-2 text-base font-black text-red-700">
                                Belum ada pembayaran
                            </p>
                        @endif
                    </div>

                </div>

                @if(!$pembayaran)
                    <a href="{{ route('pasien.pembayaran.create', ['konsultasi_id' => $idKonsultasi]) }}"
                       class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-[#41AD01] px-6 py-4 text-sm font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#329000]">
                        Lanjutkan Pembayaran
                    </a>
                @endif

            </div>

            <div class="rounded-[34px] bg-white p-6 shadow-soft">

                <h3 class="text-xl font-black text-slate-800">
                    Alur Konsultasi
                </h3>

                <div class="mt-6 space-y-5">

                    <div class="flex gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                            <i class="fas fa-check"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-800">Pilih Psikolog</p>
                            <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Psikolog sudah dipilih.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $pembayaran ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }}">
                            <i class="fas {{ $pembayaran ? 'fa-check' : 'fa-credit-card' }}"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-800">Pembayaran</p>
                            <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Selesaikan pembayaran konsultasi.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ !empty($metodeKonsultasi) ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }}">
                            <i class="fas {{ !empty($metodeKonsultasi) ? 'fa-check' : 'fa-calendar' }}"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-800">Jadwal & Metode</p>
                            <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Tentukan jadwal dan metode konsultasi.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $detail ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }}">
                            <i class="fas {{ $detail ? 'fa-check' : 'fa-file-medical' }}"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-800">Hasil Konsultasi</p>
                            <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Hasil dibuka melalui menu Hasil Konsultasi.</p>
                        </div>
                    </div>

                </div>

            </div>

            @if($rujukan && $idRujukan)
                <div class="rounded-[34px] bg-white p-6 shadow-soft">

                    <h3 class="text-xl font-black text-slate-800">
                        Surat Rujukan
                    </h3>

                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                        Psikolog telah membuat surat rujukan psikiater untuk konsultasi ini.
                    </p>

                    <a href="{{ route('pasien.rujukan-psikiater.show', ['rujukan_psikiater' => $idRujukan]) }}"
                       class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-[#01588E] px-6 py-4 text-sm font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#01446e]">
                        Lihat Surat Rujukan
                        <i class="fas fa-arrow-right ml-3"></i>
                    </a>

                </div>
            @endif

        </div>

    </div>

</div>

@endsection