@extends('backend.layouts.app')

@section('title', 'Edit Meditasi')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="admin-title">Edit Meditasi</h1>
        <p class="admin-subtitle">Perbarui konten meditasi MindHaven.</p>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl bg-red-50 p-5 text-sm font-semibold text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.meditasi.update', $meditasi->id_meditasi) }}" method="POST" enctype="multipart/form-data" class="admin-card p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="admin-label">Judul Meditasi</label>
            <input type="text" name="judul" class="admin-input" value="{{ old('judul', $meditasi->judul) }}">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label class="admin-label">Kategori</label>
                <select name="kategori" class="admin-input">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori }}" {{ old('kategori', $meditasi->kategori) == $kategori ? 'selected' : '' }}>
                            {{ $kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="admin-label">Durasi</label>
                <input type="number" name="durasi" class="admin-input" value="{{ old('durasi', $meditasi->durasi) }}">
            </div>

            <div>
                <label class="admin-label">Status</label>
                <select name="status" class="admin-input">
                    <option value="published" {{ old('status', $meditasi->status) == 'published' ? 'selected' : '' }}>
                        Published
                    </option>
                    <option value="draft" {{ old('status', $meditasi->status) == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                </select>
            </div>
        </div>

        <div>
            <label class="admin-label">File Audio / Video Baru</label>
            <input type="file" name="file_meditasi" class="admin-input" accept=".mp3,.wav,.m4a,.mp4,.mov,.webm">

            @if($meditasi->audio)
                <p class="mt-2 text-xs font-semibold text-slate-500">
                    File saat ini: {{ basename($meditasi->audio) }}
                </p>
            @endif
        </div>

        <div>
            <label class="admin-label">Deskripsi</label>
            <textarea name="deskripsi" rows="6" class="admin-input">{{ old('deskripsi', $meditasi->deskripsi) }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="admin-btn-primary">
                Update Meditasi
            </button>

            <a href="{{ route('admin.meditasi.index') }}" class="admin-btn-light">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection