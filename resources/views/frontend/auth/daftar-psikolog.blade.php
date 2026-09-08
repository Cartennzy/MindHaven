@extends('frontend.layouts.guest')

@section('title', 'Bergabung Psikolog - MindHaven')

@section('content')

<style>
    html {
        scroll-behavior: smooth;
    }

    .mh-reveal {
        opacity: 0;
        transform: translateY(22px);
        transition: opacity .7s ease, transform .7s ease;
    }

    .mh-reveal.mh-show {
        opacity: 1;
        transform: translateY(0);
    }

    .mh-error-text {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 800;
        color: #DC2626;
    }

    .mh-input-error {
        border-color: #EF4444 !important;
        background: #FFF7F7 !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, .10) !important;
    }

    .mh-file-card-error {
        border-color: #FCA5A5 !important;
        background: #FFF7F7 !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, .08) !important;
    }
</style>

<div class="min-h-screen bg-[#F6FAFD] text-slate-800">

    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 left-10 h-96 w-96 rounded-full bg-[#28AEDA]/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-[30rem] w-[30rem] rounded-full bg-[#28AEDA]/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-6xl px-5 py-6 lg:px-8">

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                    <img src="{{ asset('assets/images/logo_polos.png') }}"
                         class="h-8 w-8 object-contain"
                         alt="MindHaven">
                </div>

                <div>
                    <h1 class="text-lg font-black text-[#28AEDA]">MindHaven</h1>
                    <p class="text-xs font-semibold text-slate-500">Program Mitra Psikolog</p>
                </div>
            </a>

            <a href="{{ route('home') }}"
               class="inline-flex w-fit items-center justify-center rounded-xl bg-white px-5 py-3 text-xs font-black text-[#28AEDA] shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:bg-[#28AEDA] hover:text-white">
                Kembali ke Beranda
            </a>
        </div>

        <div class="mb-7 rounded-3xl bg-gradient-to-br from-[#157EA1] via-[#28AEDA] to-[#5CC9EA] p-6 text-white shadow-[0_22px_70px_rgba(40,174,218,0.22)] lg:p-8 mh-reveal">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-7">
                    <span class="inline-flex rounded-full bg-white/15 px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] backdrop-blur">
                        Partnership Program
                    </span>

                    <h2 class="mt-5 max-w-3xl text-2xl font-black leading-tight md:text-3xl">
                        Bergabung Sebagai Psikolog Profesional MindHaven
                    </h2>

                    <p class="mt-3 max-w-3xl text-sm font-medium leading-7 text-white/75">
                        Lengkapi data pengajuan mitra. Setelah diverifikasi admin, akun login dan password akan dikirim melalui email yang didaftarkan.
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-7 mh-reveal">
            <span class="inline-flex rounded-full bg-[#28AEDA]/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.16em] text-[#28AEDA]">
                Form Pengajuan Mitra Psikolog
            </span>

            <h1 class="mt-4 text-2xl font-black leading-tight text-slate-950 md:text-3xl">
                Pendaftaran Kerja Sama Psikolog
            </h1>

            <p class="mt-3 max-w-3xl text-sm font-medium leading-7 text-slate-500">
                Pastikan data yang dikirim sudah benar dan sesuai dengan dokumen profesional yang dilampirkan.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form id="mhRegistrationForm"
              action="{{ route('psikolog.register.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-5"
              novalidate>
            @csrf

            {{-- INPUT HIDDEN UTK SINKRONISASI MANIPULASI STRING JADWAL KE CONTROLLER --}}
            <input type="hidden" id="jadwalPraktikHidden" name="jadwal_praktik" value="{{ old('jadwal_praktik') }}">

            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:p-6 mh-reveal">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#28AEDA]/10 text-sm font-black text-[#28AEDA]">
                        01
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-950">Data Akun</h2>
                        <p class="text-xs font-semibold text-slate-400">Informasi akun untuk proses login.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Nama Akun</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('name') ? 'mh-input-error' : '' }}"
                               placeholder="Masukan nama akun">
                        @error('name')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('email') ? 'mh-input-error' : '' }}"
                               placeholder="Masukan email aktif">
                        @error('email')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:p-6 mh-reveal">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#28AEDA]/10 text-sm font-black text-[#28AEDA]">
                        02
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-950">Data Pribadi</h2>
                        <p class="text-xs font-semibold text-slate-400">Identitas dasar psikolog.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Nama Lengkap + Gelar</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('nama_lengkap') ? 'mh-input-error' : '' }}"
                               placeholder="Contoh: Najwan Muyassar, M.Psi., Psikolog">
                        @error('nama_lengkap')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">No Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('no_telepon') ? 'mh-input-error' : '' }}"
                               placeholder="Contoh: 08123456789">
                        @error('no_telepon')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('tanggal_lahir') ? 'mh-input-error' : '' }}">
                        @error('tanggal_lahir')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('jenis_kelamin') ? 'mh-input-error' : '' }}">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-black text-slate-700">Alamat Praktik / Domisili</label>
                        <textarea name="alamat" rows="3"
                                  class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold leading-7 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('alamat') ? 'mh-input-error' : '' }}"
                                  placeholder="Masukan alamat praktik atau domisili">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:p-6 mh-reveal">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#28AEDA]/10 text-sm font-black text-[#28AEDA]">
                        03
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-950">Data Profesional</h2>
                        <p class="text-xs font-semibold text-slate-400">Profil layanan awal dan pengalaman psikolog.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Spesialisasi</label>
                        <select name="spesialisasi"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('spesialisasi') ? 'mh-input-error' : '' }}">
                            <option value="">Pilih spesialisasi utama</option>
                            <option value="Stres, Stress Management, Burnout" {{ old('spesialisasi') == 'Stres, Stress Management, Burnout' ? 'selected' : '' }}>Stres / Burnout</option>
                            <option value="Gangguan Kecemasan, Anxiety, Overthinking" {{ old('spesialisasi') == 'Gangguan Kecemasan, Anxiety, Overthinking' ? 'selected' : '' }}>Gangguan Kecemasan</option>
                            <option value="Depresi, Mood Disorder, Emotional Support" {{ old('spesialisasi') == 'Depresi, Mood Disorder, Emotional Support' ? 'selected' : '' }}>Depresi</option>
                            <option value="Keluarga & Hubungan, Relationship, Family Counseling" {{ old('spesialisasi') == 'Keluarga & Hubungan, Relationship, Family Counseling' ? 'selected' : '' }}>Keluarga & Hubungan</option>
                            <option value="Trauma, Trauma Healing, Inner Child" {{ old('spesialisasi') == 'Trauma, Trauma Healing, Inner Child' ? 'selected' : '' }}>Trauma</option>
                            <option value="Gangguan Mood, Mood Disorder, Emotional Regulation" {{ old('spesialisasi') == 'Gangguan Mood, Mood Disorder, Emotional Regulation' ? 'selected' : '' }}>Gangguan Mood</option>
                            <option value="Lainnya, Kesehatan Mental Umum, Self Growth" {{ old('spesialisasi') == 'Lainnya, Kesehatan Mental Umum, Self Growth' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('spesialisasi')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">Pengalaman Kerja</label>
                        <input type="number" name="pengalaman" value="{{ old('pengalaman') }}" min="0"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('pengalaman') ? 'mh-input-error' : '' }}"
                               placeholder="Contoh: 5">
                        <p class="mt-2 text-xs font-bold text-slate-400">Isi angka dalam tahun.</p>
                        @error('pengalaman')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-black text-slate-700">Biaya Konsultasi</label>
                        <input type="number" name="biaya_konsultasi" value="{{ old('biaya_konsultasi') }}" min="0"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('biaya_konsultasi') ? 'mh-input-error' : '' }}"
                               placeholder="Contoh: 150000">
                        @error('biaya_konsultasi')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:p-6 mh-reveal">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#28AEDA]/10 text-sm font-black text-[#28AEDA]">
                        04
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-950">Pendidikan & Legalitas</h2>
                        <p class="text-xs font-semibold text-slate-400">Data akademik, izin praktik, dan dokumen legal.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-black text-slate-700">Pendidikan</label>
                        <textarea name="pendidikan" rows="3"
                                  class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold leading-7 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('pendidikan') ? 'mh-input-error' : '' }}"
                                  placeholder="Contoh: S1 Psikologi — Universitas Indonesia. S2 Profesi Psikolog — Universitas Padjadjaran.">{{ old('pendidikan') }}</textarea>
                        @error('pendidikan')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 {{ $errors->has('dokumen_pendidikan') ? 'mh-file-card-error' : '' }}">
                        <label class="mb-2 block text-xs font-black text-slate-700">Upload Dokumen Pendidikan</label>
                        <input type="file" name="dokumen_pendidikan"
                               class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-[#28AEDA] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:bg-slate-50 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('dokumen_pendidikan') ? 'mh-input-error' : '' }}"
                               accept=".pdf,image/png,image/jpeg,image/jpg">
                        <p class="mt-2 text-xs font-bold text-slate-400">
                            Upload ijazah, transkrip, sertifikat profesi, atau bukti pendidikan. PDF/JPG/PNG maksimal 5MB.
                        </p>
                        @error('dokumen_pendidikan')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">STR Psikolog</label>
                        <input type="text" name="str_psikolog" value="{{ old('str_psikolog') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('str_psikolog') ? 'mh-input-error' : '' }}"
                               placeholder="Contoh: STRPK-32-2026-0001928">
                        @error('str_psikolog')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black text-slate-700">SIP Psikolog</label>
                        <input type="text" name="sip_psikolog" value="{{ old('sip_psikolog') }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:bg-white focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('sip_psikolog') ? 'mh-input-error' : '' }}"
                               placeholder="Contoh: 445/SIP/PSI/2026">
                        @error('sip_psikolog')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 {{ $errors->has('dokumen_str_psikolog') ? 'mh-file-card-error' : '' }}">
                        <label class="mb-2 block text-xs font-black text-slate-700">Upload Dokumen STR Psikolog</label>
                        <input type="file" name="dokumen_str_psikolog"
                               class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-[#28AEDA] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:bg-slate-50 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('dokumen_str_psikolog') ? 'mh-input-error' : '' }}"
                               accept=".pdf,image/png,image/jpeg,image/jpg">
                        <p class="mt-2 text-xs font-bold text-slate-400">
                            Upload scan STR Psikolog. PDF/JPG/PNG maksimal 5MB.
                        </p>
                        @error('dokumen_str_psikolog')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 {{ $errors->has('dokumen_sip_psikolog') ? 'mh-file-card-error' : '' }}">
                        <label class="mb-2 block text-xs font-black text-slate-700">Upload Dokumen SIP Psikolog</label>
                        <input type="file" name="dokumen_sip_psikolog"
                               class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-[#28AEDA] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:bg-slate-50 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('dokumen_sip_psikolog') ? 'mh-input-error' : '' }}"
                               accept=".pdf,image/png,image/jpeg,image/jpg">
                        <p class="mt-2 text-xs font-bold text-slate-400">
                            Upload scan SIP Psikolog. PDF/JPG/PNG maksimal 5MB.
                        </p>
                        @error('dokumen_sip_psikolog')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- JADWAL PRAKTIK --}}
                    <div class="md:col-span-2">
                        <div class="rounded-[28px] border border-slate-200 bg-slate-50 p-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-black text-slate-800">
                                    Jadwal Praktik Psikolog
                                </h3>
                                <p class="mt-2 text-sm font-semibold text-slate-500">
                                    Pilih hari dan jam praktik yang tersedia untuk pasien melakukan konsultasi.
                                </p>
                            </div>

                            <div class="mb-6">
                                <label class="mb-4 block text-sm font-black text-slate-700">
                                    Hari Praktik
                                </label>

                                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                    @foreach([
                                        'Senin',
                                        'Selasa',
                                        'Rabu',
                                        'Kamis',
                                        'Jumat',
                                        'Sabtu',
                                        'Minggu'
                                    ] as $hari)
                                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 cursor-pointer hover:border-[#01588E] transition">
                                            <input type="checkbox"
                                                   name="hari_praktik[]"
                                                   value="{{ $hari }}"
                                                   class="h-5 w-5 rounded border-slate-300 text-[#01588E] focus:ring-[#01588E]"
                                                   {{ in_array($hari, old('hari_praktik', [])) ? 'checked' : '' }}>
                                            <span class="text-sm font-bold text-slate-700">
                                                {{ $hari }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                @error('hari_praktik')
                                    <p class="mt-3 text-sm font-bold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-3 block text-sm font-black text-slate-700">
                                        Jam Mulai
                                    </label>
                                    <input type="time"
                                           name="jam_mulai"
                                           value="{{ old('jam_mulai') }}"
                                           class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-700 focus:border-[#01588E] focus:ring-[#01588E]">
                                    @error('jam_mulai')
                                        <p class="mt-3 text-sm font-bold text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-3 block text-sm font-black text-slate-700">
                                        Jam Selesai
                                    </label>
                                    <input type="time"
                                           name="jam_selesai"
                                           value="{{ old('jam_selesai') }}"
                                           class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-700 focus:border-[#01588E] focus:ring-[#01588E]">
                                    @error('jam_selesai')
                                        <p class="mt-3 text-sm font-bold text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm lg:p-6 mh-reveal">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#28AEDA]/10 text-sm font-black text-[#28AEDA]">
                        05
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-950">Foto Profil & CV</h2>
                        <p class="text-xs font-semibold text-slate-400">Upload foto profil dan CV sebagai dokumen pendukung.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 {{ $errors->has('foto_profil') ? 'mh-file-card-error' : '' }}">
                        <label class="mb-2 block text-xs font-black text-slate-700">Foto Profil</label>
                        <input type="file"
                               name="foto_profil"
                               class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-[#28AEDA] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:bg-slate-50 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('foto_profil') ? 'mh-input-error' : '' }}"
                               accept="image/png,image/jpeg,image/jpg">
                        <p class="mt-2 text-xs font-bold leading-5 text-slate-400">
                            Opsional. Gunakan foto profesional dengan format JPG/PNG maksimal 2MB.
                        </p>
                        @error('foto_profil')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 {{ $errors->has('dokumen_verifikasi') ? 'mh-file-card-error' : '' }}">
                        <label class="mb-2 block text-xs font-black text-slate-700">Upload CV</label>
                        <input type="file"
                               name="dokumen_verifikasi"
                               class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-600 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-[#28AEDA] file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:bg-slate-50 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10 {{ $errors->has('dokumen_verifikasi') ? 'mh-input-error' : '' }}"
                               accept=".pdf,image/png,image/jpeg,image/jpg">
                        <p class="mt-2 text-xs font-bold leading-5 text-slate-400">
                            Wajib upload CV terbaru. Format PDF/JPG/PNG maksimal 5MB.
                        </p>
                        @error('dokumen_verifikasi')
                            <p class="mh-error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[#28AEDA]/10 bg-[#F0F7FB] p-5 mh-reveal">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[#28AEDA] shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-black text-slate-800">
                            Informasi Pengajuan Mitra
                        </h3>
                        <p class="mt-2 text-sm font-semibold leading-7 text-slate-500">
                            Data pengajuan akan masuk ke admin dengan status pending.
                            Jika disetujui, akun psikolog akan aktif dan informasi login
                            dikirim melalui email.
                        </p>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 z-20 -mx-5 border-t border-slate-200 bg-white/85 px-5 py-4 backdrop-blur-xl lg:-mx-8 lg:px-8">
                <div class="mx-auto flex max-w-6xl flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-6 py-3 text-xs font-black text-slate-600 transition hover:bg-slate-200">
                        Kembali
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-[#28AEDA] px-7 py-3 text-xs font-black text-white shadow-[0_16px_40px_rgba(40,174,218,0.24)] transition hover:-translate-y-0.5 hover:bg-[#2198BF]">
                        Kirim Pengajuan
                    </button>
                </div>
            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const revealElements = document.querySelectorAll('.mh-reveal');

    const revealObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('mh-show');
            }
        });
    }, {
        threshold: 0.12
    });

    revealElements.forEach(function(element) {
        revealObserver.observe(element);
    });

    // SINKRONISASI DATALAYER FORM SUBMIT:
    // Menangkap array hari praktik pasca user klik submit form untuk otomatis digabungkan ke hidden field 'jadwal_praktik'
    const form = document.getElementById('mhRegistrationForm');
    const hiddenJadwalInput = document.getElementById('jadwalPraktikHidden');

    form.addEventListener('submit', function () {
        const checkedBoxes = document.querySelectorAll('input[name="hari_praktik[]"]:checked');
        const selectedDays = [];
        
        checkedBoxes.forEach(function (box) {
            selectedDays.push(box.value);
        });

        if (selectedDays.length > 0) {
            hiddenJadwalInput.value = selectedDays.join(', ');
        } else {
            hiddenJadwalInput.value = '';
        }
    });
});
</script>

@endsection