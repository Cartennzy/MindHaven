@extends('backend.layouts.app')

@section('title', 'Data Konsultasi')

@section('content')

@php
    use Carbon\Carbon;

    $dataKonsultasi = isset($konsultasis) ? $konsultasis : collect();

    $totalKonsultasi = $dataKonsultasi->count();
    $totalSelesai = $dataKonsultasi->where('status', 'selesai')->count();
    $totalProses = $dataKonsultasi->whereIn('status', ['diproses', 'pending'])->count();

    function statusKonsultasiClass($status) {
        return match ($status) {
            'selesai' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'diproses' => 'bg-sky-100 text-sky-700 ring-sky-200',
            'pending' => 'bg-amber-100 text-amber-700 ring-amber-200',
            'dibatalkan' => 'bg-rose-100 text-rose-700 ring-rose-200',
            default => 'bg-slate-100 text-slate-600 ring-slate-200',
        };
    }
@endphp

<div class="space-y-6">

    <section class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-cyan-50 via-white to-blue-50 px-6 py-7 shadow-[0_18px_55px_rgba(15,23,42,.06)] md:px-8 md:py-8">

        <div class="absolute -right-28 -top-28 h-72 w-72 rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-cyan-100/50 blur-3xl"></div>

        <div class="relative">

            <div class="inline-flex items-center gap-3 rounded-full border border-blue-100 bg-white/90 px-4 py-2 text-sm font-medium text-[#01588E] shadow-sm">
                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                Data Konsultasi MindHaven
            </div>

            <h1 class="mt-6 max-w-4xl text-4xl font-semibold leading-tight tracking-tight text-slate-900 md:text-5xl">
                Kelola Data
                <span class="text-[#0284C7]">
                    Konsultasi
                </span>
            </h1>

            <p class="mt-5 max-w-4xl text-base font-normal leading-8 text-slate-600 md:text-lg">
                Pantau seluruh konsultasi pasien dan psikolog yang terdaftar di MindHaven dengan tampilan modern dan terintegrasi langsung dengan sistem konsultasi.
            </p>

            <div class="mt-7 grid max-w-4xl grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-[1.7rem] border border-slate-100 bg-white/90 p-4 shadow-sm backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Total Konsultasi
                    </p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">
                        {{ $totalKonsultasi }} Konsultasi
                    </h3>
                </div>

                <div class="rounded-[1.7rem] border border-slate-100 bg-white/90 p-4 shadow-sm backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Diproses / Pending
                    </p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">
                        {{ $totalProses }} Konsultasi
                    </h3>
                </div>

                <div class="rounded-[1.7rem] border border-slate-100 bg-white/90 p-4 shadow-sm backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Selesai
                    </p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">
                        {{ $totalSelesai }} Konsultasi
                    </h3>
                </div>

            </div>

        </div>

    </section>

    <section class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,.05)] md:p-7">

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">
                    Daftar Konsultasi
                </h2>

                <p class="mt-1 text-sm font-normal text-slate-500">
                    Semua data konsultasi pasien dan psikolog.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.konsultasi.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="Cari konsultasi..."
                    class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#01588E]"
                >

                <select
                    name="status"
                    class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#01588E]"
                >
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <button
                    type="submit"
                    class="rounded-2xl bg-[#01588E] px-5 py-3 text-sm font-medium text-white transition hover:bg-[#01446e]"
                >
                    Filter
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

            @forelse ($dataKonsultasi as $konsultasi)

                @php
                    $pasienNama = $konsultasi->pasien->nama_lengkap
                        ?? $konsultasi->pasien->user->name
                        ?? '-';

                    $psikologNama = $konsultasi->psikolog->nama_lengkap
                        ?? $konsultasi->psikolog->user->name
                        ?? '-';

                    $tanggal = $konsultasi->tanggal_konsultasi
                        ? Carbon::parse($konsultasi->tanggal_konsultasi)->translatedFormat('d F Y')
                        : '-';

                    $jam = $konsultasi->jam_konsultasi
                        ? Carbon::parse($konsultasi->jam_konsultasi)->format('H:i')
                        : '-';

                    $status = $konsultasi->status ?? 'pending';
                    $keluhan = $konsultasi->keluhan ?? '-';
                    $topik = $konsultasi->topik_konsultasi ?? '-';
                    $metode = $konsultasi->metode_konsultasi ?? '-';
                    $harga = number_format($konsultasi->harga ?? 0, 0, ',', '.');
                @endphp

                <div class="rounded-[1.7rem] border border-slate-100 bg-gradient-to-br from-white to-slate-50 p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_20px_45px_rgba(15,23,42,.07)]">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                        <div class="flex items-start gap-4">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#01588E]/10 text-base font-semibold uppercase text-[#01588E]">
                                {{ strtoupper(substr($pasienNama, 0, 1)) }}
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">
                                    {{ $pasienNama }}
                                </h3>

                                <p class="mt-1 text-sm font-normal text-slate-500">
                                    Konsultasi dengan {{ $psikologNama }}
                                </p>

                                <span class="mt-3 inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-medium capitalize ring-1 {{ statusKonsultasiClass($status) }}">
                                    <span class="h-2 w-2 rounded-full bg-current"></span>
                                    {{ $status }}
                                </span>
                            </div>

                        </div>

                        <a href="{{ route('admin.konsultasi.show', $konsultasi->id_konsultasi) }}"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#01588E] px-5 py-3 text-sm font-medium text-white shadow-lg shadow-[#01588E]/20 transition duration-300 hover:bg-[#01446e]">
                            Detail
                        </a>

                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-3">

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Topik</p>
                            <p class="mt-2 line-clamp-1 text-sm font-semibold text-slate-900">
                                {{ $topik }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Tanggal</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $tanggal }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Jam</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $jam }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Metode</p>
                            <p class="mt-2 text-sm font-semibold capitalize text-slate-900">
                                {{ $metode }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Harga</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                Rp {{ $harga }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Keluhan</p>
                            <p class="mt-2 line-clamp-1 text-sm font-semibold text-slate-900">
                                {{ $keluhan }}
                            </p>
                        </div>

                    </div>

                </div>

            @empty

                <div class="rounded-[2rem] border border-dashed border-slate-200 bg-slate-50 p-10 text-center xl:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-800">
                        Belum ada data konsultasi
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Data konsultasi akan muncul setelah pasien melakukan konsultasi.
                    </p>
                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection