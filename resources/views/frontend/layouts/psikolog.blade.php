<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Dashboard Psikolog')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#EEF3FA] text-slate-900 antialiased">

<div class="min-h-screen overflow-x-hidden">

    {{-- LAYER OVERLAY HITAM TRANSPARAN DI HP SAAT MENU AKTIF --}}
    <div id="sidebarOverlayPsikolog" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm hidden transition-opacity duration-300 lg:hidden"></div>

    @if(View::exists('frontend.partials.psikolog.sidebar'))
        @include('frontend.partials.psikolog.sidebar')
    @elseif(View::exists('frontend.layouts.partials.psikolog.sidebar'))
        @include('frontend.layouts.partials.psikolog.sidebar')
    @endif

    {{-- KONTEN UTAMA DENGAN DYNAMIC PADDING KIRI --}}
    <main class="min-h-screen transition-all duration-300 lg:pl-[270px]">

        @if(View::exists('frontend.partials.psikolog.navbar'))
            @include('frontend.partials.psikolog.navbar')
        @elseif(View::exists('frontend.layouts.partials.psikolog.navbar'))
            @include('frontend.layouts.partials.psikolog.navbar')
        @endif

        <section class="mx-auto w-full max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8 xl:px-10">
            @yield('content')
        </section>

    </main>

</div>

{{-- SCRIPT JAVASCRIPT TOGGLE HAMBURGER MENU RESPONSIVE --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hamburgerBtn = document.getElementById('mobileMenuButton');
        const sidebarEl = document.getElementById('psikologMainSidebar');
        const overlayEl = document.getElementById('sidebarOverlayPsikolog');

        if (!hamburgerBtn || !sidebarEl || !overlayEl) return;

        function toggleSidebar() {
            sidebarEl.classList.toggle('-translate-x-full');
            overlayEl.classList.toggle('hidden');
        }

        function closeSidebar() {
            sidebarEl.classList.add('-translate-x-full');
            overlayEl.classList.add('hidden');
        }

        hamburgerBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleSidebar();
        });

        overlayEl.addEventListener('click', closeSidebar);
        
        // Pengaman: jika ukuran layar di-resize ke desktop, bersihkan overlay otomatis
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    });
</script>

</body>
</html>