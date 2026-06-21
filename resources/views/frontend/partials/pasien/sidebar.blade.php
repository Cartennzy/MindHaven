<aside id="mobileSidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-hidden border-r border-slate-200 bg-[#F8FBFD] transition-transform duration-300 lg:translate-x-0 lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:w-72">

    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-white via-[#F8FBFD] to-[#EEF7FA]"></div>
    <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-[#01588E]/5 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 -left-24 h-72 w-72 rounded-full bg-[#41AD01]/5 blur-3xl"></div>

    <div class="relative shrink-0 border-b border-slate-100 px-6 py-6">
        <a href="{{ route('pasien.dashboard') }}" class="group flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white shadow-[0_10px_30px_rgba(1,88,142,0.08)] transition-all duration-300 group-hover:scale-105">
                <img src="{{ asset('assets/images/logo_polos.png') }}"
                     alt="MindHaven Logo"
                     class="h-12 w-12 object-contain">
            </div>

            <div class="min-w-0">
                <h1 class="text-[1.5rem] font-extrabold tracking-tight text-[#01588E] transition duration-300 group-hover:text-[#01446e]">
                    MindHaven
                </h1>
            </div>
        </a>
    </div>

    <nav class="relative flex-1 overflow-y-auto px-4 py-5 scrollbar-thin scrollbar-track-transparent scrollbar-thumb-[#01588E]/20 hover:scrollbar-thumb-[#01588E]/40">

        <p class="mb-5 px-4 text-[11px] font-black uppercase tracking-[0.24em] text-slate-400">
            Pasien Menu
        </p>

        <div class="space-y-2 pb-6">

            <a href="{{ route('pasien.dashboard') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.dashboard')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.dashboard') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z"/>
                    </svg>
                </span>
                Dashboard
            </a>

            <a href="{{ route('pasien.konsultasi.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.konsultasi.index') || request()->routeIs('pasien.konsultasi.create') || request()->routeIs('pasien.konsultasi.show') || request()->routeIs('pasien.konsultasi.detail-psikolog') || request()->routeIs('pasien.konsultasi.metode') || request()->routeIs('pasien.konsultasi.store-metode')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.konsultasi.index') || request()->routeIs('pasien.konsultasi.create') || request()->routeIs('pasien.konsultasi.show') || request()->routeIs('pasien.konsultasi.detail-psikolog') || request()->routeIs('pasien.konsultasi.metode') || request()->routeIs('pasien.konsultasi.store-metode') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                    </svg>
                </span>
                Konsultasi
            </a>

            <a href="{{ route('pasien.hasil-konsultasi.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.hasil-konsultasi.*') || request()->routeIs('pasien.konsultasi.hasil')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.hasil-konsultasi.*') || request()->routeIs('pasien.konsultasi.hasil') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13H7V3Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M10 13h6M10 17h6"/>
                    </svg>
                </span>
                Hasil Konsultasi
            </a>

            <a href="{{ route('pasien.pembayaran.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.pembayaran.*')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.pembayaran.*') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3V7Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h4"/>
                    </svg>
                </span>
                Pembayaran
            </a>

            <a href="{{ route('pasien.rujukan-psikiater.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.rujukan-psikiater.*')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.rujukan-psikiater.*') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 10h6M12 7v6"/>
                    </svg>
                </span>
                Surat Rujukan
            </a>

            <a href="{{ route('pasien.meditasi.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.meditasi.*')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.meditasi.*') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c4-3 6-6 6-10a6 6 0 1 0-12 0c0 4 2 7 6 10Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12c1 1 2 1.5 3 1.5s2-.5 3-1.5"/>
                    </svg>
                </span>
                Meditasi
            </a>

            {{-- INTEGRASI: Sisipan Tombol Fitur Baru Self-Assessment Pasien --}}
            <a href="{{ route('pasien.self-assessment.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.self-assessment.*')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.self-assessment.*') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9l2 2 4-4"/>
                    </svg>
                </span>
                Self-Assessment
            </a>

            <a href="{{ route('pasien.profile.index') }}"
               class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition-all duration-300
               {{ request()->routeIs('pasien.profile.*')
                    ? 'bg-[#01588E] text-white shadow-[0_14px_30px_rgba(1,88,142,0.20)]'
                    : 'text-slate-600 hover:bg-white hover:text-[#01588E] hover:shadow-[0_10px_25px_rgba(1,88,142,0.08)]' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl transition-all duration-300
                {{ request()->routeIs('pasien.profile.*') ? 'bg-white/15' : 'bg-[#EEF7FC] text-[#01588E] group-hover:bg-[#01588E] group-hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </span>
                Profil
            </a>

        </div>

    </nav>

    <div class="relative shrink-0 border-t border-slate-100 bg-white p-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#01588E] px-4 py-3 text-sm font-black text-white transition-all duration-300 hover:bg-red-600 hover:shadow-[0_0_30px_rgba(220,38,38,0.25)]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0 4-4m-4 4 4 4M9 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H9"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('mobileSidebar');
    const button = document.getElementById('mobileMenuButton');
    const overlay = document.getElementById('mobileSidebarOverlay');

    if (!sidebar || !button || !overlay) return;

    button.addEventListener('click', function () {

        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');

    });

    overlay.addEventListener('click', function () {

        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');

    });

    window.addEventListener('resize', function () {

        if (window.innerWidth >= 1024) {

            overlay.classList.add('hidden');
            sidebar.classList.remove('-translate-x-full');

        } else {

            sidebar.classList.add('-translate-x-full');

        }

    });

});
</script>