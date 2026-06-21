@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\Konsultasi;

    $user = Auth::user();
    $psikolog = $user->psikolog ?? null;

    $namaPsikolog = $psikolog->nama_lengkap ?? $user->name;

    $fotoPsikolog = ($psikolog && $psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil))
        ? asset('storage/' . $psikolog->foto_profil)
        : asset('assets/images/default-doctor.png');

    $idPsikologLogin = $psikolog->id_psikolog ?? $psikolog->id ?? null;

    $jumlahKonsultasiMasuk = $idPsikologLogin
        ? Konsultasi::where('id_psikolog', $idPsikologLogin)
            ->whereIn('status', ['pending', 'diproses'])
            ->count()
        : 0;
@endphp

{{-- UPGRADE CSS CLASS: Mengaktifkan mode sliding responsive via -translate-x-full --}}
<aside id="psikologMainSidebar" class="fixed left-0 top-0 z-50 h-screen w-[270px] bg-[#061A33] text-white transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:block">

    <div id="psikologSidebarScroll" class="flex h-full flex-col overflow-y-auto px-5 py-6">

        <div class="mb-8 flex items-center gap-3 rounded-3xl bg-white/10 px-4 py-4">
            <img src="{{ asset('assets/images/logo_polos.png') }}"
                 alt="MindHaven"
                 class="h-11 w-11 rounded-xl bg-white object-contain p-1">

            <div>
                <h1 class="text-xl font-bold tracking-tight">MindHaven</h1>
                <p class="text-[10px] font-medium text-blue-100/60 uppercase tracking-wider">Psychologist Panel</p>
            </div>
        </div>

        <div class="mb-8 rounded-[2rem] bg-white/5 border border-white/5 px-5 py-6 text-center shadow-inner">
            <div class="mx-auto h-20 w-24 overflow-hidden rounded-2xl border-2 border-white/20 shadow-xl">
                <img src="{{ $fotoPsikolog }}"
                     alt="Foto Psikolog"
                     class="h-full w-full object-cover">
            </div>

            <h2 class="mt-4 text-base font-semibold tracking-tight text-white line-clamp-1">
                {{ $namaPsikolog }}
            </h2>

            <p class="mt-1 text-xs font-medium text-blue-100/60 line-clamp-1">
                {{ $psikolog->spesialisasi ?? 'Psikolog MindHaven' }}
            </p>
        </div>

        <p class="mb-4 px-3 text-[10px] font-bold uppercase tracking-[0.24em] text-blue-100/40">
            Menu Utama
        </p>

        <nav class="space-y-1.5 flex-1">

            <a href="{{ route('psikolog.dashboard') }}"
               class="group flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold transition duration-200
               {{ request()->routeIs('psikolog.dashboard') ? 'bg-white text-[#061A33] shadow-lg' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('psikolog.dashboard') ? 'bg-[#EAF1FF]' : 'bg-white/10 group-hover:bg-white/15' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z"/>
                    </svg>
                </span>
                Dashboard
            </a>

            <a href="{{ route('psikolog.pendapatan.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold transition duration-200
               {{ request()->routeIs('psikolog.pendapatan.*') ? 'bg-white text-[#061A33] shadow-lg' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('psikolog.pendapatan.*') ? 'bg-[#EAF1FF]' : 'bg-white/10 group-hover:bg-white/15' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                Pendapatan
            </a>

            <a href="{{ route('psikolog.konsultasi.index') }}"
               class="group relative flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold transition duration-200
               {{ request()->routeIs('psikolog.konsultasi.*') ? 'bg-white text-[#061A33] shadow-lg' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('psikolog.konsultasi.*') ? 'bg-[#EAF1FF]' : 'bg-white/10 group-hover:bg-white/15' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.82L3 20l1.25-3.33A7.46 7.46 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </span>

                <span class="flex-1">Konsultasi</span>

                @if($jumlahKonsultasiMasuk > 0)
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-rose-600 px-1.5 text-[10px] font-black text-white shadow-md">
                        {{ $jumlahKonsultasiMasuk }}
                    </span>
                @endif
            </a>

            <a href="{{ route('psikolog.pasien.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold transition duration-200
               {{ request()->routeIs('psikolog.pasien.*') ? 'bg-white text-[#061A33] shadow-lg' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('psikolog.pasien.*') ? 'bg-[#EAF1FF]' : 'bg-white/10 group-hover:bg-white/15' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                </span>
                Data Pasien
            </a>

            <a href="{{ route('psikolog.hasil-konsultasi.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold transition duration-200
               {{ request()->routeIs('psikolog.hasil-konsultasi.*') ? 'bg-white text-[#061A33] shadow-lg' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('psikolog.hasil-konsultasi.*') ? 'bg-[#EAF1FF]' : 'bg-white/10 group-hover:bg-white/15' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/>
                    </svg>
                </span>
                Hasil Konsultasi
            </a>

            <a href="{{ route('psikolog.rujukan-psikiater.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold transition duration-200
               {{ request()->routeIs('psikolog.rujukan-psikiater.*') ? 'bg-white text-[#061A33] shadow-lg' : 'text-blue-100/70 hover:bg-white/10 hover:text-white' }}">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('psikolog.rujukan-psikiater.*') ? 'bg-[#EAF1FF]' : 'bg-white/10 group-hover:bg-white/15' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m4-4H8m12 0a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                </span>
                Surat Rujukan
            </a>

        </nav>

        <form action="{{ route('logout') }}" method="POST" class="mt-auto pt-6">
            @csrf
            <button type="submit"
                    class="w-full h-11 rounded-xl bg-white/10 px-5 text-xs font-bold text-white transition duration-200 hover:bg-white hover:text-[#061A33]">
                Logout 
            </button>
        </form>

    </div>

</aside>