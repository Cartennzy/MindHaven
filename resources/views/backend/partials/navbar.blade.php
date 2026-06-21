<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">

    @php
        $adminNotifications = collect();
        $adminUnreadCount = 0;

        if (auth()->check() && class_exists(\App\Models\Notifikasi::class)) {
            $adminNotifications = \App\Models\Notifikasi::where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();

            $adminUnreadCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();
        }
    @endphp

    <div class="px-5 lg:px-10">

        <div class="flex min-h-24 items-center justify-between gap-5">

            {{-- TITLE --}}
            <div class="min-w-0">
                <h2 class="truncate text-2xl font-extrabold text-[#01588E] lg:text-3xl">
                    Dashboard Admin
                </h2>
            </div>

            {{-- RIGHT --}}
            <div class="flex shrink-0 items-center gap-4">

                {{-- NOTIFIKASI --}}
                <div class="group relative">
                    <button type="button"
                            class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white text-[#01588E] shadow-sm transition hover:bg-[#01588E] hover:text-white">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5m6 0a3 3 0 0 1-6 0"/>
                        </svg>

                        @if($adminUnreadCount > 0)
                            <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                            </span>
                        @endif

                    </button>

                    <div class="invisible absolute right-0 top-14 z-50 w-96 translate-y-2 rounded-[1.5rem] border border-slate-100 bg-white p-4 opacity-0 shadow-[0_24px_70px_rgba(15,23,42,.16)] transition duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">

                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">
                                    Notifikasi Admin
                                </h3>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Pengajuan kerja sama dan aktivitas terbaru.
                                </p>
                            </div>

                            @if($adminUnreadCount > 0)
                                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                                    {{ $adminUnreadCount }} baru
                                </span>
                            @endif
                        </div>

                        <div class="max-h-80 space-y-2 overflow-y-auto pr-1">
                            @forelse($adminNotifications as $notification)

                                <a href="{{ route('admin.psikolog.index') }}"
                                   class="block rounded-2xl border border-slate-100 p-3 transition hover:bg-slate-50">

                                    <div class="flex gap-3">
                                        <div class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-[#01588E]/10 text-[#01588E]' }}">
                                            <svg class="h-4 w-4"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M20 21v-2a4 4 0 0 0-3-3.87"/>
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-2">
                                                <p class="line-clamp-1 text-sm font-semibold text-slate-900">
                                                    {{ $notification->judul ?? 'Notifikasi Baru' }}
                                                </p>

                                                @if(!$notification->is_read)
                                                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-red-500"></span>
                                                @endif
                                            </div>

                                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                                {{ $notification->pesan ?? '-' }}
                                            </p>

                                            <p class="mt-2 text-[11px] font-medium text-slate-400">
                                                {{ $notification->created_at ? $notification->created_at->diffForHumans() : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                </a>

                            @empty

                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-center">
                                    <p class="text-sm font-semibold text-slate-600">
                                        Belum ada notifikasi
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Notifikasi pengajuan psikolog akan muncul di sini.
                                    </p>
                                </div>

                            @endforelse
                        </div>

                    </div>
                </div>

                <div class="hidden text-right md:block">
                    <h4 class="text-sm font-bold text-slate-800">
                        {{ auth()->user()->name ?? 'Admin MindHaven' }}
                    </h4>

                    <p class="text-xs text-slate-500">
                        {{ auth()->user()->email ?? 'admin@mindhaven.com' }}
                    </p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-lg font-black text-[#01588E]">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>

            </div>

        </div>

    </div>

</header>