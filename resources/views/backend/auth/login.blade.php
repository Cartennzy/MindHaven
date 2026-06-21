<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin & Psikolog | MindHaven</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F3F6FA] text-slate-900 antialiased">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-[52%_48%]">

    {{-- LEFT VISUAL --}}
    <section class="relative hidden min-h-screen overflow-hidden bg-[#28AEDA] px-14 py-12 text-white lg:flex lg:flex-col lg:justify-center">

        <div class="absolute left-10 top-0 h-20 w-20 -translate-y-1/2 rounded-full bg-white"></div>

        <div class="absolute left-28 top-0 flex gap-3">
            <div class="h-24 w-5 rounded-b-full bg-white/35"></div>
            <div class="h-32 w-8 rounded-b-full border border-white/70 bg-white/10"></div>
            <div class="h-44 w-10 rounded-b-full bg-white/35"></div>
        </div>

        <div class="absolute left-8 top-20 grid grid-cols-6 gap-3 opacity-80">
            @for($i = 0; $i < 36; $i++)
                <span class="h-1 w-1 rounded-full bg-white"></span>
            @endfor
        </div>

        <div class="absolute right-40 top-14 h-14 w-14 rounded-full border-[6px] border-white/90">
            <div class="absolute right-1 top-1 h-4 w-4 rounded-full bg-[#28AEDA]"></div>
        </div>

        <div class="absolute right-28 top-10 h-4 w-4 rounded-full bg-cyan-200 shadow-[0_0_25px_rgba(255,255,255,0.8)]"></div>

        <div class="relative z-10 max-w-xl">
            <h1 class="text-5xl font-extrabold leading-tight tracking-tight xl:text-6xl">
                MindHaven<br>
                start here
            </h1>

            <p class="mt-6 max-w-md text-lg font-medium leading-8 text-white/90">
                Masuk ke dashboard MindHaven untuk mengelola konsultasi, data pasien, hasil konsultasi, dan layanan kesehatan mental.
            </p>
        </div>

        <div class="absolute bottom-24 left-12 h-12 w-12 rounded-full bg-cyan-300 shadow-[0_0_35px_rgba(255,255,255,0.6)]"></div>

        <div class="absolute bottom-8 left-10 grid grid-cols-6 gap-3 opacity-80">
            @for($i = 0; $i < 30; $i++)
                <span class="h-1 w-1 rounded-full bg-white"></span>
            @endfor
        </div>

        <div class="absolute -bottom-40 right-16 h-72 w-72 rounded-full border-[10px] border-white/90"></div>
        <div class="absolute -bottom-24 right-28 h-56 w-56 rounded-full border border-white/40"></div>
        <div class="absolute bottom-20 right-24 h-14 w-14 rounded-full bg-cyan-300 shadow-[0_0_35px_rgba(255,255,255,0.6)]"></div>

    </section>

    {{-- RIGHT FORM --}}
    <section class="flex min-h-screen items-center justify-center px-6 py-10 lg:px-16">

        <div class="w-full max-w-[380px]">

            <div class="mb-8 flex justify-center lg:hidden">
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white shadow-[0_20px_55px_rgba(40,174,218,0.18)]">
                    <img src="{{ asset('assets/images/logo_polos.png') }}"
                         alt="MindHaven Logo"
                         class="h-16 w-16 object-contain">
                </div>
            </div>

            <div class="mb-7 hidden justify-center lg:flex">
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white shadow-[0_20px_55px_rgba(15,23,42,0.12)]">
                    <img src="{{ asset('assets/images/logo_polos.png') }}"
                         alt="MindHaven Logo"
                         class="h-16 w-16 object-contain">
                </div>
            </div>

            <div class="mb-7 text-center">
                <h2 class="text-2xl font-semibold tracking-tight text-slate-800">
                    Halo! Selamat datang kembali
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Login Admin & Psikolog MindHaven
                </p>
            </div>

            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('backend.login.process') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Email
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#28AEDA]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                        </span>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Masukkan alamat email"
                               required
                               autofocus
                               class="w-full rounded-md border border-slate-200 bg-white py-4 pl-12 pr-4 text-sm font-medium text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#28AEDA]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>

                        <input type="password"
                               name="password"
                               placeholder="Masukkan password"
                               required
                               class="w-full rounded-md border border-slate-200 bg-white py-4 pl-12 pr-4 text-sm font-medium text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#28AEDA] focus:ring-4 focus:ring-[#28AEDA]/10">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-500">
                        <input type="checkbox"
                               name="remember"
                               class="h-4 w-4 rounded border-slate-300 text-[#28AEDA] focus:ring-[#28AEDA]">
                        Ingat saya
                    </label>

                    <a href="{{ route('home') }}"
                       class="text-xs font-semibold text-[#28AEDA] hover:underline">
                        Kembali ke Beranda
                    </a>
                </div>

                <button type="submit"
                        class="w-full rounded-md bg-[#28AEDA] px-5 py-4 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(40,174,218,0.35)] transition duration-300 hover:-translate-y-0.5 hover:bg-[#159AC7]">
                    Login
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-500">
                Kembali ke halaman utama?
                <a href="{{ route('home') }}" class="font-semibold text-[#28AEDA] hover:underline">
                    Beranda
                </a>
            </p>

        </div>

    </section>

</div>

</body>
</html>