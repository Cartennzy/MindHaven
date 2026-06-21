@extends('backend.layouts.app')

@section('title', 'Edit Rumah Sakit')

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
        <h1 class="admin-title">Edit Rumah Sakit</h1>
        <p class="admin-subtitle">
            Perbarui data rumah sakit rujukan.
        </p>
    </div>

    <form action="{{ route('admin.rumah-sakit.update', $rumahSakit->id_rumahsakit) }}"
          method="POST"
          enctype="multipart/form-data"
          class="admin-card p-8 space-y-6">

        @csrf
        @method('PUT')

        {{-- FOTO --}}
        <div>
            <label class="admin-label">
                Foto Rumah Sakit
            </label>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center">

                <div>
                    @if(!empty($rumahSakit->foto))
                        <img src="{{ asset('storage/' . $rumahSakit->foto) }}"
                             alt="{{ $rumahSakit->nama_rumahsakit }}"
                             class="h-36 w-36 rounded-[2rem] object-cover shadow-lg">
                    @else
                        <div class="flex h-36 w-36 items-center justify-center rounded-[2rem] bg-slate-100 text-sm font-medium text-slate-400">
                            NO IMAGE
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <input type="file"
                           name="foto"
                           accept="image/*"
                           class="admin-input">

                    <p class="mt-3 text-xs font-medium text-slate-400">
                        Format: JPG, PNG, WEBP maksimal 2MB.
                    </p>

                    @error('foto')
                        <p class="mt-2 text-sm font-medium text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- NAMA --}}
        <div>
            <label class="admin-label">
                Nama Rumah Sakit
            </label>

            <input type="text"
                   name="nama_rumahsakit"
                   value="{{ old('nama_rumahsakit', $rumahSakit->nama_rumahsakit) }}"
                   class="admin-input"
                   placeholder="Contoh: RS Jiwa Harapan"
                   required>

            @error('nama_rumahsakit')
                <p class="mt-2 text-sm font-medium text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- ALAMAT --}}
        <div>
            <label class="admin-label">
                Alamat
            </label>

            <textarea name="alamat"
                      rows="4"
                      class="admin-input"
                      placeholder="Alamat lengkap rumah sakit..."
                      required>{{ old('alamat', $rumahSakit->alamat) }}</textarea>

            @error('alamat')
                <p class="mt-2 text-sm font-medium text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- TELEPON + STATUS --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <div>
                <label class="admin-label">
                    No Telepon
                </label>

                <input type="text"
                       name="no_telepon"
                       value="{{ old('no_telepon', $rumahSakit->no_telepon) }}"
                       class="admin-input"
                       placeholder="Contoh: 021-555-001"
                       required>

                @error('no_telepon')
                    <p class="mt-2 text-sm font-medium text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="admin-label">
                    Status
                </label>

                <select name="status"
                        class="admin-input">

                    <option value="aktif"
                        {{ old('status', $rumahSakit->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="nonaktif"
                        {{ old('status', $rumahSakit->status ?? 'aktif') == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>

                </select>

                @error('status')
                    <p class="mt-2 text-sm font-medium text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- WEBSITE --}}
        <div>
            <label class="admin-label">
                Website
            </label>

            <input type="text"
                   name="website"
                   value="{{ old('website', $rumahSakit->website ?? '') }}"
                   class="admin-input"
                   placeholder="Contoh: https://rumahsakit.com">

            @error('website')
                <p class="mt-2 text-sm font-medium text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- BUTTON --}}
        <div class="flex flex-col gap-4 sm:flex-row">

            <button type="submit"
                    class="admin-btn-primary">
                Update Rumah Sakit
            </button>

            <a href="{{ route('admin.rumah-sakit.index') }}"
               class="admin-btn-light">
                Batal
            </a>

        </div>

    </form>

</div>

@endsection