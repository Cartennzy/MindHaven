@extends('backend.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

@php
    use Illuminate\Support\Facades\Route;

    $stats = [
        [
            'title' => 'Total Pasien',
            'value' => $totalPasien ?? 0,
            'desc' => 'Data pasien terdaftar',
            'route' => Route::has('admin.pasien.index') ? route('admin.pasien.index') : '#',
            'color' => 'from-blue-600 to-sky-400',
            'shadow' => 'shadow-blue-500/20',
            'iconColor' => 'text-blue-600',
            'iconBg' => 'bg-blue-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/>',
        ],
        [
            'title' => 'Total Psikolog',
            'value' => $totalPsikolog ?? 0,
            'desc' => 'Psikolog terdaftar',
            'route' => Route::has('admin.psikolog.index') ? route('admin.psikolog.index') : '#',
            'color' => 'from-emerald-600 to-teal-400',
            'shadow' => 'shadow-emerald-500/20',
            'iconColor' => 'text-emerald-600',
            'iconBg' => 'bg-emerald-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c4 0 7 2 7 5v1H5v-1c0-3 3-5 7-5zM12 12a4 4 0 100-8 4 4 0 000 8z"/>',
        ],
        [
            'title' => 'Total Psikiater',
            'value' => $totalPsikiater ?? 0,
            'desc' => 'Psikiater terdaftar',
            'route' => Route::has('admin.psikiater.index') ? route('admin.psikiater.index') : '#',
            'color' => 'from-violet-600 to-indigo-400',
            'shadow' => 'shadow-violet-500/20',
            'iconColor' => 'text-violet-600',
            'iconBg' => 'bg-violet-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 106 0 3 3 0 00-6 0zM4 12a8 8 0 1116 0v2a4 4 0 01-4 4h-1"/>',
        ],
        [
            'title' => 'Rumah Sakit',
            'value' => $totalRumahSakit ?? 0,
            'desc' => 'Rumah sakit terdaftar',
            'route' => Route::has('admin.rumah-sakit.index') ? route('admin.rumah-sakit.index') : '#',
            'color' => 'from-orange-500 to-amber-400',
            'shadow' => 'shadow-orange-500/20',
            'iconColor' => 'text-orange-600',
            'iconBg' => 'bg-orange-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21h16M6 21V5a1 1 0 011-1h10a1 1 0 011 1v16M9 9h6M12 6v6"/>',
        ],
        [
            'title' => 'Total Konsultasi',
            'value' => $totalKonsultasi ?? 0,
            'desc' => 'Total konsultasi masuk',
            'route' => Route::has('admin.konsultasi.index') ? route('admin.konsultasi.index') : '#',
            'color' => 'from-cyan-600 to-sky-400',
            'shadow' => 'shadow-cyan-500/20',
            'iconColor' => 'text-cyan-600',
            'iconBg' => 'bg-cyan-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 7l4-4h10a3 3 0 003-3V6a3 3 0 00-3-3H6a3 3 0 00-3 3v11a3 3 0 003 3z"/>',
        ],
        [
            'title' => 'Total Pembayaran',
            'value' => $totalPembayaran ?? 0,
            'desc' => 'Data pembayaran sistem',
            'route' => Route::has('admin.pembayaran.index') ? route('admin.pembayaran.index') : '#',
            'color' => 'from-rose-600 to-pink-400',
            'shadow' => 'shadow-rose-500/20',
            'iconColor' => 'text-rose-600',
            'iconBg' => 'bg-rose-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M5 8V6a2 2 0 012-2h10a2 2 0 012 2v2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>',
        ],
        [
            'title' => 'Total Artikel',
            'value' => $totalArtikel ?? 0,
            'desc' => 'Artikel dipublikasi',
            'route' => Route::has('admin.artikel.index') ? route('admin.artikel.index') : '#',
            'color' => 'from-amber-500 to-yellow-400',
            'shadow' => 'shadow-amber-500/20',
            'iconColor' => 'text-amber-600',
            'iconBg' => 'bg-amber-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v6h5"/>',
        ],
        [
            'title' => 'Total Meditasi',
            'value' => $totalMeditasi ?? 0,
            'desc' => 'Konten meditasi aktif',
            'route' => Route::has('admin.meditasi.index') ? route('admin.meditasi.index') : '#',
            'color' => 'from-purple-600 to-fuchsia-400',
            'shadow' => 'shadow-purple-500/20',
            'iconColor' => 'text-purple-600',
            'iconBg' => 'bg-purple-50',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2 3 4 5 4 9a4 4 0 01-8 0c0-4 2-6 4-9zM5 20h14"/>',
        ],
    ];

    $grafikKonsultasi = [
        'Total Konsultasi' => $totalKonsultasi ?? 0,
        'Konsultasi Selesai' => $konsultasiSelesai ?? 0,
    ];

    $grafikPembayaran = [
        'Pending' => $pembayaranPending ?? 0,
        'Diterima' => $pembayaranDiterima ?? 0,
    ];
@endphp

<div class="space-y-6 max-w-7xl mx-auto pb-12">

    {{-- HERO SUMMARY - UPGRADED SINKRON MATCHING IMAGE_35AD39.PNG --}}
    <div class="relative overflow-hidden rounded-[2.2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.04)] md:p-8">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-slate-50 blur-3xl opacity-60"></div>
        
        <div class="relative z-10 space-y-6">
            {{-- Badge pill atas --}}
            <div class="w-fit">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-[#01588E] border border-emerald-100/50">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Kontrol MindHaven
                </span>
            </div>

            {{-- Judul Utama --}}
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 md:text-4xl">
                    Ringkasan <span class="text-[#01588E]">Aktifitas Sistem</span>
                </h1>

                <p class="mt-2 max-w-4xl text-sm font-medium leading-relaxed text-slate-500">
                    Pantau data pengguna, konsultasi, pembayaran, dan konten MindHaven secara cepat dalam satu tampilan kendali utama.
                </p>
            </div>

            {{-- Row Micro-cards Kecil di Bagian Bawah Sesuai Gambar Referensi --}}
            <div class="flex flex-wrap items-center gap-4 pt-2">
                {{-- Card Tanggal --}}
                <div class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-slate-50/50 px-5 py-3 min-w-[210px] shadow-sm">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#01588E] border border-blue-100/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tanggal Sistem</span>
                        <h4 class="text-sm font-bold text-slate-800 mt-0.5">{{ now()->format('M Y') }}</h4>
                    </div>
                </div>

                {{-- Card Jam Real-time --}}
                <div class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-slate-50/50 px-5 py-3 min-w-[210px] shadow-sm">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Waktu Berjalan</span>
                        <h4 id="realtime-clock" class="text-sm font-bold text-slate-800 mt-0.5">--:--</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN MATRIKS STATS GRID --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        @foreach($stats as $stat)
            <a href="{{ $stat['route'] }}"
               class="group relative min-h-[155px] overflow-hidden rounded-[2rem] bg-gradient-to-br {{ $stat['color'] }} p-6 text-white shadow-lg {{ $stat['shadow'] }} transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl flex flex-col justify-between">

                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl transition duration-500 group-hover:scale-125"></div>

                <div class="relative flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20 text-white border border-white/10 shadow-inner transition duration-300 group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $stat['icon'] !!}
                        </svg>
                    </div>

                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 opacity-0 transition-all duration-300 group-hover:translate-x-1 group-hover:opacity-100">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>

                <div class="relative mt-4">
                    <p class="text-xs font-semibold text-white/80 uppercase tracking-wider">
                        {{ $stat['title'] }}
                    </p>

                    <h2 class="mt-1 text-3xl font-bold tracking-tight text-white drop-shadow-sm">
                        {{ $stat['value'] }}
                    </h2>

                    <p class="mt-1.5 text-xs font-medium text-white/70">
                        {{ $stat['desc'] }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- GRAFIK ANALISIS SECTION --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] xl:col-span-2">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-50 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Grafik Konsultasi</h2>
                    <p class="mt-1 text-xs font-semibold text-slate-400">
                        Rasio volume agregat seluruh janji temu konsultasi psikolog dan selesai.
                    </p>
                </div>

                <span class="rounded-xl bg-blue-50 border border-blue-100 px-3.5 h-8 inline-flex items-center text-xs font-bold text-blue-700 whitespace-nowrap">
                    Konsultasi Sesi
                </span>
            </div>

            <div class="h-[280px]">
                <canvas id="konsultasiChart"></canvas>
            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)]">
            <div class="mb-6 border-b border-slate-50 pb-4">
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Grafik Pembayaran</h2>
                <p class="mt-1 text-xs font-semibold text-slate-400">
                    Perbandingan kuesioner status invoice pending dan diterima faskes.
                </p>
            </div>

            <div class="h-[280px]">
                <canvas id="pembayaranChart"></canvas>
            </div>
        </div>

    </div>

    {{-- LOWER COMPONENT SECTION --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)]">
            <div class="mb-5 flex items-center justify-between border-b border-slate-50 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Statistik Konsultasi</h2>
                    <p class="mt-0.5 text-xs font-semibold text-slate-400">Persentase penutupan sesi medis.</p>
                </div>

                <span class="rounded-xl bg-slate-50 border border-slate-100 px-3 h-7 inline-flex items-center text-[10px] font-bold text-slate-500 uppercase tracking-wide">
                    Real-time
                </span>
            </div>

            @php
                $progressKonsultasi = ($totalKonsultasi ?? 0) > 0
                    ? min(100, (($konsultasiSelesai ?? 0) / ($totalKonsultasi ?? 1)) * 100)
                    : 0;
            @endphp

            <div class="space-y-5 py-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Sesi Konsultasi Selesai</span>
                    <span class="text-lg font-bold text-slate-900">{{ $konsultasiSelesai ?? 0 }}</span>
                </div>

                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100/80 shadow-inner">
                    <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-sky-400 transition-all duration-700"
                         style="width: {{ $progressKonsultasi }}%"></div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Total Akumulasi Masuk</span>
                    <span class="text-lg font-bold text-slate-900">{{ $totalKonsultasi ?? 0 }}</span>
                </div>
            </div>
        </div>

        <a href="{{ Route::has('admin.pembayaran.index') ? route('admin.pembayaran.index') : '#' }}"
           class="group rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Pendapatan Platform</h2>
                    <p class="mt-0.5 text-xs font-semibold text-slate-400">Klaim fee operasional gerbang dana.</p>
                </div>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition duration-300 group-hover:scale-105 border border-emerald-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-3 0-5 1.5-5 4s2 4 5 4 5-1.5 5-4-2-4-5-4zm0 0V5m0 11v3"/>
                    </svg>
                </div>
            </div>

            <div class="mt-5">
                <p class="text-xs font-semibold text-slate-400">Saldo Biaya Admin Bersih</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-emerald-600 whitespace-nowrap">
                    Rp {{ number_format($saldoAdmin ?? 0, 0, ',', '.') }}
                </h2>
            </div>

            <div class="mt-5 rounded-xl bg-slate-50/60 border border-slate-100 p-3 flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Volume Transaksi Sukses</span>
                <span class="text-sm font-bold text-slate-800">{{ $pembayaranDiterima ?? 0 }} Sesi</span>
            </div>
        </a>

        <a href="{{ Route::has('admin.pembayaran.index') ? route('admin.pembayaran.index') : '#' }}"
           class="group rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:border-slate-200 flex flex-col justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight">Status Finansial</h2>
                <p class="mt-0.5 text-xs font-semibold text-slate-400">Peta segmentasi perputaran kas sistem.</p>
            </div>

            <div class="my-3 flex items-center justify-center">
                <div class="flex h-24 w-24 items-center justify-center rounded-full border-[11px] border-slate-50 border-t-blue-600 transition duration-500 group-hover:rotate-6 shadow-sm">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $totalPembayaran ?? 0 }}</h3>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Total</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-2">
                <div class="flex items-center justify-between rounded-xl bg-amber-50 border border-amber-100 px-3 py-2">
                    <span class="text-xs font-bold text-amber-700">Pending</span>
                    <span class="text-xs font-bold text-slate-800">{{ $pembayaranPending ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-emerald-50 border border-emerald-100 px-3 py-2">
                    <span class="text-xs font-bold text-emerald-700">Diterima</span>
                    <span class="text-xs font-bold text-slate-800">{{ $pembayaranDiterima ?? 0 }}</span>
                </div>
            </div>
        </a>

    </div>
</div>

{{-- SCRIPT RENDERING CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        const clock = document.getElementById('realtime-clock');
        if (clock) {
            clock.textContent = time + ' WIB';
        }
    }

    updateClock();
    setInterval(updateClock, 1000);

    document.addEventListener('DOMContentLoaded', function () {
        const konsultasiCanvas = document.getElementById('konsultasiChart');
        const pembayaranCanvas = document.getElementById('pembayaranChart');

        if (konsultasiCanvas) {
            new Chart(konsultasiCanvas, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($grafikKonsultasi)) !!},
                    datasets: [{
                        label: 'Jumlah',
                        data: {!! json_encode(array_values($grafikKonsultasi)) !!},
                        backgroundColor: [
                            'rgba(14, 165, 233, 0.85)',
                            'rgba(16, 185, 129, 0.85)'
                        ],
                        borderColor: [
                            '#0284C7',
                            '#10B981'
                        ],
                        borderWidth: 2,
                        borderRadius: 12,
                        maxBarThickness: 60
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#061A33', padding: 12, cornerRadius: 12 }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748B', font: { weight: '600', size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148, 163, 184, 0.12)' },
                            ticks: { precision: 0, color: '#64748B', font: { weight: '600', size: 11 } }
                        }
                    }
                }
            });
        }

        if (pembayaranCanvas) {
            new Chart(pembayaranCanvas, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($grafikPembayaran)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($grafikPembayaran)) !!},
                        backgroundColor: [
                            '#F59E0B',
                            '#10B981'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 5,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#475569',
                                padding: 16,
                                font: { weight: '600', size: 11 }
                            }
                        },
                        tooltip: { backgroundColor: '#061A33', padding: 12, cornerRadius: 12 }
                    }
                }
            });
        }
    });
</script>

@endsection