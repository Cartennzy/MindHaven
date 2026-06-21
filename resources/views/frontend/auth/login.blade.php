@extends('frontend.layouts.guest')

@section('title', 'Login MindHaven')

@section('content')

<style>
    html {
        scroll-behavior: smooth;
    }

    body {
        background: #F4F6FB;
    }

    .mh-login-page {
        min-height: 100vh;
        background: #F4F6FB;
    }

    .mh-blue-panel {
        position: relative;
        overflow: hidden;
        background: #28AEDA;
    }

    .mh-dot-grid {
        position: absolute;
        width: 92px;
        height: 92px;
        background-image: radial-gradient(rgba(255,255,255,.78) 1.4px, transparent 1.4px);
        background-size: 13px 13px;
        opacity: .75;
    }

    .mh-orb {
        position: absolute;
        border-radius: 9999px;
        background: radial-gradient(circle at 35% 30%, #A8F5FF, #4DD2F2 55%, #28AEDA);
        box-shadow: 0 16px 45px rgba(40, 174, 218, .32);
    }

    .mh-ring {
        position: absolute;
        border-radius: 9999px;
        border: 9px solid rgba(255,255,255,.9);
        box-shadow: inset 0 0 0 2px rgba(255,255,255,.15);
    }

    .mh-input {
        width: 100%;
        height: 44px;
        border: 1px solid #E7EAF2;
        background: #FFFFFF;
        padding-left: 44px;
        padding-right: 44px;
        font-size: 13px;
        font-weight: 600;
        color: #26324D;
        outline: none;
        transition: all .22s ease;
        box-shadow: 0 8px 22px rgba(23, 34, 77, .035);
    }

    .mh-input::placeholder {
        color: #B5BDD0;
        font-weight: 600;
    }

    .mh-input:focus {
        border-color: #28AEDA;
        box-shadow: 0 0 0 4px rgba(40,174,218,.13);
    }

    .mh-input-error {
        border-color: #EF4444 !important;
        background: #FFF7F7;
        box-shadow: 0 0 0 4px rgba(239,68,68,.10) !important;
    }

    .mh-label {
        display: block;
        margin-bottom: 9px;
        font-size: 12px;
        font-weight: 700;
        color: #4B5368;
    }

    .mh-error-text {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #DC2626;
    }

    .mh-alert-error {
        margin-bottom: 20px;
        border-radius: 14px;
        border: 1px solid #FECACA;
        background: #FEF2F2;
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.6;
        color: #B91C1C;
        box-shadow: 0 12px 28px rgba(239, 68, 68, .10);
    }

    .mh-alert-success {
        margin-bottom: 20px;
        border-radius: 14px;
        border: 1px solid #BBF7D0;
        background: #F0FDF4;
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.6;
        color: #15803D;
        box-shadow: 0 12px 28px rgba(34, 197, 94, .10);
    }

    .mh-reveal {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity .75s ease, transform .75s ease;
    }

    .mh-reveal.mh-show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="mh-login-page flex min-h-screen">

    <section class="mh-blue-panel hidden min-h-screen w-[54%] px-14 py-12 text-white lg:flex">
        <div class="mh-dot-grid left-7 top-20"></div>
        <div class="mh-dot-grid bottom-8 left-7"></div>

        <div class="absolute left-32 top-0 h-28 w-5 rounded-b-full bg-white/25"></div>
        <div class="absolute left-40 top-0 h-36 w-9 rounded-b-full border border-white/40 bg-white/10"></div>
        <div class="absolute left-52 top-0 h-44 w-9 rounded-b-full bg-white/30"></div>

        <div class="absolute right-44 top-10 h-11 w-11 rounded-full border-[5px] border-white/90 border-l-transparent"></div>
        <div class="absolute right-40 top-8 h-3 w-3 rounded-full bg-[#79F1EA]"></div>

        <div class="mh-orb bottom-20 left-9 h-10 w-10"></div>
        <div class="mh-ring -bottom-36 right-24 h-72 w-72"></div>
        <div class="mh-ring -bottom-28 right-40 h-48 w-48 border-[3px] opacity-50"></div>
        <div class="mh-orb bottom-36 right-28 h-12 w-12"></div>

        <div class="relative z-10 flex min-h-full w-full flex-col justify-center">
            <div class="mh-reveal max-w-lg">
                <h1 class="text-[54px] font-black leading-[1.05] tracking-tight">
                    Tenang<br>
                    mulai di sini
                </h1>

                <p class="mt-7 max-w-sm text-[17px] font-medium leading-8 text-white/90">
                    Masuk ke akun MindHaven untuk melanjutkan konsultasi, melihat hasil, dan mengelola layanan kesehatan mental.
                </p>
            </div>
        </div>
    </section>

    <section class="flex min-h-screen flex-1 items-center justify-center px-6 py-10">
        <div class="mh-reveal w-full max-w-[380px]">

            <div class="mb-7 text-center">
                <a href="{{ route('home') }}"
                   class="mx-auto mb-6 flex h-[74px] w-[74px] items-center justify-center rounded-xl bg-white shadow-[0_12px_35px_rgba(23,34,77,.10)] ring-1 ring-slate-100">
                    <img src="{{ asset('assets/images/logo_polos.png') }}"
                         alt="MindHaven Logo"
                         class="h-14 w-14 object-contain">
                </a>

                <h2 class="text-xl font-extrabold tracking-tight text-[#30364A]">
                    Halo, Selamat Datang Kembali
                </h2>
                <p class="mt-2 text-sm font-medium text-slate-400">
                    Login untuk masuk ke layanan MindHaven
                </p>
            </div>

            @if($errors->has('login') && !$errors->has('email') && !$errors->has('password'))
                <div class="mh-alert-error">
                    {{ $errors->first('login') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mh-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mh-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" class="space-y-5" novalidate>
                @csrf

                <div>
                    <label class="mh-label">Email</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 flex -translate-y-1/2 {{ $errors->has('email') ? 'text-red-500' : 'text-[#28AEDA]' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="mh-input {{ $errors->has('email') ? 'mh-input-error' : '' }}"
                               placeholder="Masukkan email kamu">
                    </div>

                    @error('email')
                        <p class="mh-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mh-label">Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 flex -translate-y-1/2 {{ $errors->has('password') ? 'text-red-500' : 'text-[#28AEDA]' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V8a5 5 0 00-10 0v3H6a2 2 0 00-2 2v6a2 2 0 002 2zm3-10V8a3 3 0 016 0v3"/>
                            </svg>
                        </span>

                        <input type="password"
                               name="password"
                               id="passwordInput"
                               class="mh-input {{ $errors->has('password') ? 'mh-input-error' : '' }}"
                               placeholder="Masukkan password">

                        <button type="button"
                                id="togglePassword"
                                class="absolute right-4 top-1/2 flex -translate-y-1/2 {{ $errors->has('password') ? 'text-red-500' : 'text-slate-400' }} transition hover:text-[#28AEDA]">
                            <svg id="eyeOpen" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>

                            <svg id="eyeClosed" class="hidden" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.223-3.592m3.31-2.13A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.293 5.118M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mh-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between gap-3">
                    <label class="flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate-500">
                        <input type="checkbox"
                               name="remember"
                               class="h-4 w-4 rounded border-slate-200 text-[#28AEDA] focus:ring-[#28AEDA]">
                        Ingat saya
                    </label>

                    <span class="text-xs font-bold text-[#28AEDA]">
                        MindHaven
                    </span>
                </div>

                <button type="submit"
                        class="w-full bg-[#28AEDA] px-6 py-3.5 text-sm font-bold text-white shadow-[0_16px_35px_rgba(40,174,218,.32)] transition hover:-translate-y-0.5 hover:bg-[#159AC7]">
                    Login
                </button>
            </form>

            <div class="my-6 flex items-center gap-4">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs font-semibold text-slate-400">atau</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <p class="text-center text-xs font-semibold text-slate-500">
                Belum punya akun pasien?
                <a href="{{ route('register') }}" class="font-bold text-[#28AEDA] hover:underline">
                    Daftar Akun
                </a>
            </p>

        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const revealElements = document.querySelectorAll('.mh-reveal');

        const revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('mh-show');
                }
            });
        }, {
            threshold: 0.12
        });

        revealElements.forEach(function (element) {
            revealObserver.observe(element);
        });

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (togglePassword && passwordInput && eyeOpen && eyeClosed) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';

                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeOpen.classList.toggle('hidden', isPassword);
                eyeClosed.classList.toggle('hidden', !isPassword);
            });
        }
    });
</script>

@endsection