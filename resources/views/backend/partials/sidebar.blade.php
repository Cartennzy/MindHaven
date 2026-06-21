<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col overflow-hidden bg-[#01588E] text-white shadow-2xl lg:flex">

    {{-- BACKGROUND --}}
    <div class="pointer-events-none absolute -top-24 left-10 h-64 w-64 rounded-full bg-sky-300/20 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-20 -right-24 h-72 w-72 rounded-full bg-emerald-300/10 blur-3xl"></div>

    {{-- BRAND --}}
    <div class="relative flex h-28 items-center border-b border-white/10 px-6">

        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-4">

            <div class="flex h-[4.6rem] w-[4.6rem] items-center justify-center overflow-hidden rounded-[1.6rem] bg-white shadow-xl ring-1 ring-white/40">
                <img
                    src="{{ asset('assets/images/logo_polos.png') }}"
                    alt="MindHaven Logo"
                    class="h-16 w-16 scale-150 object-contain transition duration-300 group-hover:scale-[1.7]"
                >
            </div>

            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    MindHaven
                </h1>
                <p class="mt-1 text-xs font-medium text-blue-100/80">
                    Admin Panel
                </p>
            </div>

        </a>

    </div>

    {{-- MENU --}}
    <nav class="relative flex-1 overflow-y-auto px-5 py-6 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

        <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-[0.22em] text-blue-100/70">
            Menu
        </p>

        @php
            $menus = [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'active' => 'admin.dashboard',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5Z"/>',
                ],
                
                [
                    'label' => 'Data Pasien',
                    'route' => 'admin.pasien.index',
                    'active' => 'admin.pasien.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M20 21v-2a4 4 0 0 0-3-3.87"/>',
                ],
                [
                    'label' => 'Data Psikolog',
                    'route' => 'admin.psikolog.index',
                    'active' => 'admin.psikolog.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 1 0 6 0 3 3 0 0 0-6 0ZM4 12a8 8 0 1 1 16 0v2"/>',
                ],
                [
                    'label' => 'Data Psikiater',
                    'route' => 'admin.psikiater.index',
                    'active' => 'admin.psikiater.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 9v6M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z"/>',
                ],
                [
                    'label' => 'Data Rumah Sakit',
                    'route' => 'admin.rumah-sakit.index',
                    'active' => 'admin.rumah-sakit.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16M9 21v-6h6v6M9 8h6M12 5v6"/>',
                ],
                [
                    'label' => 'Konsultasi',
                    'route' => 'admin.konsultasi.index',
                    'active' => 'admin.konsultasi.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 7 4-4h10a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v11a3 3 0 0 0 3 3Z"/>',
                ],
                [
                    'label' => 'Pembayaran',
                    'route' => 'admin.pembayaran.index',
                    'active' => 'admin.pembayaran.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M5 8V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v2M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8"/>',
                ],
                [
                    'label' => 'Laporan',
                    'route' => 'admin.laporan.index',
                    'active' => 'admin.laporan.*',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2Zm3 6h6M10 13h6M10 17h4"/>',
                ],
            ];
        @endphp

        <div class="space-y-2">
            @foreach($menus as $menu)
                <a href="{{ route($menu['route']) }}"
                   class="group relative flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold transition duration-300
                   {{ request()->routeIs($menu['active'])
                        ? 'bg-white/20 text-white shadow-lg ring-1 ring-white/15'
                        : 'text-blue-50/90 hover:translate-x-1 hover:bg-white/12 hover:text-white' }}">

                    @if(request()->routeIs($menu['active']))
                        <span class="absolute left-0 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-white"></span>
                    @endif

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl transition duration-300
                        {{ request()->routeIs($menu['active']) ? 'bg-white/20' : 'bg-white/10 group-hover:bg-white/15' }}">
                        <svg class="h-5 w-5 transition duration-300 group-hover:scale-110"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            {!! $menu['icon'] !!}
                        </svg>
                    </span>

                    <span>{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- KONTEN --}}
        <div class="pt-7">

            <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-[0.22em] text-blue-100/70">
                Konten
            </p>

            <div class="space-y-2">

                <a href="{{ route('admin.artikel.index') }}"
                   class="group relative flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold transition duration-300
                   {{ request()->routeIs('admin.artikel.*')
                        ? 'bg-white/20 text-white shadow-lg ring-1 ring-white/15'
                        : 'text-blue-50/90 hover:translate-x-1 hover:bg-white/12 hover:text-white' }}">

                    @if(request()->routeIs('admin.artikel.*'))
                        <span class="absolute left-0 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-white"></span>
                    @endif

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('admin.artikel.*') ? 'bg-white/20' : 'bg-white/10 group-hover:bg-white/15' }}">
                        <svg class="h-5 w-5 transition duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/>
                        </svg>
                    </span>

                    <span>Artikel</span>
                </a>

                <a href="{{ route('admin.meditasi.index') }}"
                   class="group relative flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold transition duration-300
                   {{ request()->routeIs('admin.meditasi.*')
                        ? 'bg-white/20 text-white shadow-lg ring-1 ring-white/15'
                        : 'text-blue-50/90 hover:translate-x-1 hover:bg-white/12 hover:text-white' }}">

                    @if(request()->routeIs('admin.meditasi.*'))
                        <span class="absolute left-0 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-white"></span>
                    @endif

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ request()->routeIs('admin.meditasi.*') ? 'bg-white/20' : 'bg-white/10 group-hover:bg-white/15' }}">
                        <svg class="h-5 w-5 transition duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2 3 4 5 4 9a4 4 0 0 1-8 0c0-4 2-6 4-9Z"/>
                        </svg>
                    </span>

                    <span>Meditasi</span>
                </a>

            </div>

        </div>

    </nav>

    {{-- FOOTER --}}
    <div class="relative border-t border-white/10 p-5">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                    class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-white/10 px-5 py-3.5 text-sm font-semibold text-white transition duration-300 hover:bg-red-500 hover:shadow-lg">

                <svg class="h-5 w-5 transition duration-300 group-hover:-translate-x-1"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12H3m0 0 4-4m-4 4 4 4m13-12v16"/>
                </svg>

                <span>Logout</span>

            </button>
        </form>

    </div>

</aside>