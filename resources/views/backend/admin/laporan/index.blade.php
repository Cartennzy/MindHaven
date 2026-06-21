@extends('backend.layouts.app')

@section('title', 'Laporan Keseluruhan')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-[34px] border border-white/80 bg-gradient-to-br from-emerald-50 via-white to-sky-50 p-7 shadow-sm md:p-9">

        <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl"></div>
        <div class="absolute -right-24 -bottom-24 h-80 w-80 rounded-full bg-sky-200/50 blur-3xl"></div>

        <div class="relative">
            <div class="inline-flex items-center gap-3 rounded-full border border-emerald-100 bg-white/90 px-5 py-3 shadow-sm">
                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                <span class="text-sm font-semibold text-[#01588E]">
                    Laporan Data MindHaven
                </span>
            </div>

            <div class="mt-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold leading-tight tracking-tight text-slate-900 md:text-4xl">
                        Kelola Laporan
                        <span class="text-sky-600">Keseluruhan Sistem</span>
                    </h1>

                    <p class="mt-4 max-w-4xl text-sm font-medium leading-7 text-slate-600 md:text-base">
                        Pantau laporan transaksi pembayaran dan laporan hasil konsultasi pasien secara terpisah berdasarkan bulan, tahun, atau rentang tanggal.
                    </p>

                    @if(request('bulan') || request('tahun') || request('tanggal_mulai') || request('tanggal_selesai'))
                        <div class="mt-4 inline-flex rounded-full bg-white px-5 py-3 text-sm font-semibold text-[#01588E] shadow-sm">
                            Periode:
                            @if(request('bulan'))
                                {{ $bulanList[(int) request('bulan')] ?? '' }}
                            @endif

                            @if(request('tahun'))
                                {{ request('tahun') }}
                            @endif

                            @if(request('tanggal_mulai') || request('tanggal_selesai'))
                                {{ request('tanggal_mulai') ?? '-' }} s/d {{ request('tanggal_selesai') ?? '-' }}
                            @endif
                        </div>
                    @endif
                </div>

                <button onclick="window.print()"
                        class="print:hidden w-fit rounded-2xl bg-[#01588E] px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-[#01466f]">
                    Cetak Laporan
                </button>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[24px] border border-slate-100 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Total Konsultasi</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalKonsultasi }} Data</h2>
                </div>

                <div class="rounded-[24px] border border-slate-100 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Konsultasi Selesai</p>
                    <h2 class="mt-2 text-2xl font-semibold text-emerald-600">{{ $konsultasiSelesai }} Data</h2>
                </div>

                <div class="rounded-[24px] border border-slate-100 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Total Transaksi</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalTransaksi }} Data</h2>
                </div>

                <div class="rounded-[24px] border border-slate-100 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Pembayaran Diterima</p>
                    <h2 class="mt-2 text-2xl font-semibold text-emerald-600">{{ $totalPembayaranDiterima }} Data</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('admin.laporan.index') }}"
          class="print:hidden rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Cari Laporan
                </label>

                <input type="text"
                       name="keyword"
                       value="{{ request('keyword') }}"
                       placeholder="Cari nama pasien, psikolog, topik, atau ID order..."
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Bulan
                </label>

                <select name="bulan"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
                    <option value="">Semua Bulan</option>
                    @foreach($bulanList as $nomorBulan => $namaBulan)
                        <option value="{{ $nomorBulan }}" {{ (string) request('bulan') === (string) $nomorBulan ? 'selected' : '' }}>
                            {{ $namaBulan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Tahun
                </label>

                <select name="tahun"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ (string) request('tahun') === (string) $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Dari Tanggal
                </label>

                <input type="date"
                       name="tanggal_mulai"
                       value="{{ request('tanggal_mulai') }}"
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Sampai Tanggal
                </label>

                <input type="date"
                       name="tanggal_selesai"
                       value="{{ request('tanggal_selesai') }}"
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Status Pembayaran
                </label>

                <select name="status_pembayaran"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status_pembayaran') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diterima" {{ request('status_pembayaran') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="gagal" {{ request('status_pembayaran') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    <option value="expired" {{ request('status_pembayaran') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Status Konsultasi
                </label>

                <select name="status_konsultasi"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status_konsultasi') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status_konsultasi') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status_konsultasi') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status_konsultasi') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-slate-600">
                    Psikolog
                </label>

                <select name="id_psikolog"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-[#01588E] focus:bg-white">
                    <option value="">Semua Psikolog</option>
                    @foreach($psikologs as $psikolog)
                        <option value="{{ $psikolog->id_psikolog }}" {{ (string) request('id_psikolog') === (string) $psikolog->id_psikolog ? 'selected' : '' }}>
                            {{ $psikolog->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-3">
            <button type="submit"
                    class="rounded-2xl bg-[#01588E] px-5 py-3 text-sm font-semibold text-white hover:bg-[#01466f]">
                Cari Laporan
            </button>

            <a href="{{ route('admin.laporan.index') }}"
               class="rounded-2xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                Reset
            </a>
        </div>
    </form>

    {{-- LAPORAN TRANSAKSI --}}
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="text-xl font-semibold text-slate-900">Laporan Transaksi Pembayaran</h2>
            <p class="mt-1 text-sm font-medium text-slate-500">
                Data pembayaran konsultasi, pasien, psikolog, dan total biaya.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @forelse($laporans as $laporan)
                @php
                    $konsultasi = $laporan->konsultasi;
                    $pasien = optional($konsultasi)->pasien;
                    $psikolog = optional($konsultasi)->psikolog;

                    $badgeBayar = match($laporan->status_pembayaran) {
                        'diterima' => 'bg-emerald-50 text-emerald-700',
                        'pending' => 'bg-amber-50 text-amber-700',
                        'gagal' => 'bg-red-50 text-red-700',
                        'expired' => 'bg-slate-100 text-slate-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <div class="rounded-3xl border border-slate-100 bg-slate-50/70 p-5 shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">ID Order</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">
                                {{ $laporan->id_order ?? '-' }}
                            </h3>
                        </div>

                        <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $badgeBayar }}">
                            {{ ucfirst($laporan->status_pembayaran ?? '-') }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Pasien</p>
                            <h4 class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($pasien)->nama_lengkap ?? '-' }}
                            </h4>
                        </div>

                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Psikolog</p>
                            <h4 class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($psikolog)->nama_lengkap ?? '-' }}
                            </h4>
                        </div>
                    </div>

                    {{-- GRID TAMPILAN NOMINAL SETELAH BIAYA PSIKOLOG DIHAPUS --}}
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Biaya Admin</p>
                            <p class="mt-1 text-sm font-semibold text-[#01588E]">
                                Rp {{ number_format($laporan->biaya_admin ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Total Bayar</p>
                            <p class="mt-1 text-sm font-semibold text-emerald-600">
                                Rp {{ number_format($laporan->total_pembayaran ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="xl:col-span-2 rounded-3xl bg-slate-50 p-8 text-center text-sm font-medium text-slate-500">
                    Belum ada data transaksi pada periode ini.
                </div>
            @endforelse
        </div>
    </div>

    {{-- GRAFIK TRANSAKSI --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2 print:hidden">
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Grafik Status Pembayaran</h2>
            <div class="mt-5 h-[300px]">
                <canvas id="pembayaranChart"></canvas>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">
                Grafik Pendapatan Per Bulan Tahun {{ $tahunGrafik }}
            </h2>
            <div class="mt-5 h-[300px]">
                <canvas id="pendapatanBulananChart"></canvas>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm xl:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900">
                Grafik Transaksi Per Bulan Tahun {{ $tahunGrafik }}
            </h2>
            <div class="mt-5 h-[320px]">
                <canvas id="transaksiBulananChart"></canvas>
            </div>
        </div>
    </div>

    {{-- LAPORAN HASIL KONSULTASI --}}
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="mb-5">
            <h2 class="text-xl font-semibold text-slate-900">Laporan Hasilamp Konsultasi</h2>
            <p class="mt-1 text-sm font-medium text-slate-500">
                Data pasien, psikolog yang menangani, topik konsultasi, status, tanggal, jam, dan metode konsultasi.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @forelse($laporans as $laporan)
                @php
                    $konsultasi = $laporan->konsultasi;
                    $pasien = optional($konsultasi)->pasien;
                    $psikolog = optional($konsultasi)->psikolog;

                    $badgeKonsultasi = match(optional($konsultasi)->status) {
                        'selesai' => 'bg-emerald-50 text-emerald-700',
                        'diproses' => 'bg-blue-50 text-blue-700',
                        'pending' => 'bg-amber-50 text-amber-700',
                        'dibatalkan' => 'bg-red-50 text-red-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <div class="rounded-3xl border border-slate-100 bg-slate-50/70 p-5 shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Topik Konsultasi</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">
                                {{ optional($konsultasi)->topik_konsultasi ?? '-' }}
                            </h3>
                        </div>

                        <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $badgeKonsultasi }}">
                            {{ ucfirst(optional($konsultasi)->status ?? '-') }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Pasien</p>
                            <h4 class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($pasien)->nama_lengkap ?? '-' }}
                            </h4>
                        </div>

                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Ditangani Psikolog</p>
                            <h4 class="mt-1 text-base font-semibold text-slate-900">
                                {{ optional($psikolog)->nama_lengkap ?? '-' }}
                            </h4>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Tanggal</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ optional($konsultasi)->tanggal_konsultasi ? \Carbon\Carbon::parse($konsultasi->tanggal_konsultasi)->format('d-m-Y') : '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Jam</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ optional($konsultasi)->jam_konsultasi ? \Carbon\Carbon::parse($konsultasi->jam_konsultasi)->format('H:i') : '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-sm font-medium text-slate-500">Metode</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                {{ optional($konsultasi)->metode_konsultasi_text ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="xl:col-span-2 rounded-3xl bg-slate-50 p-8 text-center text-sm font-medium text-slate-500">
                    Belum ada data hasil konsultasi pada periode ini.
                </div>
            @endforelse
        </div>
    </div>

    {{-- GRAFIK HASIL KONSULTASI --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2 print:hidden">
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Grafik Status Konsultasi</h2>
            <div class="mt-5 h-[300px]">
                <canvas id="konsultasiChart"></canvas>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">
                Grafik Konsultasi Per Bulan Tahun {{ $tahunGrafik }}
            </h2>
            <div class="mt-5 h-[300px]">
                <canvas id="konsultasiBulananChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pembayaranCanvas = document.getElementById('pembayaranChart');
        const pendapatanBulananCanvas = document.getElementById('pendapatanBulananChart');
        const transaksiBulananCanvas = document.getElementById('transaksiBulananChart');
        const konsultasiCanvas = document.getElementById('konsultasiChart');
        const konsultasiBulananCanvas = document.getElementById('konsultasiBulananChart');

        const bulanLabels = @json($grafikBulanLabels);

        if (pembayaranCanvas) {
            new Chart(pembayaranCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Diterima', 'Pending', 'Gagal'],
                    datasets: [{
                        data: [
                            {{ $totalPembayaranDiterima }},
                            {{ $totalPembayaranPending }},
                            {{ $totalPembayaranGagal }}
                        ],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.85)',
                            'rgba(245, 158, 11, 0.85)',
                            'rgba(220, 38, 38, 0.85)'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        if (pendapatanBulananCanvas) {
            new Chart(pendapatanBulananCanvas, {
                type: 'line',
                data: {
                    labels: bulanLabels,
                    datasets: [
                        {
                            label: 'Total Bayar',
                            data: @json($grafikPendapatanBulanan),
                            borderColor: 'rgba(1, 88, 142, 0.9)',
                            backgroundColor: 'rgba(1, 88, 142, 0.12)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'Biaya Admin',
                            data: @json($grafikAdminBulanan),
                            borderColor: 'rgba(16, 185, 129, 0.9)',
                            backgroundColor: 'rgba(16, 185, 129, 0.10)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'Pendapatan Psikolog',
                            data: @json($grafikPsikologBulanan),
                            borderColor: 'rgba(245, 158, 11, 0.9)',
                            backgroundColor: 'rgba(245, 158, 11, 0.10)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        if (transaksiBulananCanvas) {
            new Chart(transaksiBulananCanvas, {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: @json($grafikTransaksiBulanan),
                        backgroundColor: 'rgba(1, 88, 142, 0.80)',
                        borderRadius: 8,
                        maxBarThickness: 55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        if (konsultasiCanvas) {
            new Chart(konsultasiCanvas, {
                type: 'bar',
                data: {
                    labels: ['Pending', 'Diproses', 'Selesai', 'Dibatalkan'],
                    datasets: [{
                        label: 'Jumlah Konsultasi',
                        data: [
                            {{ $konsultasiPending }},
                            {{ $konsultasiDiproses }},
                            {{ $konsultasiSelesai }},
                            {{ $konsultasiDibatalkan }}
                        ],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.80)',
                            'rgba(37, 99, 235, 0.80)',
                            'rgba(16, 185, 129, 0.80)',
                            'rgba(220, 38, 38, 0.80)'
                        ],
                        borderRadius: 8,
                        maxBarThickness: 70
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        if (konsultasiBulananCanvas) {
            new Chart(konsultasiBulananCanvas, {
                type: 'line',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Jumlah Konsultasi',
                        data: @json($grafikKonsultasiBulanan),
                        borderColor: 'rgba(16, 185, 129, 0.9)',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }
    });
</script>

<style>
    @media print {
        aside,
        nav,
        footer,
        .print\:hidden {
            display: none !important;
        }

        body {
            background: white !important;
        }

        main {
            padding: 0 !important;
        }
    }
</style>

@endsection