@extends('backend.layouts.app')

@section('title', 'Tambah Meditasi')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="admin-title">Tambah Meditasi</h1>
        <p class="admin-subtitle">Tambahkan konten meditasi berdasarkan kategori kebutuhan pasien.</p>
    </div>

    @if(session('error'))
        <div class="rounded-2xl bg-red-50 p-5 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.meditasi.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="admin-card p-8 space-y-6"
          novalidate>
        @csrf

        <div>
            <label class="admin-label">Judul Meditasi</label>
            <input type="text"
                   name="judul"
                   value="{{ old('judul') }}"
                   class="admin-input {{ $errors->has('judul') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-100' : '' }}"
                   placeholder="Contoh: Meditasi Tidur Nyenyak">

            @error('judul')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label class="admin-label">Kategori</label>
                <select name="kategori"
                        class="admin-input {{ $errors->has('kategori') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-100' : '' }}">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>
                            {{ $kategori }}
                        </option>
                    @endforeach
                </select>

                @error('kategori')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="admin-label">Durasi</label>
                <input type="number"
                       name="durasi"
                       value="{{ old('durasi') }}"
                       class="admin-input {{ $errors->has('durasi') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-100' : '' }}"
                       placeholder="Contoh: 10">

                @error('durasi')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="admin-label">Status</label>
                <select name="status"
                        class="admin-input {{ $errors->has('status') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-100' : '' }}">
                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>
                        Published
                    </option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                </select>

                @error('status')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="admin-label">File Audio / Video</label>
            <input type="file"
                   name="file_meditasi"
                   class="admin-input {{ $errors->has('file_meditasi') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-100' : '' }}"
                   accept=".mp3,.wav,.m4a,.mp4,.mov,.webm">

            <p class="mt-2 text-xs font-semibold text-slate-500">
                Format: mp3, wav, m4a, mp4, mov, webm. Maksimal 50 MB.
            </p>

            @error('file_meditasi')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="admin-label">Deskripsi</label>
            <textarea name="deskripsi"
                      rows="6"
                      class="admin-input {{ $errors->has('deskripsi') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-100' : '' }}"
                      placeholder="Deskripsi meditasi...">{{ old('deskripsi') }}</textarea>

            @error('deskripsi')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="admin-btn-primary">
                Simpan Meditasi
            </button>

            <a href="{{ route('admin.meditasi.index') }}" class="admin-btn-light">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection