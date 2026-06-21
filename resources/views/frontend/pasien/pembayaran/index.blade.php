@extends('frontend.layouts.app')

@section('title', 'Pembayaran - MindHaven')
@section('page_title', 'Pembayaran')
@section('page_subtitle', 'Kelola pembayaran konsultasi Anda.')

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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 15h4"/>
                    </svg>
                </div>

                <div>
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/10 px-3.5 py-1 text-[11px] font-black uppercase tracking-[0.14em] text-white backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#A7E36F] animate-pulse"></span>
                        Riwayat Pembayaran
                    </span>
                    <h3 class="text-3xl font-black tracking-tight text-white">Pembayaran</h3>
                    <p class="mt-1 text-sm font-semibold text-white/85">Pantau status verifikasi dan bukti pembayaran invoice konsultasi Anda secara real-time.</p>
                </div>
            </div>

            <div class="rounded-[26px] border border-white/20 bg-white/10 px-6 py-4 text-center backdrop-blur-md shadow-inner min-w-[120px]">
                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-white/80">Total Riwayat</p>
                <p class="mt-0.5 text-4xl font-black text-white">{{ $pembayarans->count() }}</p>
            </div>
        </div>
    </div>

    {{-- LIST PEMBAYARAN --}}
    <div class="space-y-6">
        @forelse($pembayarans as $pembayaran)
            @php
                $konsultasi = $pembayaran->konsultasi;

                $statusText = match($pembayaran->status_pembayaran) {
                    'diterima' => 'Diterima',
                    'gagal' => 'Gagal',
                    'expired' => 'Expired',
                    default => 'Pending',
                };

                $statusClass = match($pembayaran->status_pembayaran) {
                    'diterima' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                    'gagal' => 'border-rose-200 bg-rose-50 text-rose-700',
                    'expired' => 'border-slate-200 bg-slate-50 text-slate-600',
                    default => 'border-amber-200 bg-amber-50 text-amber-700',
                };

                $metodePembayaranRaw = $pembayaran->metode_pembayaran ?? null;

                $metodePembayaranText = match($metodePembayaranRaw) {
                    'transfer_bank' => 'Transfer Bank',
                    'bayar_langsung' => 'Bayar Langsung',
                    'cash' => 'Bayar Langsung',
                    'tunai' => 'Bayar Langsung',
                    'bank_transfer' => 'Transfer Bank',
                    'Midtrans', 'midtrans' => 'Midtrans Gateway',
                    default => $metodePembayaranRaw ? ucwords(str_replace('_', ' ', $metodePembayaranRaw)) : 'Belum Dipilih',
                };

                $hargaTampil = $pembayaran->total_pembayaran
                    ?? $konsultasi->harga
                    ?? 0;
            @endphp

            <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-gradient-to-br from-white via-[#F8FAFC] to-[#F1F5F9] p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:shadow-[0_22px_50px_rgba(1,88,142,0.07)] hover:-translate-y-0.5 hover:border-slate-200">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b from-[#01588E] to-[#49C5B6]"></div>

                <div class="relative flex flex-col gap-6">
                    
                    {{-- Row Header Dalam Card --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between w-full">
                        <div class="flex items-center gap-3.5">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-[#01588E] text-xl border border-slate-100 shadow-sm transition-all duration-300 group-hover:scale-105">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 leading-tight">Invoice Pembayaran #{{ $loop->iteration }}</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5 uppercase tracking-wider">ID Transaksi Semat Medis</p>
                            </div>
                        </div>

                        <div class="inline-flex h-9 items-center justify-center rounded-xl border px-4 text-xs font-black uppercase tracking-wider shadow-sm {{ $statusClass }} self-start sm:self-center">
                            {{ $statusText }}
                        </div>
                    </div>

                    {{-- Metadata Transaksi Box --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white border border-slate-100/80 p-5 rounded-2xl shadow-inner">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm text-slate-500"><i class="fas fa-user-user"></i></div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Dokter Psikolog</span>
                                <span class="text-sm font-black text-slate-800 mt-0.5 block truncate">{{ $konsultasi->psikolog->nama_lengkap ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm text-[#01588E]"><i class="fas fa-money-check-dollar"></i></div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Total Pembayaran</span>
                                <span class="text-sm font-black text-[#01588E] mt-0.5 block">Rp {{ number_format($hargaTampil, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm text-[#41AD01]"><i class="fas fa-credit-card"></i></div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Metode Pembayaran</span>
                                <span class="text-sm font-black text-slate-800 mt-0.5 block">{{ $metodePembayaranText }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button Baris Bawah Card --}}
                    <div class="border-t border-slate-200/50 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
                        <p class="text-xs font-semibold text-slate-400"><i class="fas fa-shield-check mr-1"></i> Bukti pembayaran digunakan akurat oleh sistem untuk validasi berkas rekam medis.</p>
                        
                        <div class="flex items-center gap-2.5 self-end sm:self-center">
                            @if($pembayaran->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                   target="_blank"
                                   class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-5 text-xs font-black text-white shadow-sm hover:scale-[1.01] transition whitespace-nowrap">
                                    <i class="fas fa-images"></i> Lihat Bukti Transfer
                                </a>
                            @else
                                <span class="inline-flex h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-black text-slate-400">
                                    <i class="fas fa-ban"></i> Belum Mengunggah Bukti
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- UPGRADE ELEMEN @EMPTY - MULTI-TONE GRADIENT DYNAMIC CARD SESUAI SCREENSHOT ACUAN --}}
            <div class="rounded-[34px] border border-[#BFE7F3] bg-gradient-to-br from-white via-[#F4FAFF] to-[#EFFDF9] p-10 text-center shadow-[0_20px_50px_rgba(1,88,142,0.05)] md:p-14">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[26px] bg-gradient-to-br from-[#01588E] to-[#49C5B6] text-white shadow-[0_12px_30px_rgba(1,88,142,0.25)] animate-pulse">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 15h4"/>
                    </svg>
                </div>

                <h3 class="mt-7 text-2xl font-black text-slate-900">Belum Ada Data Pembayaran</h3>
                <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-7 text-slate-400">
                    Sistem tidak mendeteksi invoice tagihan konsultasi aktif saat ini. Data riwayat verifikasi transaksi perbankan Anda akan terakumulasi otomatis di sini.
                </p>

                <a href="{{ route('pasien.konsultasi.index') }}"
                   class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-7 text-sm font-black text-white shadow-[0_12_24px_rgba(1,88,142,0.15)] hover:-translate-y-0.5 transition duration-300">
                    <i class="fas fa-clipboard-check"></i> Periksa Konsultasi Saya
                </a>
            </div>
        @endforelse
    </div>

</div>

@endsection