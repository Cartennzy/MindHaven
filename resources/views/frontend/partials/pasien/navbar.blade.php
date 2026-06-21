@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use App\Models\Notifikasi;

    $user = Auth::user();
    $pasien = $user->pasien ?? null;

    $namaPasien = $pasien->nama_lengkap ?? $user->name ?? 'Pasien';
    $inisialPasien = strtoupper(substr($namaPasien, 0, 2));

    $notifikasis = Notifikasi::where('user_id', $user->id)
        ->where('tipe', 'hasil_konsultasi')
        ->latest()
        ->take(8)
        ->get();

    $jumlahNotif = Notifikasi::where('user_id', $user->id)
        ->where('tipe', 'hasil_konsultasi')
        ->where('is_read', false)
        ->count();
@endphp

<header class="sticky top-0 z-50 border-b border-white/40 bg-[#EEF3FA]/80 backdrop-blur-2xl shadow-[0_4px_30px_rgba(0,0,0,0.01)]">

    <div class="mx-auto flex w-full max-w-[1500px] items-center justify-between gap-5 px-4 py-4 sm:px-6 lg:px-8 xl:px-10">

        {{-- BAGIAN KIRI: JUDUL NAVBAR DINAMIS MENGGUNAKAN YIELD --}}
        <div class="min-w-0">
            <div class="flex items-center gap-3.5">
                <div class="hidden h-9 w-1.5 rounded-full bg-gradient-to-b from-[#01588E] to-[#49C5B6] lg:block shadow-sm"></div>

                <div class="min-w-0">
                    <!-- FIX DI SINI: Menggunakan yield agar judul berubah otomatis sesuai halaman, jika kosong default ke Dashboard -->
                    <h1 class="truncate text-xl font-black tracking-tight text-slate-950 sm:text-2xl lg:text-3xl">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    <p class="mt-0.5 text-xs font-bold text-slate-400">
                        @yield('page_subtitle', 'Kelola aktivitas layanan kesehatan mental Anda.')
                    </p>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: PANEL AKSI UTAMA (NOTIFIKASI & PROFIL) --}}
        <div class="flex shrink-0 items-center gap-4">
            
            {{-- Tombol Mobile Menu Responsif --}}
            <button id="mobileMenuButton" type="button" class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-[#01588E] shadow-sm hover:bg-slate-50 transition lg:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- NOTIFIKASI HASIL KONSULTASI --}}
            <div class="relative flex items-center">

                <button type="button"
                        id="notifButtonPasien"
                        class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-white bg-white/90 text-[#01588E] shadow-[0_10px_25px_rgba(15,23,42,0.04)] transition duration-300 hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-[0_14px_30px_rgba(1,88,142,0.1)] group">

                    <svg class="h-5 w-5 transition-transform duration-300 group-hover:rotate-12"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-width="2.2"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                    </svg>

                    @if($jumlahNotif > 0)
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-xl bg-gradient-to-r from-[#41AD01] to-[#62C91B] px-1 text-[9px] font-black text-white shadow-md shadow-green-200 ring-2 ring-white animate-bounce">
                            {{ $jumlahNotif }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN PANEL NOTIFIKASI --}}
                <div id="notifDropdownPasien"
                     class="absolute right-0 top-[58px] z-[9999] hidden w-[380px] overflow-hidden rounded-[26px] border border-slate-100 bg-white shadow-[0_25px_60px_rgba(15,23,42,0.12)]">

                    <div class="border-b border-slate-50 bg-gradient-to-b from-slate-50/50 to-white px-5 py-4">
                        <h3 class="text-base font-black text-slate-900">Notifikasi Masuk</h3>
                        <p class="mt-0.5 text-[11px] font-bold text-slate-400">Lembar pembaruan rekam medis hasil pemeriksaan</p>
                    </div>

                    <div class="max-h-[360px] overflow-y-auto p-3 space-y-2">
                        @forelse($notifikasis as $notif)
                            @php
                                $link = '#';

                                if ($notif->konsultasi_id && Route::has('pasien.hasil-konsultasi.show')) {
                                    $link = route('pasien.hasil-konsultasi.show', $notif->konsultasi_id);
                                } elseif (Route::has('pasien.hasil-konsultasi.index')) {
                                    $link = route('pasien.hasil-konsultasi.index');
                                }
                            @endphp

                            <a href="{{ $link }}"
                               class="block rounded-2xl border border-slate-50/80 bg-slate-50/40 p-3.5 transition duration-200 hover:bg-[#F4FAFF] hover:border-blue-100 group">

                                <div class="flex gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#01588E]/10 text-[#01588E] border border-[#01588E]/5 group-hover:bg-[#01588E] group-hover:text-white transition-all">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <h4 class="line-clamp-1 text-xs font-black text-slate-900 group-hover:text-[#01588E] transition-colors">
                                            {{ $notif->judul ?? 'Hasil Konsultasi Baru' }}
                                        </h4>
                                        <p class="mt-1 line-clamp-2 text-[11px] font-semibold leading-relaxed text-slate-400">
                                            {{ $notif->pesan ?? 'Psikolog telah mengirimkan hasil konsultasi Anda.' }}
                                        </p>
                                        <p class="mt-2 text-[10px] font-bold text-slate-400/80 flex items-center gap-1">
                                            <i class="far fa-clock"></i> {{ $notif->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-10 text-center">
                                <div class="text-slate-300 text-2xl mb-2"><i class="far fa-bell-slash"></i></div>
                                <p class="text-xs font-black text-slate-700">Belum ada pembaruan</p>
                                <p class="mt-1 text-[10px] font-medium text-slate-400 max-w-[240px] mx-auto">Notifikasi rilis berkas dokumen medis Anda akan terangkum di sini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if(Route::has('pasien.hasil-konsultasi.index'))
                        <div class="border-t border-slate-50 p-3 bg-slate-50/50">
                            <a href="{{ route('pasien.hasil-konsultasi.index') }}"
                               class="flex h-10 w-full items-center justify-center rounded-xl bg-[#01588E] px-4 text-xs font-black text-white transition hover:bg-[#01466f] shadow-sm">
                                Lihat Semua Hasil Sesi
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PROFILE USER CONTAINER --}}
            <a href="{{ route('pasien.profile.index') }}"
               class="group flex h-12 items-center gap-3 rounded-2xl border border-white bg-white/90 pl-4 pr-2 shadow-[0_10px_25px_rgba(15,23,42,0.04)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_14px_30px_rgba(1,88,142,0.1)]">

                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-slate-900 leading-none">
                        Profile
                    </p>
                    <p class="mt-1 max-w-[120px] truncate text-[10px] font-bold text-slate-400 leading-none">
                        {{ $namaPasien }}
                    </p>
                </div>

                @if($pasien && $pasien->foto_profil)
                    <img src="{{ asset('storage/' . $pasien->foto_profil) }}"
                         alt="Foto Profil"
                         class="h-8 w-8 rounded-xl object-cover border border-slate-100 shadow-sm transition duration-300 group-hover:scale-105">
                @else
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#061A33] to-[#01588E] text-[10px] font-black text-white shadow-sm transition duration-300 group-hover:scale-105">
                        {{ $inisialPasien }}
                    </div>
                @endif
            </a>

        </div>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('notifButtonPasien');
        const dropdown = document.getElementById('notifDropdownPasien');

        if (!button || !dropdown) return;

        button.addEventListener('click', function (event) {
            event.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        dropdown.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        document.addEventListener('click', function () {
            dropdown.classList.add('hidden');
        });
    });
</script>