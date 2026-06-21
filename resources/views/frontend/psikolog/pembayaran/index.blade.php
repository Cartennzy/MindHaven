@extends('frontend.layouts.psikolog')

@section('title', 'Detail Pendapatan Psikolog - MindHaven')
@section('page-title', 'Detail Pendapatan')

@section('content')

<div class="space-y-7 max-w-7xl mx-auto">

    {{-- HEADER BANNER - PREMIUM SAAS FINtech STYLE --}}
    <div class="relative overflow-hidden rounded-[34px] bg-gradient-to-br from-[#061A33] via-[#01588E] to-[#12B76A] p-7 shadow-[0_24px_60px_rgba(1,88,142,0.15)] md:p-8">
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-[#B7E3FF]/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-white/75">Rincian Pembayaran Diterima</p>
                <h1 class="mt-2 text-3xl font-bold text-white md:text-4xl tracking-tight">
                    Detail Pendapatan Psikolog
                </h1>
                <p class="mt-2 max-w-2xl text-sm font-medium leading-relaxed text-white/80">
                    Pantau pendapatan dari setiap pembayaran sesi klinis pasien yang sudah berhasil diverifikasi dan diterima oleh sistem faskes.
                </p>
            </div>

            <a href="{{ route('psikolog.dashboard') }}"
               class="inline-flex w-fit items-center gap-3 rounded-2xl bg-white h-12 px-5 text-sm font-bold text-[#01588E] shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- METRIC SUMMARY CARDS COUNTER --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.03)]">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h2 class="mt-2 text-3xl font-bold text-[#061A33] tracking-tight whitespace-nowrap">
                        Rp {{ number_format($totalPendapatanPsikolog ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="mt-1.5 text-xs font-semibold text-slate-400">Akumulasi biaya psikolog bersih</p>
                </div>

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#E8F6FF] text-[#01588E] border border-blue-50 shadow-inner">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.03)]">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pembayaran Diterima</p>
                    <h2 class="mt-2 text-3xl font-bold text-[#061A33] tracking-tight">
                        {{ $totalPembayaranDiterima ?? 0 }}
                    </h2>
                    <p class="mt-1.5 text-xs font-semibold text-slate-400">Transaksi masuk sukses</p>
                </div>

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#E9FBEF] text-[#12B76A] border border-emerald-50 shadow-inner">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.03)]">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Hari Ini</p>
                    <h2 class="mt-2 text-3xl font-bold text-[#061A33] tracking-tight whitespace-nowrap">
                        Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}
                    </h2>
                    <p class="mt-1.5 text-xs font-semibold text-slate-400">Berdasarkan data hari berjalan</p>
                </div>

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#FFF7E8] text-[#F59E0B] border border-amber-50 shadow-inner">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- DETAIL TRANSAKSI BOX VIEW --}}
    <div class="rounded-[34px] bg-white p-6 shadow-[0_16px_50px_rgba(15,23,42,0.04)] border border-slate-100 md:p-7">
        <div class="mb-6 border-b border-slate-100 pb-5">
            <h2 class="text-xl font-bold text-[#061A33]">Rincian Sumber Pendapatan</h2>
            <p class="mt-1 text-xs font-semibold text-slate-400">
                Log kronologis pembagian invoice klaim dana masuk rekam medis pasien terverifikasi.
            </p>
        </div>

        @if($pembayarans->count() > 0)
            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                @foreach($pembayarans as $index => $pembayaran)
                    @php
                        $konsultasi = $pembayaran->konsultasi;
                        $pasien = $konsultasi?->pasien;

                        $namaPasien = $pasien?->nama_lengkap
                            ?? $pasien?->user?->name
                            ?? 'Pasien';

                        $tanggalKonsultasi = $konsultasi?->tanggal_konsultasi
                            ? \Carbon\Carbon::parse($konsultasi->tanggal_konsultasi)->translatedFormat('d F Y')
                            : '-';

                        $jamKonsultasi = $konsultasi?->jam_konsultasi
                            ? \Carbon\Carbon::parse($konsultasi->jam_konsultasi)->format('H:i')
                            : null;
                    @endphp

                    <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-[#FBFCFE] p-5 shadow-sm transition duration-300 hover:bg-white hover:shadow-[0_20px_50px_rgba(1,88,142,0.06)] hover:border-slate-200">
                        <div class="absolute -right-16 -top-16 h-36 w-36 rounded-full bg-[#E8F6FF] opacity-50 blur-2xl transition group-hover:bg-[#DDF4FF]"></div>

                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#061A33] to-[#01588E] text-sm font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($namaPasien, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Transaksi #{{ $pembayarans->firstItem() + $index }}</span>
                                            <h3 class="mt-0.5 text-base font-bold text-[#061A33] truncate">
                                                {{ $namaPasien }}
                                            </h3>
                                            <p class="text-[10px] font-semibold text-slate-400">ID Rekam: {{ $konsultasi?->id_pasien ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <span class="inline-flex h-7 items-center justify-center rounded-lg bg-emerald-50 border border-emerald-100 px-3 text-[10px] font-bold text-emerald-700 uppercase tracking-wide whitespace-nowrap">
                                        {{ $pembayaran->status_pembayaran ?? '-' }}
                                    </span>
                                </div>

                                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div class="rounded-xl bg-white border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Topik</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 truncate">
                                            {{ $konsultasi?->topik_konsultasi ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-white border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Metode Sesi</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 truncate">
                                            {{ $konsultasi?->metode_konsultasi_text ?? $pembayaran->metode_pembayaran ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-white border border-slate-100 p-3 shadow-inner">
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Order ID</span>
                                        <p class="mt-0.5 text-xs font-semibold text-slate-700 truncate">
                                            {{ $pembayaran->id_order ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- FOOTER CARD: FIX LAYOUT RP DAN NOMINAL AGAR SELALU SEJAJAR HORIZONTAL --}}
                            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between gap-4 w-full">
                                <span class="text-[11px] font-medium text-slate-400 whitespace-nowrap"><i class="far fa-calendar-alt mr-1"></i> {{ $tanggalKonsultasi }} @if($jamKonsultasi) | {{ $jamKonsultasi }} WIB @endif</span>
                                <div class="text-right min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">Pendapatan Bersih</span>
                                    <span class="text-base font-bold text-[#12B76A] whitespace-nowrap block sm:inline">
                                        Rp {{ number_format($pembayaran->biaya_psikolog ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- FOOTER TOTAL BARIS BAWAH: AMAN SINKRON --}}
            <div class="mt-6 rounded-[24px] bg-slate-50/70 border border-slate-100 p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-xl bg-white border border-slate-100 p-4 shadow-sm flex items-center justify-between gap-4">
                        <span class="text-xs font-bold text-slate-500">Total Akumulasi Honor Klaim Sesi (Halaman Ini)</span>
                        <p class="text-xl font-bold text-[#12B76A] whitespace-nowrap">
                            Rp {{ number_format($pembayarans->sum('biaya_psikolog'), 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-white border border-slate-100 p-4 shadow-sm flex items-center justify-between gap-4">
                        <span class="text-xs font-bold text-slate-500">Total Keseluruhan Dana Cair (Halaman Ini)</span>
                        <p class="text-xl font-bold text-[#061A33] whitespace-nowrap">
                            Rp {{ number_format($pembayarans->sum('biaya_psikolog'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            @if($pembayarans->hasPages())
                <div class="mt-6 border-t border-slate-100 pt-5">
                    {{ $pembayarans->links() }}
                </div>
            @endif

        @else
            <div class="rounded-[30px] border border-dashed border-slate-200 bg-[#F8FAFC] px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-bold text-[#061A33]">Belum ada pendapatan diterima</h3>
                <p class="mx-auto mt-1 max-w-md text-xs font-semibold leading-relaxed text-slate-400">
                    Sistem keuangan bursa kerja MindHaven tidak mendeteksi adanya mutasi dana masuk. Pencatatan honorarium remunerasi akan ter-update otomatis seketika setelah pembayaran invoice pasien disetujui faskes.
                </p>
            </div>
        @endif
    </div>

</div>

@endsection