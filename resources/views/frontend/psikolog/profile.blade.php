@extends('frontend.layouts.psikolog')

@section('title', 'Profile Psikolog')
@section('page-title', 'Profile Psikolog')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\Konsultasi;
    use App\Models\JadwalPsikolog;
    use Carbon\Carbon;

    $user = $user ?? Auth::user();
    $psikolog = $psikolog ?? $user->psikolog;

    $idPsikologLogin = $psikolog->id_psikolog ?? null;

    $fotoPsikolog = ($psikolog && $psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil))
        ? asset('storage/' . $psikolog->foto_profil)
        : asset('assets/images/default-doctor.png');

    $totalKonsultasiProfile = $idPsikologLogin
        ? Konsultasi::where('id_psikolog', $idPsikologLogin)->count()
        : 0;

    $totalPasienProfile = $idPsikologLogin
        ? Konsultasi::where('id_psikolog', $idPsikologLogin)->distinct('id_pasien')->count('id_pasien')
        : 0;

    $tanggalGabung = $user->created_at
        ? Carbon::parse($user->created_at)->translatedFormat('d F Y')
        : '-';

    $statusVerifikasi = $psikolog->status_verifikasi ?? 'pending';

    $statusLabel = match($statusVerifikasi) {
        'verified' => 'Terverifikasi',
        'pending' => 'Menunggu Verifikasi',
        'rejected' => 'Ditolak',
        default => ucfirst($statusVerifikasi),
    };

    $ratingPsikolog = 0;

    // SINKRONISASI AKURAT: Ambil data hari praktik langsung dari tabel JadwalPsikolog berdasarkan register awal
    $currentJadwal = $idPsikologLogin
        ? JadwalPsikolog::where('id_psikolog', $idPsikologLogin)->pluck('hari')->toArray()
        : [];
@endphp

<div class="space-y-6 max-w-7xl mx-auto">

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- HERO HEADER --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-[#071A33] p-7 shadow-[0_24px_70px_rgba(15,23,42,0.16)]">
        <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-[#01588E]/40 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-[#41AD01]/20 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex flex-col gap-5 md:flex-row md:items-center">
                <img src="{{ $fotoPsikolog }}"
                     alt="Foto Psikolog"
                     class="h-28 w-28 rounded-[1.7rem] border-4 border-white/20 object-cover shadow-xl">

                <div>
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-medium uppercase tracking-[0.18em] text-white/80">
                            Profile Psikolog
                        </span>
                        <span class="rounded-full bg-emerald-400/15 px-4 py-2 text-xs font-medium text-emerald-100">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold leading-tight text-white lg:text-4xl">
                        {{ $psikolog->nama_lengkap ?? $user->name }}
                    </h1>
                    <p class="mt-1 text-sm text-white/65 font-medium">{{ $user->email }}</p>
                    <p class="mt-0.5 text-sm text-white/65 font-medium">{{ $psikolog->spesialisasi ?? 'Psikolog MindHaven' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-4 xl:w-[560px]">
                <div class="rounded-3xl bg-white/10 p-4 text-white backdrop-blur">
                    <p class="text-xs text-white/55 font-medium">Pasien</p>
                    <h3 class="mt-2 text-2xl font-bold">{{ $totalPasienProfile }}</h3>
                </div>
                <div class="rounded-3xl bg-white/10 p-4 text-white backdrop-blur">
                    <p class="text-xs text-white/55 font-medium">Sesi Konsultasi</p>
                    <h3 class="mt-2 text-2xl font-bold">{{ $totalKonsultasiProfile }}</h3>
                </div>
                <div class="rounded-3xl bg-white/10 p-4 text-white backdrop-blur">
                    <p class="text-xs text-white/55 font-medium">Rating Sesi</p>
                    <h3 class="mt-2 text-2xl font-bold">{{ $ratingPsikolog }}/5</h3>
                </div>
                <div class="rounded-3xl bg-white/10 p-4 text-white backdrop-blur">
                    <p class="text-xs text-white/55 font-medium">Bergabung</p>
                    <h3 class="mt-2 text-xs font-bold tracking-tight">{{ $tanggalGabung }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN FORM EDIT PROFILE --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <form action="{{ route('psikolog.profile.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.04)] border border-slate-100 xl:col-span-8 flex flex-col justify-between">
            @csrf
            @method('PUT')

            <div>
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-5">
                    <div>
                        <h2 class="text-xl font-bold text-[#061A33]">Informasi Profesional</h2>
                        <p class="mt-1 text-xs font-medium text-slate-400">Perbarui informasi kompetensi, bio klinis, dan data praktik Anda.</p>
                    </div>
                    <button type="submit" class="rounded-2xl bg-[#01588E] h-12 px-6 text-sm font-bold text-white shadow-sm hover:bg-[#01446e] transition whitespace-nowrap">
                        Simpan Perubahan
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $psikolog->nama_lengkap) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">No Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $psikolog->no_telepon) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($psikolog->tanggal_lahir)->format('Y-m-d')) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                            <option value="laki-laki" {{ old('jenis_kelamin', $psikolog->jenis_kelamin) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $psikolog->jenis_kelamin) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Spesialisasi Masalah</label>
                        <input type="text" name="spesialisasi" value="{{ old('spesialisasi', $psikolog->spesialisasi) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengalaman Praktik (Tahun)</label>
                        <input type="number" name="pengalaman" value="{{ old('pengalaman', $psikolog->pengalaman) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Biaya Konsultasi Per Sesi (Rp)</label>
                        <input type="number" name="biaya_konsultasi" value="{{ old('biaya_konsultasi', $psikolog->biaya_konsultasi) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode Konsultasi Sesi</label>
                        <select name="metode_konsultasi" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                            <option value="">Pilih Metode</option>
                            <option value="online" {{ old('metode_konsultasi', $psikolog->metode_konsultasi) === 'online' ? 'selected' : '' }}>Online (Video Call / Chat)</option>
                            <option value="offline" {{ old('metode_konsultasi', $psikolog->metode_konsultasi) === 'offline' ? 'selected' : '' }}>Offline (Tatap Muka Rumah Sakit)</option>
                            <option value="online dan offline" {{ old('metode_konsultasi', $psikolog->metode_konsultasi) === 'online dan offline' ? 'selected' : '' }}>Online dan Offline (Kombinasi)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Bio Deskripsi Singkat</label>
                        <textarea name="bio" rows="3" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">{{ old('bio', $psikolog->bio) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Riwayat Pendidikan Resmi</label>
                        <textarea name="pendidikan" rows="2" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">{{ old('pendidikan', $psikolog->pendidikan) }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Nomor STR Aktif</label>
                        <input type="text" name="str_psikolog" value="{{ old('str_psikolog', $psikolog->str_psikolog) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Nomor SIP Eksis</label>
                        <input type="text" name="sip_psikolog" value="{{ old('sip_psikolog', $psikolog->sip_psikolog) }}" class="w-full h-14 rounded-2xl border border-slate-200 bg-slate-50/50 px-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">
                    </div>

                    {{-- OUTPUT SINKRONISASI TEKS BADGE SAKTI DARI TABEL JADWAL_PSIKOLOGS --}}
                    <div class="md:col-span-2 rounded-2xl border border-slate-100 bg-slate-50/40 p-5 shadow-inner">
                        <label class="mb-3 block text-xs font-semibold text-slate-500 uppercase tracking-wider"><i class="fas fa-calendar-check text-[#01588E] mr-1"></i> Jadwal Aktif Praktik Mingguan (Sinkronisasi Register)</label>
                        <div class="flex flex-wrap gap-2">
                            @forelse($currentJadwal as $hari)
                                <span class="inline-flex h-9 items-center justify-center rounded-xl bg-blue-50 border border-blue-100 px-4 text-xs font-semibold text-[#01588E] shadow-sm">
                                    <i class="fas fa-check-circle text-emerald-500 mr-2"></i> Hari {{ $hari }}
                                </span>
                            @empty
                                <span class="text-xs font-medium text-slate-400 italic">Belum ada sinkronisasi jadwal aktif dari data registrasi awal.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Unggah Foto Profil Baru</label>
                        <input type="file" name="foto_profil" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-5 py-3 text-sm font-semibold text-slate-700 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-[#01588E] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat Kantor / Praktik Fisik</label>
                        <textarea name="alamat" rows="2" class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-5 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:bg-white">{{ old('alamat', $psikolog->alamat) }}</textarea>
                    </div>
                </div>
            </div>
        </form>

        {{-- SIDEBAR KANAN KEAMANAN & SUMMARY --}}
        <div class="space-y-6 xl:col-span-4">
            <div class="rounded-[2rem] bg-white p-6 shadow-[0_18_55px_rgba(15,23,42,0.06)] border border-slate-100">
                <h2 class="text-lg font-bold text-[#061A33]">Ringkasan Aktivitas Sesi</h2>
                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-[#EEF4FF] p-4"><p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Verifikasi Finansial</p><h3 class="mt-1 text-sm font-bold text-[#061A33]">{{ $statusLabel }}</h3></div>
                    <div class="rounded-2xl bg-[#E9FBEF] p-4"><p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Akumulasi Pasien Unik</p><h3 class="mt-1 text-sm font-bold text-[#061A33]">{{ $totalPasienProfile }} Jiwa</h3></div>
                    <div class="rounded-2xl bg-[#FFF4DF] p-4"><p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Jam Konseling</p><h3 class="mt-1 text-sm font-bold text-[#061A33]">{{ $totalKonsultasiProfile }} Sesi Tatap Muka</h3></div>
                </div>
            </div>

            <div class="rounded-[2rem] bg-white p-6 shadow-[0_18_55px_rgba(15,23,42,0.06)] border border-slate-100">
                <h2 class="text-lg font-bold text-[#061A33]">Proteksi & Kredensial Keamanan</h2>
                <form action="{{ route('psikolog.profile.password') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-400 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E]">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-400 uppercase tracking-wider">Kata Sandi Pengganti Baru</label>
                        <input type="password" name="password" required class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E]">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-400 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E]">
                    </div>
                    <button type="submit" class="w-full h-12 rounded-xl bg-[#061A33] text-xs font-bold text-white transition hover:bg-[#01588E]">Perbarui Proteksi Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection