@extends('backend.layouts.app')

@section('title', 'Tambah Psikolog')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="admin-title">
            Tambah Psikolog
        </h1>

        <p class="admin-subtitle">
            Tambahkan akun psikolog baru ke sistem MindHaven.
        </p>
    </div>

    <div class="admin-card p-6">

        <form action="{{ route('admin.psikolog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama Akun</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-style" placeholder="Nama akun">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="input-style" placeholder="Nama lengkap psikolog">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-style" placeholder="psikolog@email.com">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input type="password" name="password" required class="input-style" placeholder="Minimal 6 karakter">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">No Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required class="input-style" placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="input-style">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="input-style">
                        <option value="">Pilih jenis kelamin</option>
                        <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Spesialisasi</label>
                    <input type="text" name="spesialisasi" value="{{ old('spesialisasi') }}" required class="input-style" placeholder="Contoh: Anxiety & Stress Management">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Pengalaman</label>
                    <input type="number" name="pengalaman" value="{{ old('pengalaman') }}" required min="0" class="input-style" placeholder="Contoh: 3">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Biaya Konsultasi</label>
                    <input type="number" name="biaya_konsultasi" value="{{ old('biaya_konsultasi') }}" required min="0" class="input-style" placeholder="Contoh: 150000">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Foto Profil</label>
                    <input type="file" name="foto_profil" class="input-style">
                </div>

            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Alamat</label>
                <textarea name="alamat" rows="4" required class="input-style" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.psikolog.index') }}"
                   class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-2xl bg-[#01588E] px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-[#01446e]">
                    Simpan Data
                </button>
            </div>

        </form>

    </div>

</div>

@endsection