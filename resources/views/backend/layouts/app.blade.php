<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin MindHaven')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-800 antialiased overflow-x-hidden">

<div class="min-h-screen">

    {{-- Sidebar --}}
    <aside class="fixed left-0 top-0 z-40 hidden h-screen w-72 overflow-y-auto bg-[#01588E] lg:block">
        @include('backend.partials.sidebar')
    </aside>

    {{-- Main Content --}}
    <div class="min-h-screen lg:pl-72">

        {{-- Navbar --}}
        @include('backend.partials.navbar')

        {{-- Content --}}
        <main class="w-full max-w-full overflow-x-hidden px-5 py-6 lg:px-10 lg:py-8">

            @if(session('success'))
                <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')

        </main>

        @include('backend.partials.footer')

    </div>

</div>

</body>
</html>