@extends('backend.layouts.app')

@section('title', 'Detail Pasien')

@section('content')

<div class="space-y-6">

    <div class="admin-card p-8">

        <div class="flex flex-col lg:flex-row lg:items-center gap-6">

            <div class="w-24 h-24 rounded-3xl bg-[#01588E]/10 text-[#01588E] flex items-center justify-center text-4xl font-bold">
                P
            </div>

            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Najwan Muyassar
                </h1>

                <p class="text-slate-500 mt-2">
                    pasien@gmail.com
                </p>

                <span class="admin-badge-success mt-4">
                    Pasien Aktif
                </span>
            </div>

        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 admin-card p-6">

            <h2 class="text-xl font-bold text-slate-800 mb-6">
                Riwayat Konsultasi
            </h2>

            <div class="space-y-4">

                @for ($i = 1; $i <= 5; $i++)

                <div class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-slate-800">
                                Konsultasi Mental Health
                            </h4>

                            <p class="text-sm text-slate-500 mt-1">
                                Dengan Psikolog {{ $i }} - 12 Mei 2026
                            </p>
                        </div>

                        <span class="admin-badge-success">
                            Selesai
                        </span>
                    </div>
                </div>

                @endfor

            </div>

        </div>

        <div class="admin-card p-6">

            <h2 class="text-xl font-bold text-slate-800 mb-6">
                Informasi Pasien
            </h2>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-500">
                        Umur
                    </p>
                    <h4 class="font-semibold">
                        21 Tahun
                    </h4>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Jenis Kelamin
                    </p>
                    <h4 class="font-semibold">
                        Laki-Laki
                    </h4>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Alamat
                    </p>
                    <h4 class="font-semibold">
                        Bekasi, Indonesia
                    </h4>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection