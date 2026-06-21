@extends('frontend.layouts.psikolog')

@section('title', 'Buat Surat Rujukan')
@section('page-title', 'Buat Surat Rujukan')

@section('content')

@php
    $selectedKonsultasiId = old('konsultasi_id');
@endphp

<div class="space-y-6 max-w-7xl mx-auto pb-12">

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-600 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- TOP HERO BANNER HEADER CARD --}}
    <div class="relative overflow-hidden rounded-[2.2rem] bg-gradient-to-r from-[#061A33] via-[#01588E] to-[#12B76A] px-7 py-9 shadow-[0_24px_70px_rgba(15,23,42,0.12)] md:px-9 md:py-11">
        <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wider text-white/75 uppercase">
                    Rujukan Psikiater
                </p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-white lg:text-4xl leading-none">
                    Buat Surat Rujukan Psikiater
                </h1>
                <p class="mt-4 max-w-2xl text-sm font-medium leading-relaxed text-white/80">
                    Pilih konsultasi pasien, tentukan psikiater tujuan, lalu lengkapi rincian data klinis rujukan secara manual.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('psikolog.rujukan-psikiater.index') }}"
                   class="inline-flex h-12 items-center gap-2 rounded-2xl bg-white/15 px-5 text-sm font-bold text-white backdrop-blur border border-white/10 shadow-inner transition hover:bg-white/20 hover:-translate-y-0.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- INTERACTIVE FORM CONTAINER --}}
    <form action="{{ route('psikolog.rujukan-psikiater.store') }}"
          method="POST"
          id="rujukanForm"
          autocomplete="off"
          class="rounded-[2.5rem] border border-blue-100/50 bg-gradient-to-b from-[#F5F9FD] to-[#EDF4FA] p-6 shadow-[0_20px_6060px_rgba(1,88,142,0.03)] md:p-8"
          novalidate>
        @csrf

        {{-- Hidden Input untuk data relasi Rumah Sakit --}}
        <input type="hidden"
               name="id_rumahsakit"
               id="rumahSakitInput"
               value="{{ old('id_rumahsakit') }}">

        <div class="space-y-8">
            
            {{-- SECTION 1: PILIH KONSULTASI PASIEN --}}
            <div class="bg-white/80 border border-white p-6 rounded-[2rem] shadow-sm max-w-full overflow-hidden">
                <div class="mb-4">
                    <label class="block text-base font-bold text-[#061A33] tracking-tight">
                        Pilih Konsultasi Pasien
                    </label>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">
                        Daftar riwayat sesi konsultasi aktif yang membutuhkan tindak lanjut medis eksternal.
                    </p>
                </div>

                <div class="relative w-full max-w-full">
                    {{-- FIX: Menambahkan overflow-hidden dan max-w-full agar dropdown patuh pada grid kontainer --}}
                    <select id="konsultasiSelect"
                            name="konsultasi_id"
                            autocomplete="off"
                            class="w-full max-w-full appearance-none rounded-2xl border {{ $errors->has('konsultasi_id') ? 'border-red-400 bg-red-50/50' : 'border-blue-100 bg-slate-50/50' }} px-5 py-4 text-sm font-semibold text-slate-800 outline-none transition focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10 pr-12">
                        <option value="">Pilih data riwayat sesi konsultasi...</option>

                        @foreach($konsultasis as $konsultasi)
                            @php
                                $detail = $konsultasi->detailKonsultasi;

                                $pasienNama = optional($konsultasi->pasien)->nama_lengkap
                                    ?? optional(optional($konsultasi->pasien)->user)->name
                                    ?? 'Pasien';

                                $diagnosa = $detail->diagnosis_awal ?? '';
                                
                                // Membuat string teks opsi bawaan
                                $optionText = "Nama: " . $pasienNama . " — Sesi Tgl: " . \Carbon\Carbon::parse($konsultasi->tanggal_konsultasi)->translatedFormat('d/m/Y') . " — Diagnosa: " . $diagnosa;
                            @endphp

                            @if(!empty($diagnosa))
                                <option value="{{ $konsultasi->id_konsultasi }}"
                                        {{ (string) $selectedKonsultasiId === (string) $konsultasi->id_konsultasi ? 'selected' : '' }}
                                        class="truncate">
                                    {{-- FIX: Membatasi panjang karakter teks di dalam opsi select agar tidak meluap keluar batas layar --}}
                                    {{ \Illuminate\Support\Str::limit($optionText, 95, '...') }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                @error('konsultasi_id')
                    <p class="mt-2.5 text-xs font-bold text-rose-500 flex items-center gap-1">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- SECTION 2: PILIH PSIKIATER TUJUAN --}}
            <div class="bg-white/80 border border-white p-6 rounded-[2rem] shadow-sm">
                <div class="mb-5">
                    <label class="block text-base font-bold text-[#061A33] tracking-tight">
                        Pilih Psikiater & Rumah Sakit Tujuan
                    </label>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">
                        Tentukan salah satu psikiater spesialis eksternal yang terintegrasi berdasarkan rekam kompetensi praktis.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    @forelse($psikiaters as $psikiater)
                        @php
                            $rumahSakit = $psikiater->rumahSakit;

                            $namaRs = $rumahSakit->nama_rumahsakit ?? 'Rumah sakit belum tersedia';
                            $alamatRs = $rumahSakit->alamat ?? '-';
                            $teleponRs = $rumahSakit->no_telepon ?? '-';
                            $websiteRs = $rumahSakit->website ?? null;

                            $fotoPsikiater = $psikiater->foto_profil
                                ? asset('storage/' . $psikiater->foto_profil)
                                : asset('assets/images/default-doctor.png');

                            $fotoRs = optional($rumahSakit)->foto
                                ? asset('storage/' . $rumahSakit->foto)
                                : asset('assets/images/default-hospital.png');
                        @endphp

                        <label class="group relative block cursor-pointer rounded-2xl border {{ $errors->has('psikiater_id') || $errors->has('id_rumahsakit') ? 'border-rose-200 bg-rose-50/10' : 'border-blue-100 bg-white' }} p-5 shadow-sm transition duration-300 hover:border-[#01588E]/40 hover:bg-[#01588E]/[0.01] hover:shadow-[0_15px_40px_rgba(1,88,142,0.05)]">
                            
                            <input type="radio"
                                   name="psikiater_id"
                                   value="{{ $psikiater->id_psikiater }}"
                                   data-rumahsakit="{{ $psikiater->id_rumahsakit }}"
                                   class="peer sr-only psikiater-radio"
                                   {{ old('psikiater_id') == $psikiater->id_psikiater ? 'checked' : '' }}>

                            {{-- CHECK INDICATOR SAAS ICON GLOW --}}
                            <div class="absolute right-5 top-5 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-200 bg-white transition duration-200 peer-checked:border-[#01588E] peer-checked:bg-[#01588E]">
                                <svg class="h-3.5 w-3.5 text-white transition scale-0 peer-checked:scale-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            {{-- ACTIVE GLOW STATE LAYOUT OVERLAY --}}
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent transition pointer-events-none peer-checked:border-[#01588E]/80 peer-checked:shadow-[0_0_20px_rgba(1,88,142,0.1)]"></div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-[100px_1fr]">
                                <div class="shrink-0 mx-auto md:mx-0">
                                    <div class="h-[100px] w-[100px] overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 shadow-inner">
                                        <img src="{{ $fotoPsikiater }}"
                                             alt="{{ $psikiater->nama_lengkap }}"
                                             class="h-full w-full object-cover">
                                    </div>
                                </div>

                                <div class="md:pr-8">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-bold text-[#061A33]">
                                            {{ $psikiater->nama_lengkap }}
                                        </h3>
                                        <span class="inline-flex items-center rounded-full bg-[#12B76A]/10 px-2.5 py-0.5 text-xs font-bold text-[#12B76A]">
                                            {{ $psikiater->pengalaman ?? 0 }} Tahun Pengalaman
                                        </span>
                                    </div>

                                    <p class="mt-0.5 text-xs font-bold text-[#01588E] tracking-wide uppercase">
                                        {{ $psikiater->spesialisasi ?? 'Spesialis Kedokteran Jiwa' }}
                                    </p>

                                    <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
                                        {{-- DATA PERSONAL PSIKIATER --}}
                                        <div class="rounded-xl bg-slate-50/80 border border-slate-100 p-4">
                                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Informasi STR & Praktik</span>
                                            <div class="space-y-1.5 text-xs font-semibold text-slate-600">
                                                <p><span class="text-slate-400 font-medium">No STR:</span> {{ $psikiater->str_psikiater ?? '-' }}</p>
                                                <p><span class="text-slate-400 font-medium">Telepon:</span> {{ $psikiater->no_telepon ?? '-' }}</p>
                                                <p class="truncate"><span class="text-slate-400 font-medium">Email:</span> {{ $psikiater->email ?? '-' }}</p>
                                                <p><span class="text-slate-400 font-medium">Jadwal:</span> {{ $psikiater->jadwal_praktik ?? '-' }}</p>
                                                <p class="truncate"><span class="text-slate-400 font-medium">Alamat:</span> {{ $psikiater->alamat_praktik ?? '-' }}</p>
                                            </div>
                                        </div>

                                        {{-- DATA RUMAH SAKIT MITRA --}}
                                        <div class="rounded-xl bg-blue-50/40 border border-blue-100/60 p-4">
                                            <div class="flex items-start gap-3">
                                                <div class="h-11 w-11 shrink-0 overflow-hidden rounded-xl border border-white bg-white shadow-sm">
                                                    <img src="{{ $fotoRs }}"
                                                         alt="{{ $namaRs }}"
                                                         class="h-full w-full object-cover">
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#01588E]/70 block">Fasilitas Kesehatan Utama</span>
                                                    <h4 class="mt-0.5 text-xs font-bold text-[#061A33] truncate">
                                                        {{ $namaRs }}
                                                    </h4>
                                                </div>
                                            </div>

                                            <div class="mt-3 space-y-1 text-xs font-semibold text-slate-600">
                                                <p class="line-clamp-2"><span class="text-slate-400 font-medium">Lokasi:</span> {{ $alamatRs }}</p>
                                                <p><span class="text-slate-400 font-medium">Kontak RS:</span> {{ $teleponRs }}</p>
                                                @if($websiteRs)
                                                    <p class="truncate text-[#01588E]"><span class="text-slate-400 font-medium">Web:</span> {{ $websiteRs }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-4 py-8 text-center text-sm font-semibold text-slate-400">
                            Belum ada data jaringan psikiater rujukan yang aktif saat ini.
                        </div>
                    @endforelse
                </div>

                @error('psikiater_id')
                    <p class="mt-2.5 text-xs font-bold text-rose-500 flex items-center gap-1">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        {{ $message }}
                    </p>
                @enderror

                @error('id_rumahsakit')
                    <p class="mt-1 text-xs font-bold text-rose-500 flex items-center gap-1">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- SECTION 3: DETAIL SUBMISI KLINIS --}}
            <div class="bg-white/80 border border-white p-6 rounded-[2rem] shadow-sm">
                <div class="mb-6">
                    <label class="block text-base font-bold text-[#061A33] tracking-tight">
                        Detail Informasi Klinis Rujukan
                    </label>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">
                        Lengkapi diagnosis, paramedik, beserta catatan esensial demi menunjang keakuratan proses terapi psikiatri lanjutan.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- TANGGAL RUJUKAN --}}
                    <div>
                        <label class="mb-2 block text-xs font-bold text-[#061A33] uppercase tracking-wider">
                            Tanggal Penerbitan Surat
                        </label>
                        <input type="date"
                               name="tanggal_rujukan"
                               value="{{ old('tanggal_rujukan', date('Y-m-d')) }}"
                               autocomplete="off"
                               class="w-full rounded-2xl border {{ $errors->has('tanggal_rujukan') ? 'border-red-400 bg-red-50/50' : 'border-blue-100 bg-slate-50/50' }} px-5 py-4 text-sm font-semibold text-slate-800 outline-none transition focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10">
                        @error('tanggal_rujukan')
                            <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DIAGNOSIS AWAL --}}
                    <div>
                        <label class="mb-2 block text-xs font-bold text-[#061A33] uppercase tracking-wider">
                            Diagnosis Awal Pasien
                        </label>
                        <input id="diagnosaAwal"
                               type="text"
                               name="diagnosa_awal"
                               value="{{ old('diagnosa_awal') }}"
                               autocomplete="off"
                               placeholder="Misal: Gangguan depresi berat dengan gejala psikotik"
                               class="w-full rounded-2xl border {{ $errors->has('diagnosa_awal') ? 'border-red-400 bg-red-50/50' : 'border-blue-100 bg-slate-50/50' }} px-5 py-4 text-sm font-semibold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10">
                        @error('diagnosa_awal')
                            <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ALASAN RUJUKAN --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-bold text-[#061A33] uppercase tracking-wider">
                            Alasan Utama Rujukan
                        </label>
                        <textarea id="alasanRujukan"
                                  name="alasan_rujukan"
                                  rows="4"
                                  autocomplete="off"
                                  placeholder="Deskripsikan indikasi klinis utama mengapa kondisi pasien memerlukan penanganan farmakoterapi oleh psikiater..."
                                  class="w-full resize-none rounded-2xl border {{ $errors->has('alasan_rujukan') ? 'border-red-400 bg-red-50/50' : 'border-blue-100 bg-slate-50/50' }} px-5 py-4 text-sm font-semibold leading-relaxed text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10">{{ old('alasan_rujukan') }}</textarea>
                        @error('alasan_rujukan')
                            <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CATATAN RUJUKAN --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-bold text-[#061A33] uppercase tracking-wider">
                            Catatan Rekomendasi / Intervensi Tambahan
                        </label>
                        <textarea id="catatanRujukan"
                                  name="catatan_rujukan"
                                  rows="4"
                                  autocomplete="off"
                                  placeholder="Tambahkan riwayat alergi obat, hasil tes psikometri esensial, atau instruksi kerja penunjang medik lainnya jika ada..."
                                  class="w-full resize-none rounded-2xl border {{ $errors->has('catatan_rujukan') ? 'border-red-400 bg-red-50/50' : 'border-blue-100 bg-slate-50/50' }} px-5 py-4 text-sm font-semibold leading-relaxed text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10">{{ old('catatan_rujukan') }}</textarea>
                        @error('catatan_rujukan')
                            <p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

        </div>

        {{-- ACTIONS BUTTON ROW --}}
        <div class="mt-8 flex flex-col gap-3 border-t border-slate-200/60 pt-6 sm:flex-row sm:items-center sm:justify-end">
            <a href="{{ route('psikolog.rujukan-psikiater.index') }}"
               class="inline-flex h-14 items-center justify-center rounded-2xl bg-white border border-blue-100 px-7 text-sm font-bold text-slate-600 shadow-sm transition hover:bg-slate-50">
                Batal
            </a>

            <button type="submit"
                    class="inline-flex h-14 items-center justify-center rounded-2xl bg-[#01588E] px-7 text-sm font-bold text-white shadow-[0_12px_30px_rgba(1,88,142,0.20)] transition hover:bg-[#004775] hover:shadow-[0_16px_40px_rgba(1,88,142,0.28)]">
                Terbitkan Rujukan Resmi
            </button>
        </div>

    </form>
</div>

{{-- SweetAlert2 Validation Script Integration --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('rujukanForm');
        const konsultasiSelect = document.getElementById('konsultasiSelect');
        const diagnosaAwal = document.getElementById('diagnosaAwal');
        const alasanRujukan = document.getElementById('alasanRujukan');
        const catatanRujukan = document.getElementById('catatanRujukan');
        const rumahSakitInput = document.getElementById('rumahSakitInput');
        const psikiaterRadios = document.querySelectorAll('.psikiater-radio');

        function showMindHavenAlert(title, text) {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#1E293B',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold px-5 py-3 text-sm'
                }
            });
        }

        function isiRumahSakit() {
            const checked = document.querySelector('.psikiater-radio:checked');

            if (!checked) {
                rumahSakitInput.value = '';
                return;
            }

            rumahSakitInput.value = checked.dataset.rumahsakit || '';
        }

        function kosongkanFieldRujukanSaatAwal() {
            const hasOldDiagnosa = @json(old('diagnosa_awal') !== null);
            const hasOldAlasan = @json(old('alasan_rujukan') !== null);
            const hasOldCatatan = @json(old('catatan_rujukan') !== null);

            if (!hasOldDiagnosa) {
                diagnosaAwal.value = '';
            }

            if (!hasOldAlasan) {
                alasanRujukan.value = '';
            }

            if (!hasOldCatatan) {
                catatanRujukan.value = '';
            }
        }

        konsultasiSelect.addEventListener('change', function () {
            diagnosaAwal.value = '';
            alasanRujukan.value = '';
            catatanRujukan.value = '';
        });

        psikiaterRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                isiRumahSakit();
            });
        });

        if (form) {
            form.addEventListener('submit', function (event) {
                isiRumahSakit();

                if (!konsultasiSelect.value) {
                    event.preventDefault();
                    showMindHavenAlert('Konsultasi Belum Dipilih', 'Silakan pilih konsultasi pasien terlebih dahulu.');
                    return;
                }

                const checkedPsikiater = document.querySelector('.psikiater-radio:checked');

                if (!checkedPsikiater) {
                    event.preventDefault();
                    showMindHavenAlert('Psikiater Belum Dipilih', 'Silakan pilih psikiater tujuan terlebih dahulu.');
                    return;
                }

                if (!rumahSakitInput.value) {
                    event.preventDefault();
                    showMindHavenAlert('Rumah Sakit Belum Dipilih', 'Rumah sakit wajib dipilih melalui data psikiater tujuan.');
                    return;
                }

                if (!diagnosaAwal.value.trim()) {
                    event.preventDefault();
                    showMindHavenAlert('Diagnosis Awal Kosong', 'Diagnosis awal wajib diisi.');
                    return;
                }

                if (!alasanRujukan.value.trim()) {
                    event.preventDefault();
                    showMindHavenAlert('Alasan Rujukan Kosong', 'Alasan rujukan wajib diisi.');
                    return;
                }
            });
        }

        kosongkanFieldRujukanSaatAwal();
        isiRumahSakit();
    });
</script>

@endsection