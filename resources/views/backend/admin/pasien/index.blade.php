@extends('backend.layouts.app')

@section('title', 'Data Pasien')

@section('content')

<div class="space-y-7">

    {{-- HERO --}}
    <div class="relative overflow-hidden rounded-[32px] border border-slate-200/70 bg-gradient-to-br from-cyan-50 via-white to-blue-50 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.06)] md:p-7">

        <div class="absolute -left-20 top-0 h-56 w-56 rounded-full bg-cyan-100/40 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-64 w-64 rounded-full bg-blue-100/30 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">

            <div class="max-w-2xl">

                <div class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white/80 px-4 py-2 text-xs font-semibold text-[#01588E] shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Data Pasien MindHaven
                </div>

                <h1 class="mt-5 text-3xl font-semibold leading-tight tracking-tight text-slate-900 md:text-4xl">
                    Kelola Data
                    <span class="bg-gradient-to-r from-[#01588E] to-sky-500 bg-clip-text text-transparent">
                        Pasien
                    </span>
                </h1>

                <p class="mt-4 text-[15px] font-medium leading-7 text-slate-600">
                    Pantau seluruh akun pasien yang terdaftar di MindHaven dengan tampilan modern dan terintegrasi langsung dengan sistem konsultasi.
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
                                Total Pasien
                            </p>

                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $pasiens->count() }} Pasien
                            </h3>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- DATA PASIEN CARD --}}
    @if($pasiens->count() > 0)

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

            @foreach ($pasiens as $pasien)

                @php
                    $nama = $pasien->nama_lengkap ?? 'Pasien';
                    $initial = strtoupper(substr($nama, 0, 1));
                    $jenisKelamin = $pasien->jenis_kelamin ? ucfirst($pasien->jenis_kelamin) : '-';

                    /*
                    |--------------------------------------------------------------------------
                    | ROUTE PARAMETER FIX
                    |--------------------------------------------------------------------------
                    | Route admin.pasien.show menggunakan parameter {pasien}.
                    | Jadi wajib dikirim ID pasien.
                    | Struktur MindHaven biasanya memakai id_pasien.
                    | Fallback ke id dibuat agar tetap aman jika tabel memakai id biasa.
                    */
                    $pasienId = $pasien->id_pasien ?? $pasien->id ?? null;
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
                                    Akun pasien terdaftar MindHaven
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-blue-50 px-3.5 py-1.5 text-xs font-semibold text-blue-700">
                                        {{ $jenisKelamin }}
                                    </span>

                                    <span class="rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-semibold text-emerald-700">
                                        Terdaftar
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($pasienId)
                            <a href="{{ route('admin.pasien.show', ['pasien' => $pasienId]) }}"
                               class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#01446e]">
                                Detail
                            </a>
                        @else
                            <button type="button"
                                    disabled
                                    class="inline-flex cursor-not-allowed items-center justify-center rounded-2xl bg-slate-300 px-5 py-2.5 text-sm font-semibold text-white">
                                Detail
                            </button>
                        @endif

                    </div>

                    <div class="relative mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                Email
                            </p>
                            <p class="mt-2 break-all text-sm font-medium text-slate-700">
                                {{ $pasien->user->email ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-[22px] border border-slate-100 bg-slate-50/80 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                                No HP
                            </p>
                            <p class="mt-2 text-sm font-medium text-slate-700">
                                {{ $pasien->no_telepon ?? '-' }}
                            </p>
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
                Belum ada data pasien.
            </h2>

            <p class="mt-2 text-sm font-medium text-slate-500">
                Data pasien akan muncul setelah pasien melakukan pendaftaran mandiri.
            </p>
        </div>

    @endif

</div>

@endsection