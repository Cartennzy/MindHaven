@extends('backend.layouts.app')

@section('title', 'Detail Rujukan Psikiater')

@section('content')

<div class="space-y-6">

    <div class="admin-card p-8">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Detail Rujukan Psikiater
                </h1>

                <p class="text-slate-500 mt-2">
                    Detail surat rujukan yang dibuat oleh psikolog.
                </p>
            </div>

            <span class="admin-badge-success">
                Diterbitkan
            </span>
        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 admin-card p-8">

            <div class="text-center border-b border-slate-200 pb-6 mb-6">
                <h2 class="text-2xl font-bold text-[#01588E]">
                    MindHaven
                </h2>

                <p class="text-slate-500 mt-1">
                    Surat Rujukan Psikiater
                </p>
            </div>

            <div class="space-y-6">

                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Data Pasien
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-sm text-slate-500">Nama Pasien</p>
                            <h4 class="font-semibold text-slate-800 mt-1">Najwan Muyassar</h4>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-sm text-slate-500">Tanggal Rujukan</p>
                            <h4 class="font-semibold text-slate-800 mt-1">12 Mei 2026</h4>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Alasan Rujukan
                    </h3>

                    <p class="text-slate-600 leading-relaxed mt-4">
                        Pasien menunjukkan gejala depresi berat, gangguan tidur,
                        penurunan motivasi, dan membutuhkan evaluasi lanjutan oleh psikiater.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Catatan Psikolog
                    </h3>

                    <p class="text-slate-600 leading-relaxed mt-4">
                        Pasien disarankan melakukan pemeriksaan psikiatri lanjutan
                        dan pemantauan kondisi emosional secara berkala.
                    </p>
                </div>

            </div>

        </div>

        <div class="space-y-6">

            <div class="admin-card p-6">

                <h2 class="text-xl font-bold text-slate-800 mb-6">
                    Informasi Rujukan
                </h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-500">Psikolog Pengirim</p>
                        <h4 class="font-semibold text-slate-800">Psikolog 1</h4>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Psikiater Tujuan</p>
                        <h4 class="font-semibold text-slate-800">dr. Psikiater 1</h4>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Rumah Sakit</p>
                        <h4 class="font-semibold text-slate-800">RS MindHaven Sehat</h4>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Spesialisasi</p>
                        <h4 class="font-semibold text-slate-800">Depresi Berat & Anxiety</h4>
                    </div>
                </div>

            </div>

            <div class="admin-card p-6">
                <button onclick="window.print()" class="admin-btn-primary w-full">
                    Cetak Surat
                </button>

                <a href="{{ route('admin.rujukan.index') }}" class="admin-btn-light w-full mt-3">
                    Kembali
                </a>
            </div>

        </div>

    </div>

</div>

@endsection