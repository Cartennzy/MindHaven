@extends('backend.layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')

@php
    use Carbon\Carbon;

    // Ambil nominal total, biaya admin, dan biaya psikolog langsung dari database
    $biayaAdmin = $pembayaran->biaya_admin ?? 0;
    $biayaPsikolog = $pembayaran->biaya_psikolog ?? 0;

    $nominalTotal = $pembayaran->total_pembayaran
        ?? $pembayaran->total_bayar
        ?? $pembayaran->harga
        ?? $pembayaran->nominal
        ?? null;

    if ($nominalTotal === null || $nominalTotal == 0) {
        $nominalTotal = $biayaAdmin + $biayaPsikolog;
    }

    // Pembuatan kode transaksi dinamis
    $pembayaranId = $pembayaran->id_pembayaran ?? $pembayaran->id ?? null;
    $kode = $pembayaran->kode_pembayaran
        ?? $pembayaran->kode
        ?? $pembayaran->order_id
        ?? ($pembayaranId ? 'MH-PAY-' . str_pad($pembayaranId, 3, '0', STR_PAD_LEFT) : 'MH-PAY');

    // Pemanggilan nama pasien secara dinamis
    $pasienNama = $pembayaran->pasien->nama_lengkap
        ?? $pembayaran->pasien->user->name
        ?? $pembayaran->konsultasi->pasien->nama_lengkap
        ?? $pembayaran->konsultasi->pasien->user->name
        ?? '-';

    // Pemanggilan nama psikolog
    $psikologNama = $pembayaran->konsultasi->psikolog->nama_lengkap 
        ?? $pembayaran->konsultasi->psikolog->user->name 
        ?? '-';

    $metode = $pembayaran->metode_pembayaran
        ?? $pembayaran->metode
        ?? $pembayaran->payment_type
        ?? 'Transfer Bank';

    $tanggal = $pembayaran->created_at
        ? Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y')
        : '-';
@endphp

<div class="space-y-6">

    <div class="admin-card p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Detail Transaksi Pendapatan
                </h1>
                <p class="text-slate-500 mt-2">
                    Rincian pembagian nominal pendapatan Admin MindHaven dan Psikolog.
                </p>
            </div>

            @if($pembayaran->status_pembayaran === 'diterima')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 border border-emerald-200">Berhasil</span>
            @elseif($pembayaran->status_pembayaran === 'pending')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700 border border-amber-200">Pending</span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 border border-rose-200">{{ ucfirst($pembayaran->status_pembayaran ?? 'Gagal') }}</span>
            @endif
        </div>
    </div>

    <div class="admin-card p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Kode Pembayaran</p>
                <h4 class="text-lg font-bold text-[#01588E] mt-1">{{ $kode }}</h4>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Nama Pasien</p>
                <h4 class="text-lg font-bold text-slate-800 mt-1">{{ $pasienNama }}</h4>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Konsultasi Dengan</p>
                <h4 class="text-lg font-bold text-slate-800 mt-1">{{ $psikologNama }}</h4>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Metode Pembayaran</p>
                <h4 class="text-lg font-bold text-slate-800 mt-1">{{ strtoupper(str_replace('_', ' ', $metode)) }}</h4>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Tanggal Transaksi</p>
                <h4 class="text-lg font-bold text-slate-800 mt-1">{{ $tanggal }}</h4>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-sm text-slate-500">Total Nominal Pembayaran (Pasien)</p>
                <h4 class="text-lg font-bold text-slate-900 mt-1">Rp{{ number_format($nominalTotal, 0, ',', '.') }}</h4>
            </div>

            {{-- DETAIL RINCIAN PENDAPATAN --}}
            <div class="border-t border-dashed border-slate-200 pt-6 md:col-span-2">
                <h3 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-[#0284C7]"></span>
                    Rincian Pembagian Pendapatan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- PENDAPATAN ADMIN --}}
                    <div class="rounded-2xl border border-sky-100 bg-sky-50/60 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-sky-600">Pendapatan Admin (Fee)</p>
                        <h4 class="text-2xl font-bold text-sky-900 mt-1.5">
                            Rp{{ number_format($biayaAdmin, 0, ',', '.') }}
                        </h4>
                        <p class="text-xs text-sky-500 mt-1">Hak bersih kelola platform MindHaven</p>
                    </div>

                    {{-- PENDAPATAN PSIKOLOG --}}
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Pendapatan Psikolog</p>
                        <h4 class="text-2xl font-bold text-emerald-900 mt-1.5">
                            Rp{{ number_format($biayaPsikolog, 0, ',', '.') }}
                        </h4>
                        <p class="text-xs text-emerald-500 mt-1">Hak bersih tenaga ahli psikolog</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex gap-3">
            <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 shadow-sm transition duration-150 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </div>

</div>

@endsection