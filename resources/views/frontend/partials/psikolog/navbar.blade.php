@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Konsultasi;

    $user = Auth::user();
    $psikolog = $user->psikolog ?? null;

    $idPsikologLogin = $psikolog->id_psikolog ?? $psikolog->id ?? null;

    $jumlahKonsultasiMasuk = $idPsikologLogin
        ? Konsultasi::where('id_psikolog', $idPsikologLogin)
            ->whereIn('status', ['pending', 'diproses'])
            ->count()
        : 0;

    $notifikasiKonsultasi = $idPsikologLogin
        ? Konsultasi::with('pasien.user')
            ->where('id_psikolog', $idPsikologLogin)
            ->whereIn('status', ['pending', 'diproses'])
            ->latest('id_konsultasi')
            ->take(5)
            ->get()
        : collect();
@endphp

<header class="sticky top-0 z-30 border-b border-white/40 bg-[#EEF3FA]/80 backdrop-blur-2xl shadow-[0_4px_30px_rgba(0,0,0,0.01)]">

    <div class="mx-auto flex w-full max-w-[1500px] items-center justify-between gap-5 px-4 py-4 sm:px-6 lg:px-8 xl:px-10">

        {{-- BAGIAN KIRI: TOMBOL HAMBURGER DAN PANEL JUDUL DINAMIS --}}
        <div class="min-w-0 flex-1 lg:flex-none">
            <div class="flex items-center gap-3.5">
                
                {{-- TOMBOL HAMBURGER MENU RESPONSIVE UNTUK LAYAR HP/TABLET --}}
                <button id="mobileMenuButton" type="button" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-[#01588E] shadow-sm hover:bg-slate-50 transition duration-200 lg:hidden">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Aksen gradient bar vertikal khas SaaS Premium (Hanya muncul di desktop) --}}
                <div class="hidden h-9 w-1.5 rounded-full bg-gradient-to-b from-[#01588E] to-[#49C5B6] lg:block shadow-sm"></div>

                <div class="min-w-0">
                    <h1 class="truncate text-xl font-black tracking-tight text-slate-950 sm:text-2xl lg:text-3xl">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    <p class="mt-0.5 text-xs font-bold text-slate-400 hidden sm:block">
                        @yield('page_subtitle', 'Kelola aktivitas layanan kesehatan mental Anda.')
                    </p>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: AKSI PANEL (NOTIFIKASI KONSULTASI MASUK & PROFIL PSIKOLOG) --}}
        <div class="flex shrink-0 items-center gap-4">

            {{-- NOTIFIKASI KONSULTASI MASUK --}}
            <div class="relative group flex items-center">
                
                <button type="button"
                        class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-white bg-white/90 text-[#01588E] shadow-[0_10px_25px_rgba(15,23,42,0.04)] transition duration-300 hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-[0_14px_30px_rgba(1,88,142,0.1)]">

                    <svg class="h-5 w-5 transition-transform duration-300 group-hover:rotate-12" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                    </svg>

                    @if($jumlahKonsultasiMasuk > 0)
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-xl bg-gradient-to-r from-red-500 to-rose-600 px-1 text-[9px] font-black text-white shadow-md shadow-red-200 ring-2 ring-white animate-bounce">
                            {{ $jumlahKonsultasiMasuk }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN KONSULTASI MASUK PANELS --}}
                <div class="invisible absolute right-0 top-[48px] pt-3 z-[9999] w-[290px] sm:w-[410px] translate-y-3 opacity-0 transition-all duration-300 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                    <div class="overflow-hidden rounded-[26px] border border-slate-100 bg-white shadow-[0_25px_60px_rgba(15,23,42,0.12)]">
                        
                        <div class="border-b border-slate-50 bg-gradient-to-b from-slate-50/50 to-white px-5 py-4">
                            <h2 class="text-base font-black text-slate-900">
                                Konsultasi Masuk
                            </h2>
                            <p class="mt-0.5 text-[11px] font-bold text-slate-400">
                                Permintaan reservasi konseling terupdate dari pasien
                            </p>
                        </div>

                        <div class="max-h-[350px] overflow-y-auto p-3 space-y-2">
                            @forelse($notifikasiKonsultasi as $konsultasi)
                                <a href="{{ route('psikolog.konsultasi.show', $konsultasi->id_konsultasi) }}"
                                   class="block rounded-2xl border border-slate-50/80 bg-slate-50/40 p-3.5 transition duration-200 hover:bg-[#F4FAFF] hover:border-blue-100 item-group">

                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#061A33] to-[#01588E] text-xs font-black text-white shadow-sm">
                                            {{ strtoupper(substr($konsultasi->pasien->nama_lengkap ?? $konsultasi->pasien->user->name ?? 'P', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <h3 class="truncate text-xs font-black text-slate-900 group-hover:text-[#01588E] transition-colors">
                                                {{ $konsultasi->pasien->nama_lengkap ?? $konsultasi->pasien->user->name ?? 'Pasien' }}
                                            </h3>

                                            <p class="mt-1 line-clamp-2 text-[11px] font-semibold leading-relaxed text-slate-400">
                                                {{ $konsultasi->keluhan ?? $konsultasi->topik_konseling ?? 'Pasien ingin melakukan konsultasi.' }}
                                            </p>

                                            <p class="mt-2 text-[10px] font-bold text-[#01588E] flex items-center gap-1">
                                                <i class="far fa-clock"></i> {{ $konsultasi->created_at ? $konsultasi->created_at->diffForHumans() : 'Baru saja' }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="py-10 text-center">
                                    <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-slate-50 text-slate-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-black text-slate-700">Antrean Bersih</p>
                                    <p class="mt-1 text-[10px] font-medium text-slate-400 max-w-[240px] mx-auto">Saat ini belum ada pengajuan sesi konsultasi tertunda dari pasien.</p>
                                </div>
                            @endforelse
                        </div>

                        @if($jumlahKonsultasiMasuk > 0)
                            <div class="border-t border-slate-50 p-3 bg-slate-50/50">
                                <a href="{{ route('psikolog.konsultasi.index') }}"
                                   class="flex h-10 w-full items-center justify-center rounded-xl bg-[#01588E] px-4 text-xs font-black text-white transition hover:bg-[#01446e] shadow-sm">
                                    Lihat Semua Daftar Konsultasi
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- PROFILE INFO PANEL REKAN PSIKOLOG --}}
            <a href="{{ route('psikolog.profile') }}"
               class="group flex h-12 items-center gap-3 rounded-2xl border border-white bg-white/90 pl-4 pr-2 shadow-[0_10px_25px_rgba(15,23,42,0.04)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_14px_30px_rgba(1,88,142,0.1)]">
                
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-slate-900 leading-none">Profile</p>
                    <p class="mt-1 max-w-[130px] truncate text-[10px] font-bold text-slate-400 leading-none">
                        {{ $psikolog->nama_lengkap ?? $user->name }}
                    </p>
                </div>

                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#061A33] to-[#01588E] text-[10px] font-black text-white shadow-sm transition duration-300 group-hover:scale-105">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </a>

        </div>

    </div>

</header>