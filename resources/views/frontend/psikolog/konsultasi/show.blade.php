@extends('frontend.layouts.psikolog')

@section('title', 'Detail Konsultasi')
@section('page-title', 'Detail Konsultasi')

@section('content')

@php
    $pasien = $konsultasi->pasien;
    $userPasien = $pasien->user ?? null;
    $namaPasien = $pasien->nama_lengkap ?? $userPasien->name ?? 'Pasien';
    $emailPasien = $userPasien->email ?? '-';
    $detail = $konsultasi->detailKonsultasi;
    $rujukan = $konsultasi->rujukanPsikiater ?? null;

    $idRujukan = $rujukan->id_rujukan
        ?? $rujukan->id_rujukan_psikiater
        ?? $rujukan->id
        ?? null;

    $modeHasil = request('mode') === 'hasil' || $detail;
    $status = strtolower($konsultasi->status ?? 'pending');

    $statusText = match ($status) {
        'pending' => 'Pending',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        default => ucfirst($status),
    };

    $statusClass = match ($status) {
        'pending' => 'bg-[#FFF7E8] text-[#B58105]',
        'diproses' => 'bg-[#E8F1FF] text-[#155EEF]',
        'selesai' => 'bg-[#E9FBEF] text-[#12B76A]',
        'dibatalkan' => 'bg-[#FEE4E2] text-[#D92D20]',
        default => 'bg-slate-100 text-slate-600',
    };

    $metodeText = match ($konsultasi->metode_konsultasi ?? null) {
        'chat' => 'Chat',
        'video_call' => 'Video Call',
        'temu_janji' => 'Temu Janji',
        'online' => 'Chat',
        'offline' => 'Temu Janji',
        'video' => 'Video Call',
        'tatap_muka' => 'Temu Janji',
        default => '-',
    };
@endphp

<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-2xl bg-[#E9FBEF] px-5 py-4 text-sm font-semibold text-[#12B76A]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl bg-[#FEE4E2] px-5 py-4 text-sm font-semibold text-[#D92D20]">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.06)]">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EEF4FF] text-xl font-semibold text-[#155EEF]">
                    {{ strtoupper(substr($namaPasien, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-2xl font-semibold text-[#061A33]">
                        {{ $namaPasien }}
                    </h2>
                    <p class="mt-1 text-sm font-medium text-slate-400">
                        {{ $emailPasien }}
                    </p>
                </div>
            </div>

            <span class="w-fit rounded-2xl px-5 py-3 text-sm font-semibold {{ $statusClass }}">
                {{ $statusText }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="rounded-[1.8rem] bg-white p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)] xl:col-span-1">
            <h3 class="text-xl font-semibold text-[#061A33]">
                Detail Keluhan Konsultasi
            </h3>

            <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                Cek dulu data pasien sebelum memulai atau menyelesaikan sesi konsultasi.
            </p>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl bg-[#F7FAFC] p-4">
                    <p class="text-sm font-medium text-slate-500">Topik Konseling</p>
                    <h3 class="mt-1 font-semibold text-[#061A33]">
                        {{ $konsultasi->topik_konsultasi ?? $konsultasi->topik_konseling ?? '-' }}
                    </h3>
                </div>

                <div class="rounded-2xl bg-[#F7FAFC] p-4">
                    <p class="text-sm font-medium text-slate-500">Metode Konsultasi</p>
                    <h3 class="mt-1 font-semibold text-[#061A33]">
                        {{ $metodeText }}
                    </h3>
                </div>

                <div class="rounded-2xl bg-[#F7FAFC] p-4">
                    <p class="text-sm font-medium text-slate-500">Tanggal Konsultasi</p>
                    <h3 class="mt-1 font-semibold text-[#061A33]">
                        {{ $konsultasi->tanggal_konsultasi ?? '-' }}
                    </h3>
                </div>

                <div class="rounded-2xl bg-[#F7FAFC] p-4">
                    <p class="text-sm font-medium text-slate-500">Jam Konsultasi</p>
                    <h3 class="mt-1 font-semibold text-[#061A33]">
                        {{ $konsultasi->jam_konsultasi ?? '-' }}
                    </h3>
                </div>

                <div class="rounded-2xl bg-[#F7FAFC] p-4">
                    <p class="text-sm font-medium text-slate-500">Keluhan Pasien</p>
                    <p class="mt-2 text-sm font-medium leading-6 text-[#061A33]">
                        {{ $konsultasi->keluhan ?? 'Belum ada keluhan.' }}
                    </p>
                </div>

                @if($rujukan)
                    <div class="rounded-2xl bg-[#E9FBEF] p-4">
                        <p class="text-sm font-medium text-[#12B76A]">Rujukan</p>
                        <p class="mt-1 text-sm font-semibold text-[#12B76A]">
                            Surat rujukan sudah dibuat.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-[1.8rem] bg-white p-6 shadow-[0_14px_40px_rgba(15,23,42,0.06)] xl:col-span-2">

            @if(!$modeHasil)

                <h2 class="text-2xl font-semibold text-[#061A33]">
                    Persiapan Sesi Konsultasi
                </h2>

                <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                    Detail konsultasi sudah tampil. Mulai sesi terlebih dahulu sebelum mengisi hasil konsultasi.
                </p>

                <div class="mt-6 rounded-3xl border border-[#155EEF]/10 bg-[#EEF4FF] p-6">
                    <h3 class="text-lg font-semibold text-[#061A33]">
                        Status Sesi
                    </h3>

                    @if($status === 'pending')
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-600">
                            Konsultasi masih pending. ACC dulu konsultasi ini agar sesi bisa dimulai.
                        </p>

                        <form action="{{ route('psikolog.konsultasi.accept', ['konsultasi' => $konsultasi->id_konsultasi]) }}" method="POST" class="mt-5">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    onclick="return confirm('ACC konsultasi ini?')"
                                    class="rounded-2xl bg-[#12B76A] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#0E9F5B]">
                                ACC Konsultasi
                            </button>
                        </form>
                    @elseif($status === 'diproses')
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-600">
                            Konsultasi sudah diproses. Klik tombol di bawah untuk masuk ke sesi konsultasi.
                        </p>

                        <a href="{{ route('psikolog.konsultasi.sesi', ['konsultasi' => $konsultasi->id_konsultasi]) }}"
                           class="mt-5 inline-flex rounded-2xl bg-[#155EEF] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#0A58CA]">
                            Mulai Sesi
                        </a>
                    @elseif($status === 'selesai')
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-600">
                            Konsultasi sudah selesai. Hasil konsultasi dapat dilihat di bawah.
                        </p>

                        <a href="{{ route('psikolog.konsultasi.show', ['konsultasi' => $konsultasi->id_konsultasi]) }}?mode=hasil"
                           class="mt-5 inline-flex rounded-2xl bg-[#061A33] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#01588E]">
                            Lihat Hasil Konsultasi
                        </a>
                    @else
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-600">
                            Konsultasi dibatalkan atau tidak aktif.
                        </p>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('psikolog.konsultasi.index') }}"
                       class="inline-flex rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">
                        Kembali
                    </a>
                </div>

            @else

                <h2 class="text-2xl font-semibold text-[#061A33]">
                    Isi Hasil Konsultasi
                </h2>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Isi hasil konsultasi pasien setelah sesi dilakukan.
                </p>

                <form action="{{ route('psikolog.konsultasi.update', ['konsultasi' => $konsultasi->id_konsultasi]) }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#061A33]">Catatan Konsultasi</label>
                        <textarea name="catatan" rows="4" class="w-full rounded-2xl border border-slate-200 bg-[#F7FAFC] px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('catatan', optional($detail)->catatan ?? optional($detail)->keluhan_utama ?? '') }}</textarea>
                        @error('catatan') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#061A33]">Observasi</label>
                        <textarea name="observasi" rows="4" class="w-full rounded-2xl border border-slate-200 bg-[#F7FAFC] px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('observasi', optional($detail)->observasi ?? optional($detail)->hasil_observasi ?? '') }}</textarea>
                        @error('observasi') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#061A33]">Diagnosa</label>
                        <textarea name="diagnosa" rows="4" class="w-full rounded-2xl border border-slate-200 bg-[#F7FAFC] px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('diagnosa', optional($detail)->diagnosa ?? optional($detail)->diagnosis_awal ?? '') }}</textarea>
                        @error('diagnosa') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#061A33]">Saran Terapi</label>
                        <textarea name="saran_terapi" rows="4" class="w-full rounded-2xl border border-slate-200 bg-[#F7FAFC] px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('saran_terapi', optional($detail)->saran_terapi ?? optional($detail)->rencana_penanganan ?? '') }}</textarea>
                        @error('saran_terapi') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#061A33]">Tindak Lanjut</label>
                        <textarea name="tindak_lanjut" rows="4" class="w-full rounded-2xl border border-slate-200 bg-[#F7FAFC] px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('tindak_lanjut', optional($detail)->tindak_lanjut ?? optional($detail)->laporan_asesmen_psikologis ?? '') }}</textarea>
                        @error('tindak_lanjut') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#061A33]">Status Konsultasi</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-[#F7FAFC] px-5 py-4 text-sm font-semibold outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">
                            <option value="pending" {{ old('status', $konsultasi->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ old('status', $konsultasi->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status', $konsultasi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $konsultasi->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex items-start gap-4 rounded-2xl border border-[#41AD01]/20 bg-[#41AD01]/5 p-5">
                        <input type="checkbox"
                               name="perlu_rujukan"
                               value="1"
                               class="mt-1 h-5 w-5 rounded border-slate-300 text-[#41AD01]"
                               {{ old('perlu_rujukan', optional($detail)->perlu_rujukan ?? false) ? 'checked' : '' }}>

                        <div>
                            <p class="text-sm font-semibold text-[#061A33]">Pasien perlu surat rujukan psikiater</p>
                            <p class="mt-1 text-sm text-slate-500">
                                Centang jika pasien membutuhkan penanganan lanjutan ke psikiater.
                            </p>
                        </div>
                    </label>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('psikolog.konsultasi.index') }}"
                           class="rounded-2xl bg-slate-100 px-6 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-200">
                            Kembali
                        </a>

                        <button type="submit"
                                class="rounded-2xl bg-[#061A33] px-6 py-3 text-sm font-semibold text-white shadow-[0_14px_35px_rgba(6,26,51,0.18)] transition hover:bg-[#155EEF]">
                            Simpan Hasil Konsultasi
                        </button>
                    </div>
                </form>

                @if($detail && ($detail->perlu_rujukan ?? false) && !$rujukan)
                    <div class="mt-6 rounded-3xl border border-[#41AD01]/20 bg-[#41AD01]/5 p-6">
                        <h3 class="text-lg font-semibold text-[#061A33]">
                            Pasien Memerlukan Surat Rujukan
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Hasil konsultasi sudah disimpan dan pasien ditandai perlu rujukan. Silakan buat surat rujukan psikiater.
                        </p>

                        <a href="{{ route('psikolog.rujukan-psikiater.create', ['konsultasi' => $konsultasi->id_konsultasi]) }}"
                           class="mt-4 inline-flex rounded-2xl bg-[#41AD01] px-5 py-3 text-sm font-semibold text-white">
                            Buat Surat Rujukan
                        </a>
                    </div>
                @endif

                @if($detail && ($detail->perlu_rujukan ?? false) && $rujukan && $idRujukan)
                    <div class="mt-6 rounded-3xl border border-[#155EEF]/20 bg-[#EEF4FF] p-6">
                        <h3 class="text-lg font-semibold text-[#061A33]">
                            Surat Rujukan Sudah Dibuat
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Surat rujukan psikiater untuk pasien ini sudah tersedia.
                        </p>

                        <a href="{{ route('psikolog.rujukan-psikiater.show', ['rujukan_psikiater' => $idRujukan]) }}"
                           class="mt-4 inline-flex rounded-2xl bg-[#155EEF] px-5 py-3 text-sm font-semibold text-white">
                            Lihat Surat Rujukan
                        </a>
                    </div>
                @endif

                @if($detail && ($detail->perlu_rujukan ?? false) && $rujukan && !$idRujukan)
                    <div class="mt-6 rounded-3xl border border-[#F79009]/20 bg-[#FFF7E8] p-6">
                        <h3 class="text-lg font-semibold text-[#061A33]">
                            Data Rujukan Belum Sinkron
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Surat rujukan terdeteksi, tetapi ID rujukan belum ditemukan. Cek primary key model RujukanPsikiater.
                        </p>
                    </div>
                @endif

                @if($detail && !($detail->perlu_rujukan ?? false))
                    <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-lg font-semibold text-[#061A33]">
                            Tidak Memerlukan Rujukan
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Pasien tidak ditandai membutuhkan surat rujukan psikiater untuk konsultasi ini.
                        </p>
                    </div>
                @endif

            @endif

        </div>
    </div>
</div>

@endsection