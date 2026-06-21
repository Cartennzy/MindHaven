@extends('backend.layouts.app')

@section('title', 'Data Psikolog')

@section('content')

@php
    $badgePsikologStatus = function ($status) {
        return match ($status) {
            'verified' => 'bg-green-50 text-green-700',
            'pending' => 'bg-yellow-50 text-yellow-700',
            'rejected' => 'bg-red-50 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    };

    $labelPsikologStatus = function ($status) {
        return match ($status) {
            'verified' => 'Terverifikasi',
            'pending' => 'Pending',
            'rejected' => 'Ditolak',
            default => ucfirst($status ?? '-'),
        };
    };
@endphp

<div class="space-y-7">

    @if(session('success'))
        <div class="rounded-2xl bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-hidden rounded-[32px] border border-slate-200/70 bg-gradient-to-br from-emerald-50 via-white to-blue-50 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.06)] md:p-7">

        <div class="absolute -left-20 top-0 h-56 w-56 rounded-full bg-emerald-100/40 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-64 w-64 rounded-full bg-blue-100/30 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">

            <div class="max-w-3xl">

                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-white/80 px-4 py-2 text-xs font-semibold text-[#01588E] shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Data Psikolog MindHaven
                </div>

                <h1 class="mt-5 text-3xl font-semibold leading-tight tracking-tight text-slate-900 md:text-4xl">
                    Kelola Data
                    <span class="bg-gradient-to-r from-[#01588E] to-sky-500 bg-clip-text text-transparent">
                        Psikolog Profesional
                    </span>
                </h1>

                <p class="mt-4 text-[15px] font-medium leading-7 text-slate-600">
                    Pantau data psikolog, pengajuan online, status verifikasi, kelengkapan dokumen, biaya konsultasi, dan keaktifan akun psikolog dalam satu tampilan.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">

                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white px-4 py-3 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 14c4 0 7 2 7 5v1H5v-1c0-3 3-5 7-5zM12 12a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Total Psikolog
                            </p>

                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $psikologs->count() }} Psikolog
                            </h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white px-4 py-3 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Terverifikasi
                            </p>

                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $psikologs->where('status_verifikasi', 'verified')->count() }} Akun
                            </h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white px-4 py-3 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Pending
                            </p>

                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $psikologs->where('status_verifikasi', 'pending')->count() }} Akun
                            </h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white px-4 py-3 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-100 text-red-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Ditolak
                            </p>

                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $psikologs->where('status_verifikasi', 'rejected')->count() }} Akun
                            </h3>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="flex justify-end">
        <a href="{{ route('psikolog.register') }}" target="_blank"
           class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md">
            Link Daftar Online
        </a>
    </div>

    @if($psikologs->count() > 0)

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

            @foreach ($psikologs as $psikolog)

                @php
                    $nama = $psikolog->nama_lengkap ?? 'Psikolog';
                    $initial = strtoupper(substr($nama, 0, 1));
                    $status = $psikolog->status_verifikasi ?? 'pending';

                    $psikologRouteKey = $psikolog->getKey()
                        ?? $psikolog->id_psikolog
                        ?? $psikolog->id
                        ?? null;

                    $dokumenLengkap =
                        !empty($psikolog->dokumen_verifikasi) &&
                        !empty($psikolog->dokumen_pendidikan) &&
                        !empty($psikolog->dokumen_str_psikolog) &&
                        !empty($psikolog->dokumen_sip_psikolog);
                @endphp

                <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-white p-5 shadow-[0_16px_45px_rgba(15,23,42,0.06)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_24px_65px_rgba(15,23,42,0.10)]">

                    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-blue-50 blur-3xl transition duration-500 group-hover:scale-125"></div>
                    <div class="absolute -bottom-20 -left-10 h-48 w-48 rounded-full bg-emerald-50 blur-3xl"></div>

                    <div class="relative flex flex-col gap-5 md:flex-row md:items-start md:justify-between">

                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[22px] bg-gradient-to-br from-[#01588E] to-sky-500 text-xl font-semibold text-white shadow-lg shadow-blue-500/20">
                                {{ $initial }}
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold tracking-tight text-slate-900">
                                    {{ $nama }}
                                </h2>

                                <p class="mt-1 text-sm font-medium text-slate-500">
                                    {{ $psikolog->user->email ?? $psikolog->email ?? '-' }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-full px-3.5 py-1.5 text-xs font-semibold {{ $badgePsikologStatus($status) }}">
                                        {{ $labelPsikologStatus($status) }}
                                    </span>

                                    @if($psikolog->is_active)
                                        <span class="rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-semibold text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3.5 py-1.5 text-xs font-semibold text-slate-600">
                                            Nonaktif
                                        </span>
                                    @endif

                                    @if($dokumenLengkap)
                                        <span class="rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-semibold text-emerald-700">
                                            Dokumen Lengkap
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-50 px-3.5 py-1.5 text-xs font-semibold text-red-700">
                                            Dokumen Belum Lengkap
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($psikologRouteKey)
                            <a href="{{ route('admin.psikolog.show', ['psikolog' => $psikologRouteKey]) }}"
                               class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#01446e]">
                                Detail
                            </a>
                        @endif

                    </div>

                    <div class="relative mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Spesialisasi
                            </p>
                            <p class="mt-2 text-sm font-medium leading-6 text-slate-700">
                                {{ $psikolog->spesialisasi ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Pengalaman
                            </p>
                            <p class="mt-2 text-sm font-medium text-slate-700">
                                {{ $psikolog->pengalaman ?? 0 }} tahun
                            </p>
                        </div>

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Biaya Konsultasi
                            </p>
                            <p class="mt-2 text-sm font-medium text-slate-700">
                                Rp{{ number_format($psikolog->biaya_konsultasi ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Status Akun
                            </p>
                            <p class="mt-2 text-sm font-medium text-slate-700">
                                {{ $psikolog->is_active ? 'Akun aktif digunakan' : 'Akun belum aktif' }}
                            </p>
                        </div>

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4 md:col-span-2">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Kelengkapan Dokumen
                            </p>

                            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs font-semibold">
                                    <span class="text-slate-500">CV</span>
                                    <span class="{{ !empty($psikolog->dokumen_verifikasi) ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ !empty($psikolog->dokumen_verifikasi) ? 'Ada' : 'Kosong' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs font-semibold">
                                    <span class="text-slate-500">Pendidikan</span>
                                    <span class="{{ !empty($psikolog->dokumen_pendidikan) ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ !empty($psikolog->dokumen_pendidikan) ? 'Ada' : 'Kosong' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs font-semibold">
                                    <span class="text-slate-500">STR</span>
                                    <span class="{{ !empty($psikolog->dokumen_str_psikolog) ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ !empty($psikolog->dokumen_str_psikolog) ? 'Ada' : 'Kosong' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs font-semibold">
                                    <span class="text-slate-500">SIP</span>
                                    <span class="{{ !empty($psikolog->dokumen_sip_psikolog) ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ !empty($psikolog->dokumen_sip_psikolog) ? 'Ada' : 'Kosong' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-[30px] border border-dashed border-slate-200 bg-white p-10 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[24px] bg-slate-100 text-slate-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14c4 0 7 2 7 5v1H5v-1c0-3 3-5 7-5zM12 12a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
            </div>

            <h2 class="mt-5 text-xl font-semibold text-slate-900">
                Belum ada data psikolog.
            </h2>

            <p class="mt-2 text-sm font-medium text-slate-500">
                Data psikolog akan muncul setelah psikolog melakukan pendaftaran mandiri.
            </p>
        </div>

    @endif

</div>

@endsection