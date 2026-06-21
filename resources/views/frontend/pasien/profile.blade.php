@extends('frontend.layouts.app')

@section('title', 'Profil Saya - MindHaven')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Kelola informasi pribadi akun pasien Anda.')

@section('content')
<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

    <div class="card">
        <div class="flex flex-col items-center text-center">

            @if($pasien->foto_profil)
                <img src="{{ asset('storage/' . $pasien->foto_profil) }}"
                     alt="Foto Profil"
                     class="h-28 w-28 rounded-3xl object-cover shadow-soft">
            @else
                <div class="flex h-28 w-28 items-center justify-center rounded-3xl bg-primary text-4xl font-black text-white shadow-soft">
                    {{ strtoupper(substr($pasien->nama_lengkap ?? Auth::user()->name, 0, 1)) }}
                </div>
            @endif

            <h3 class="mt-5 text-xl font-bold text-primary">
                {{ $pasien->nama_lengkap }}
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                {{ Auth::user()->email }}
            </p>

            <span class="badge-success mt-4">Pasien Aktif</span>
        </div>
    </div>

    <div class="card xl:col-span-2">

        @if(session('success'))
            <div class="mb-5 rounded-2xl bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-2xl bg-red-50 px-5 py-4 text-sm font-bold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('pasien.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pasien->nama_lengkap) }}" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">No Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $pasien->no_telepon) }}" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-input" required>
                        <option value="laki-laki" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="mt-5">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" rows="4" class="form-input" required>{{ old('alamat', $pasien->alamat) }}</textarea>
            </div>

            <div class="mt-5">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="foto_profil" accept="image/png,image/jpeg,image/jpg,image/webp" class="form-input">
            </div>

            <button type="submit" class="btn-success mt-6">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection