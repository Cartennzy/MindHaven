@extends('backend.layouts.app')

@section('title', 'Data Pembayaran')

@section('content')

@php
    use Carbon\Carbon;

    $dataPembayaran = isset($pembayarans) ? $pembayarans : collect();

    function nominalPembayaran($item) {
        $nominal =
            $item->total_bayar
            ?? $item->harga
            ?? $item->nominal
            ?? $item->jumlah
            ?? $item->total
            ?? $item->amount
            ?? $item->konsultasi->harga
            ?? null;

        if ($nominal === null || $nominal == 0) {
            $biayaAdmin = $item->biaya_admin ?? 0;
            $biayaPsikolog = $item->biaya_psikolog ?? 0;
            $nominal = $biayaAdmin + $biayaPsikolog;
        }

        return (float) $nominal;
    }

    $totalPembayaran = $dataPembayaran->count();

    $totalNominal = $dataPembayaran->sum(function ($item) {
        return nominalPembayaran($item);
    });
@endphp

<div class="space-y-6">

    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-cyan-50 via-white to-blue-50 px-6 py-7 shadow-[0_18px_55px_rgba(15,23,42,.06)] md:px-8 md:py-8">

        <div class="absolute -right-28 -top-28 h-72 w-72 rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-cyan-100/50 blur-3xl"></div>

        <div class="relative">

            <div class="inline-flex items-center gap-3 rounded-full border border-blue-100 bg-white/90 px-4 py-2 text-sm font-medium text-[#01588E] shadow-sm">
                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                Data Pembayaran MindHaven
            </div>

            <h1 class="mt-6 max-w-4xl text-4xl font-semibold leading-tight tracking-tight text-slate-900 md:text-5xl">
                Kelola Data
                <span class="text-[#0284C7]">
                    Pembayaran
                </span>
            </h1>

            <p class="mt-5 max-w-4xl text-base font-normal leading-8 text-slate-600 md:text-lg">
                Pantau seluruh pembayaran konsultasi pasien dengan tampilan modern dan lebih rapi.
            </p>

            <div class="mt-7 grid max-w-xl grid-cols-1 gap-4 sm:grid-cols-2">

                <div class="rounded-[1.7rem] border border-slate-100 bg-white/90 p-4 shadow-sm backdrop-blur">

                    <div class="flex items-center gap-4">

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75h19.5M3.75 5.25h16.5A1.5 1.5 0 0 1 21.75 6.75v10.5H2.25V6.75A1.5 1.5 0 0 1 3.75 5.25Z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                Total Pembayaran
                            </p>

                            <h3 class="mt-1 text-lg font-semibold text-slate-900">
                                {{ $totalPembayaran }} Transaksi
                            </h3>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- LIST PEMBAYARAN --}}
    <section class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,.05)] md:p-7">

        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <h2 class="text-2xl font-semibold text-slate-900">
                    Daftar Pembayaran
                </h2>

                <p class="mt-1 text-sm font-normal text-slate-500">
                    Semua data pembayaran konsultasi pasien.
                </p>
            </div>

            <div class="rounded-2xl bg-[#01588E]/10 px-5 py-3 text-sm font-medium text-[#01588E]">
                Total Nominal:
                Rp{{ number_format($totalNominal, 0, ',', '.') }}
            </div>

        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

            @forelse ($dataPembayaran as $pembayaran)

                @php
                    $pembayaranId = $pembayaran->id_pembayaran ?? $pembayaran->id ?? null;

                    $kode = $pembayaran->kode_pembayaran
                        ?? $pembayaran->kode
                        ?? $pembayaran->order_id
                        ?? ($pembayaranId ? 'MH-PAY-' . str_pad($pembayaranId, 3, '0', STR_PAD_LEFT) : 'MH-PAY');

                    $pasienNama = $pembayaran->pasien->nama_lengkap
                        ?? $pembayaran->pasien->user->name
                        ?? $pembayaran->konsultasi->pasien->nama_lengkap
                        ?? $pembayaran->konsultasi->pasien->user->name
                        ?? '-';

                    $nominal = nominalPembayaran($pembayaran);

                    $metode = $pembayaran->metode_pembayaran
                        ?? $pembayaran->metode
                        ?? $pembayaran->payment_type
                        ?? 'Transfer Bank';

                    $tanggal = $pembayaran->created_at
                        ? Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y')
                        : '-';
                @endphp

                <div class="rounded-[1.7rem] border border-slate-100 bg-gradient-to-br from-white to-slate-50 p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_20px_45px_rgba(15,23,42,.07)]">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                        <div class="flex items-start gap-4">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75h19.5M3.75 5.25h16.5A1.5 1.5 0 0 1 21.75 6.75v10.5H2.25V6.75A1.5 1.5 0 0 1 3.75 5.25Z"/>
                                </svg>
                            </div>

                            <div>

                                <h3 class="text-lg font-semibold text-slate-900">
                                    {{ $kode }}
                                </h3>

                                <p class="mt-1 text-sm font-normal text-slate-500">
                                    Pembayaran dari {{ $pasienNama }}
                                </p>

                            </div>

                        </div>

                        @if($pembayaranId)
                            <a href="{{ route('admin.pembayaran.show', ['pembayaran' => $pembayaranId]) }}"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#01588E] px-5 py-3 text-sm font-medium text-white shadow-lg shadow-[#01588E]/20 transition duration-300 hover:bg-[#01446e]">

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>

                                Detail

                            </a>
                        @else
                            <button type="button"
                                    disabled
                                    class="inline-flex cursor-not-allowed items-center justify-center gap-2 rounded-2xl bg-slate-300 px-5 py-3 text-sm font-medium text-white">
                                Detail
                            </button>
                        @endif

                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-3">

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Pasien
                            </p>

                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $pasienNama }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Nominal
                            </p>

                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                Rp{{ number_format($nominal, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Metode
                            </p>

                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $metode }}
                            </p>
                        </div>

                    </div>

                    <div class="mt-3 rounded-2xl border border-slate-100 bg-white p-4">

                        <p class="text-xs font-medium text-slate-500">
                            Tanggal Transaksi
                        </p>

                        <p class="mt-2 text-sm font-semibold text-slate-900">
                            {{ $tanggal }}
                        </p>

                    </div>

                </div>

            @empty

                <div class="rounded-[2rem] border border-dashed border-slate-200 bg-slate-50 p-10 text-center xl:col-span-2">

                    <h3 class="text-lg font-semibold text-slate-800">
                        Belum ada data pembayaran
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Data pembayaran akan muncul setelah pasien melakukan pembayaran konsultasi.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection