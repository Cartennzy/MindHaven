@extends('backend.layouts.app')

@section('title', 'Tambah Rumah Sakit')

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

    <div>
        <h1 class="text-3xl font-medium text-[#061A33]">
            Tambah Rumah Sakit
        </h1>
        <p class="mt-2 text-slate-500">
            Tambahkan data rumah sakit rujukan.
        </p>
    </div>

    <form action="{{ route('admin.rumah-sakit.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="rounded-[2rem] bg-white p-8 shadow-sm">

        @csrf

        <div class="space-y-6">

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Foto Rumah Sakit
                </label>
                <input type="file"
                       name="foto"
                       accept="image/*"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#01588E]">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Nama Rumah Sakit
                </label>
                <input type="text"
                       name="nama_rumahsakit"
                       value="{{ old('nama_rumahsakit') }}"
                       placeholder="Contoh: RS Mitra Keluarga Bekasi"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#01588E]"
                       required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Alamat
                </label>
                <textarea name="alamat"
                          rows="4"
                          placeholder="Masukkan alamat lengkap rumah sakit"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#01588E]"
                          required>{{ old('alamat') }}</textarea>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    No Telepon
                </label>
                <input type="text"
                       name="no_telepon"
                       value="{{ old('no_telepon') }}"
                       placeholder="Contoh: 021-xxxxxxx"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#01588E]"
                       required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Website
                </label>
                <input type="text"
                       name="website"
                       value="{{ old('website') }}"
                       placeholder="Contoh: https://rumahsakit.com"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#01588E]">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>
                <select name="status"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#01588E]">
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">

            <a href="{{ route('admin.rumah-sakit.index') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                Batal
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#01466f]">
                Simpan Rumah Sakit
            </button>

        </div>

    </form>

</div>

@endsection