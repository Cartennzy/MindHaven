@extends('frontend.layouts.psikolog')

@section('title', 'Data Konsultasi')
@section('page-title', 'Data Konsultasi')

@section('content')

@php
    $namaPasienKonsultasi = function ($konsultasi) {
        return $konsultasi->pasien->nama_lengkap
            ?? $konsultasi->pasien->user->name
            ?? 'Pasien';
    };

    $emailPasienKonsultasi = function ($konsultasi) {
        return $konsultasi->pasien->user->email ?? '-';
    };

    $statusTextKonsultasi = function ($status) {
        return match ($status) {
            'pending' => 'Pending',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => '-',
        };
    };

    $statusClassKonsultasi = function ($status) {
        return match ($status) {
            'pending' => 'bg-[#FFF7E8] text-[#B58105]',
            'diproses' => 'bg-[#E8F1FF] text-[#155EEF]',
            'selesai' => 'bg-[#E9FBEF] text-[#12B76A]',
            'dibatalkan' => 'bg-[#FEE4E2] text-[#D92D20]',
            default => 'bg-slate-100 text-slate-600',
        };
    };

    $metodeTextKonsultasi = function ($metode) {
        return match ($metode) {
            'chat' => 'Chat',
            'video_call' => 'Video Call',
            'temu_janji' => 'Temu Janji',
            'online' => 'Chat',
            'offline' => 'Temu Janji',
            'video' => 'Video Call',
            'tatap_muka' => 'Temu Janji',
            default => '-',
        };
    };

    $totalKonsultasi = $konsultasis->count();
    $pending = $konsultasis->where('status', 'pending')->count();
    $diproses = $konsultasis->where('status', 'diproses')->count();
    $selesai = $konsultasis->where('status', 'selesai')->count();
@endphp

<div class="space-y-7">

    @if(session('success'))
        <div class="rounded-[24px] border border-green-100 bg-green-50 px-5 py-4 text-sm font-medium text-green-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-[24px] border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#061A33] via-[#01588E] to-[#12B76A] p-7 shadow-[0_28px_90px_rgba(1,88,142,0.18)] md:p-8">
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-[#B7E3FF]/20 blur-3xl"></div>

        <div class="relative z-10">
            <p class="text-sm font-medium tracking-wide text-white/75">
                MindHaven Consultation Management
            </p>

            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-white md:text-5xl">
                Data Konsultasi
            </h1>

            <p class="mt-4 max-w-2xl text-sm font-medium leading-7 text-white/75 md:text-base">
                Kelola konsultasi pasien yang masuk, sedang diproses, hingga selesai dengan tampilan yang lebih mudah dibaca.
            </p>

            <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[28px] bg-white/95 p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)] backdrop-blur">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Total</p>
                    <h3 class="mt-4 text-4xl font-semibold text-[#061A33]">{{ $totalKonsultasi }}</h3>
                    <p class="mt-2 text-sm font-medium text-slate-400">Seluruh konsultasi</p>
                </div>

                <div class="rounded-[28px] bg-white/95 p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)] backdrop-blur">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Pending</p>
                    <h3 class="mt-4 text-4xl font-semibold text-[#B58105]">{{ $pending }}</h3>
                    <p class="mt-2 text-sm font-medium text-slate-400">Menunggu diproses</p>
                </div>

                <div class="rounded-[28px] bg-white/95 p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)] backdrop-blur">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Diproses</p>
                    <h3 class="mt-4 text-4xl font-semibold text-[#155EEF]">{{ $diproses }}</h3>
                    <p class="mt-2 text-sm font-medium text-slate-400">Konsultasi aktif</p>
                </div>

                <div class="rounded-[28px] bg-white/95 p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)] backdrop-blur">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Selesai</p>
                    <h3 class="mt-4 text-4xl font-semibold text-[#12B76A]">{{ $selesai }}</h3>
                    <p class="mt-2 text-sm font-medium text-slate-400">Konsultasi selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[36px] border border-white bg-white p-6 shadow-[0_22px_75px_rgba(15,23,42,0.08)] md:p-7">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-[#061A33]">
                    Seluruh Data Konsultasi
                </h2>

                <p class="mt-2 text-sm font-medium leading-6 text-slate-400">
                    Setiap pasien ditampilkan dalam card supaya status, jadwal, dan aksinya lebih jelas.
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-2xl bg-[#EEF4FF] px-5 py-3 text-sm font-semibold text-[#155EEF]">
                <span class="h-2.5 w-2.5 rounded-full bg-[#155EEF]"></span>
                {{ $totalKonsultasi }} Data
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
                                        {{ strtoupper(substr($namaPasienKonsultasi($konsultasi), 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-lg font-semibold text-[#061A33]">
                                            {{ $namaPasienKonsultasi($konsultasi) }}
                                        </h3>

                                        <p class="mt-1 truncate text-xs font-medium text-slate-400">
                                            {{ $emailPasienKonsultasi($konsultasi) }}
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold {{ $statusClassKonsultasi($konsultasi->status) }}">
                                    {{ $statusTextKonsultasi($konsultasi->status) }}
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Topik Konseling</p>
                                    <p class="mt-1 text-sm font-medium leading-6 text-slate-700">
                                        {{ $konsultasi->topik_konsultasi ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Metode</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700">
                                        {{ $metodeTextKonsultasi($konsultasi->metode_konsultasi ?? null) }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100 sm:col-span-2">
                                    <p class="text-xs font-medium text-slate-400">Keluhan Pasien</p>
                                    <p class="mt-1 line-clamp-3 text-sm font-medium leading-6 text-slate-700">
                                        {{ $konsultasi->keluhan ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Tanggal Konsultasi</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700">
                                        {{ $konsultasi->tanggal_konsultasi ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-100">
                                    <p class="text-xs font-medium text-slate-400">Jam Konsultasi</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700">
                                        {{ $konsultasi->jam_konsultasi ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                                @if($konsultasi->status === 'pending')
                                    <form action="{{ route('psikolog.konsultasi.accept', $konsultasi->id_konsultasi) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                onclick="return confirm('ACC konsultasi ini?')"
                                                class="w-full rounded-2xl bg-[#12B76A] px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#0E9F5B]">
                                            ACC Konsultasi
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('psikolog.konsultasi.show', $konsultasi->id_konsultasi) }}"
                                   class="w-full rounded-2xl bg-[#061A33] px-5 py-3 text-center text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#01588E]">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-[30px] border border-dashed border-slate-200 bg-[#F8FAFC] px-6 py-20 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4-4-4z"/>
                    </svg>
                </div>

                <h3 class="mt-5 text-xl font-semibold tracking-tight text-[#061A33]">
                    Belum Ada Konsultasi
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm font-medium leading-6 text-slate-400">
                    Data akan muncul setelah pasien membuat konsultasi.
                </p>
            </div>
        @endif
    </div>

</div>

@endsection