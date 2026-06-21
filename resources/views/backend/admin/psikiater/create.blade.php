@extends('backend.layouts.app')

@section('title', 'Tambah Psikiater')

@section('content')

<div class="space-y-6">

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-sm text-red-700">
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-cyan-50 via-white to-blue-50 px-6 py-7 shadow-[0_18px_55px_rgba(15,23,42,.06)] md:px-8 md:py-8">
        <div class="absolute -right-28 -top-28 h-72 w-72 rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-cyan-100/50 blur-3xl"></div>

        <div class="relative">
            <div class="inline-flex items-center gap-3 rounded-full border border-blue-100 bg-white/90 px-4 py-2 text-sm font-medium text-[#01588E] shadow-sm">
                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                Tambah Data Psikiater
            </div>

            <h1 class="mt-6 text-4xl font-semibold tracking-tight text-slate-900 md:text-5xl">
                Tambah
                <span class="text-[#0284C7]">Psikiater</span>
            </h1>

            <p class="mt-5 max-w-3xl text-base font-normal leading-8 text-slate-600 md:text-lg">
                Tambahkan data psikiater resmi untuk kebutuhan surat rujukan pasien MindHaven.
            </p>
        </div>
    </section>

    <form action="{{ route('admin.psikiater.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,.05)] md:p-8">

        @csrf

        <div class="mb-8 flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0"/>
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-semibold text-slate-900">
                    Form Data Psikiater
                </h2>
                <p class="text-sm font-normal text-slate-500">
                    Lengkapi data psikiater dengan benar.
                </p>
            </div>
        </div>

        <div class="space-y-6">

            <div>
                <label class="admin-label">Foto Profil</label>
                <input type="file"
                       name="foto_profil"
                       accept="image/*"
                       class="admin-input cursor-pointer">
                <p class="mt-2 text-xs text-slate-400">
                    Upload foto psikiater. Format disarankan JPG, PNG, atau WEBP.
                </p>
            </div>

            <div>
                <label class="admin-label">Nama Psikiater</label>
                <input type="text"
                       name="nama_lengkap"
                       value="{{ old('nama_lengkap') }}"
                       class="admin-input"
                       placeholder="Contoh: dr. Ahmad Santoso, Sp.KJ"
                       required>

                @error('nama_lengkap')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="admin-label">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="admin-input"
                       placeholder="Contoh: psikiater@email.com"
                       required>

                @error('email')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <label class="admin-label">Spesialisasi</label>
                    <input type="text"
                           name="spesialisasi"
                           value="{{ old('spesialisasi') }}"
                           class="admin-input"
                           placeholder="Contoh: Kedokteran Jiwa"
                           required>

                    @error('spesialisasi')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label">No STR</label>
                    <input type="text"
                           name="str_psikiater"
                           value="{{ old('str_psikiater') }}"
                           class="admin-input"
                           placeholder="Contoh: STR-KJ-32-2026-001287">

                    @error('str_psikiater')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <label class="admin-label">No Telepon</label>
                    <input type="text"
                           name="no_telepon"
                           value="{{ old('no_telepon') }}"
                           class="admin-input"
                           placeholder="Contoh: 081234567890"
                           required>

                    @error('no_telepon')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label">Status</label>
                    <select name="status" class="admin-input" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    @error('status')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="admin-label">Rumah Sakit</label>
                <select name="id_rumahsakit"
                        class="admin-input"
                        required>
                    <option value="">Pilih Rumah Sakit</option>

                    @foreach($rumahSakits as $rumahSakit)
                        <option value="{{ $rumahSakit->id_rumahsakit }}"
                            {{ old('id_rumahsakit') == $rumahSakit->id_rumahsakit ? 'selected' : '' }}>
                            {{ $rumahSakit->nama_rumahsakit }}
                        </option>
                    @endforeach
                </select>

                @error('id_rumahsakit')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <label class="admin-label">Pengalaman</label>
                    <input type="number"
                           name="pengalaman"
                           value="{{ old('pengalaman', 0) }}"
                           min="0"
                           class="admin-input"
                           placeholder="Contoh: 5">

                    @error('pengalaman')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="admin-label">Jadwal Praktik</label>
                    <input type="text"
                           name="jadwal_praktik"
                           value="{{ old('jadwal_praktik') }}"
                           class="admin-input"
                           placeholder="Contoh: Senin - Jumat, 09.00 - 15.00">

                    @error('jadwal_praktik')
                        <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="admin-label">Alamat Praktik</label>
                <textarea name="alamat_praktik"
                          rows="4"
                          class="admin-input"
                          placeholder="Alamat praktik psikiater...">{{ old('alamat_praktik') }}</textarea>

                @error('alamat_praktik')
                    <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-100 pt-6 sm:flex-row">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-3 rounded-2xl bg-[#01588E] px-6 py-4 text-sm font-medium text-white shadow-lg shadow-[#01588E]/20 transition hover:bg-[#01446e]">
                    Simpan Psikiater
                </button>

                <a href="{{ route('admin.psikiater.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-slate-100 px-6 py-4 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                    Batal
                </a>
            </div>

        </div>

    </form>

</div>

@endsection