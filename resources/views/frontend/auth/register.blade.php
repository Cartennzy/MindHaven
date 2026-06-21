@extends('frontend.layouts.guest')

@section('title', 'Register Pasien - MindHaven')

@section('content')

<style>
    html { scroll-behavior: smooth; }
    body { background: #EAF7FC; }

    .mh-register-page {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        background:
            radial-gradient(circle at 15% 18%, rgba(40, 174, 218, .32), transparent 34%),
            radial-gradient(circle at 85% 12%, rgba(65, 173, 1, .15), transparent 28%),
            radial-gradient(circle at 70% 88%, rgba(40, 174, 218, .24), transparent 32%),
            linear-gradient(135deg, #EAF9FF 0%, #F7FBFF 45%, #EEF4FA 100%);
    }

    .mh-bg-orb {
        position: absolute;
        border-radius: 9999px;
        filter: blur(8px);
        opacity: .9;
        pointer-events: none;
    }

    .mh-bg-orb-1 {
        width: 360px;
        height: 360px;
        left: -120px;
        top: -100px;
        background: radial-gradient(circle, rgba(40,174,218,.52), rgba(40,174,218,.08));
    }

    .mh-bg-orb-2 {
        width: 300px;
        height: 300px;
        right: -90px;
        top: 120px;
        background: radial-gradient(circle, rgba(65,173,1,.22), rgba(65,173,1,.04));
    }

    .mh-bg-orb-3 {
        width: 440px;
        height: 440px;
        right: 16%;
        bottom: -220px;
        background: radial-gradient(circle, rgba(40,174,218,.28), rgba(40,174,218,.03));
    }

    .mh-dot-grid {
        position: absolute;
        width: 120px;
        height: 120px;
        background-image: radial-gradient(rgba(40,174,218,.30) 1.4px, transparent 1.4px);
        background-size: 14px 14px;
        opacity: .72;
        pointer-events: none;
    }

    .mh-register-layout {
        position: relative;
        z-index: 5;
        width: 100%;
        min-height: 100vh;
        display: grid;
        grid-template-columns: .9fr 1.1fr;
    }

    .mh-brand-panel {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 64px 70px;
        color: white;
        background:
            linear-gradient(145deg, rgba(40,174,218,.98), rgba(17,152,202,.96)),
            radial-gradient(circle at 25% 25%, rgba(255,255,255,.25), transparent 30%);
    }

    .mh-brand-panel::before {
        content: "";
        position: absolute;
        inset: 28px;
        border-radius: 34px;
        border: 1px solid rgba(255,255,255,.20);
        pointer-events: none;
    }

    .mh-brand-panel::after {
        content: "";
        position: absolute;
        width: 390px;
        height: 390px;
        right: -175px;
        bottom: -150px;
        border-radius: 9999px;
        border: 48px solid rgba(255,255,255,.13);
    }

    .mh-brand-title {
        margin-top: 145px;
        max-width: 470px;
        font-size: clamp(44px, 5.4vw, 76px);
        line-height: .98;
        letter-spacing: -0.06em;
        font-weight: 950;
    }

    .mh-brand-desc {
        margin-top: 28px;
        max-width: 470px;
        font-size: 17px;
        line-height: 1.9;
        font-weight: 650;
        color: rgba(255,255,255,.88);
    }

    .mh-benefit-list {
        margin-top: 34px;
        display: grid;
        gap: 12px;
        max-width: 440px;
    }

    .mh-benefit-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.13);
        font-size: 13px;
        font-weight: 750;
        color: rgba(255,255,255,.93);
    }

    .mh-benefit-icon {
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        border-radius: 999px;
        background: rgba(255,255,255,.20);
        font-size: 13px;
        font-weight: 900;
    }

    .mh-form-panel {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 70px;
        background: rgba(255,255,255,.20);
        backdrop-filter: blur(26px);
        -webkit-backdrop-filter: blur(26px);
    }

    .mh-form-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(120deg, rgba(255,255,255,.40), rgba(255,255,255,.10)),
            radial-gradient(circle at 50% 18%, rgba(255,255,255,.75), transparent 32%);
        pointer-events: none;
    }

    .mh-form-card {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 700px;
        border-radius: 32px;
        border: 1px solid rgba(255,255,255,.82);
        background: rgba(255,255,255,.70);
        backdrop-filter: blur(34px);
        -webkit-backdrop-filter: blur(34px);
        box-shadow: 0 28px 80px rgba(21, 43, 80, .15), inset 0 1px 0 rgba(255,255,255,.95);
        padding: 32px;
    }

    .mh-form-content {
        position: relative;
        z-index: 2;
    }

    .mh-logo-card {
        margin: 0 auto 16px;
        height: 78px;
        width: 78px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 22px;
        border: 1px solid rgba(255,255,255,.78);
        background: rgba(255,255,255,.74);
        box-shadow: 0 18px 42px rgba(21, 43, 80, .12), inset 0 1px 0 rgba(255,255,255,.95);
    }

    .mh-page-title {
        text-align: center;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 950;
        letter-spacing: -.035em;
        color: #24304A;
    }

    .mh-page-subtitle {
        margin-top: 9px;
        text-align: center;
        font-size: 13px;
        line-height: 1.7;
        font-weight: 650;
        color: #7A879D;
    }

    .mh-section-title {
        margin: 26px 0 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        font-weight: 900;
        color: #18A9D7;
        letter-spacing: .095em;
        text-transform: uppercase;
    }

    .mh-section-icon {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(40,174,218,.18), rgba(40,174,218,.08));
        color: #18A9D7;
        border: 1px solid rgba(40,174,218,.18);
        box-shadow: 0 8px 18px rgba(40,174,218,.12);
    }

    .mh-section-icon svg {
        width: 16px;
        height: 16px;
    }

    .mh-label {
        display: block;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 850;
        color: #46536B;
    }

    .mh-input,
    .mh-textarea {
        width: 100%;
        border-radius: 16px;
        border: 1px solid rgba(207, 216, 229, .88);
        background: rgba(255,255,255,.74);
        padding: 13px 15px;
        font-size: 13px;
        font-weight: 700;
        color: #24304A;
        outline: none;
        transition: all .22s ease;
        box-shadow: 0 10px 26px rgba(27, 48, 82, .055), inset 0 1px 0 rgba(255,255,255,.85);
    }

    .mh-input { height: 48px; }

    .mh-textarea {
        min-height: 96px;
        resize: none;
        line-height: 1.7;
    }

    .mh-input:focus,
    .mh-textarea:focus {
        border-color: rgba(40,174,218,.85);
        background: rgba(255,255,255,.92);
        box-shadow: 0 0 0 5px rgba(40,174,218,.15), 0 16px 34px rgba(40,174,218,.12);
        transform: translateY(-1px);
    }

    .mh-input-error,
    .mh-textarea-error {
        border-color: #EF4444 !important;
        background: #FFF7F7 !important;
        box-shadow: 0 0 0 5px rgba(239,68,68,.10) !important;
    }

    .mh-error-text {
        margin-top: 7px;
        font-size: 12px;
        font-weight: 800;
        color: #DC2626;
    }

    .mh-radio-card {
        height: 48px;
        border-radius: 16px;
        border: 1px solid rgba(207, 216, 229, .88);
        background: rgba(255,255,255,.72);
        padding: 0 15px;
        display: flex;
        align-items: center;
        gap: 11px;
        font-size: 13px;
        font-weight: 850;
        color: #64748B;
        cursor: pointer;
        transition: all .22s ease;
        box-shadow: 0 10px 26px rgba(27, 48, 82, .055), inset 0 1px 0 rgba(255,255,255,.85);
    }

    .mh-radio-card-error {
        border-color: #EF4444 !important;
        background: #FFF7F7 !important;
        color: #DC2626 !important;
        box-shadow: 0 0 0 5px rgba(239,68,68,.10) !important;
    }

    .mh-radio-card input {
        width: 17px;
        height: 17px;
        accent-color: #28AEDA;
    }

    .mh-note {
        margin-top: 20px;
        border-radius: 18px;
        border: 1px solid rgba(40,174,218,.16);
        background: rgba(40,174,218,.075);
        padding: 14px 16px;
    }

    .mh-note p {
        font-size: 12px;
        line-height: 1.8;
        font-weight: 700;
        color: #5F6D83;
    }

    .mh-submit-btn {
        margin-top: 22px;
        width: 100%;
        border-radius: 17px;
        background: linear-gradient(135deg, #28AEDA 0%, #159AC7 100%);
        padding: 15px 24px;
        font-size: 14px;
        font-weight: 900;
        color: white;
        box-shadow: 0 18px 42px rgba(40,174,218,.34), inset 0 1px 0 rgba(255,255,255,.24);
        transition: all .22s ease;
    }

    .mh-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 52px rgba(40,174,218,.42), inset 0 1px 0 rgba(255,255,255,.28);
        filter: brightness(1.02);
    }

    .mh-divider {
        margin: 24px 0;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .mh-divider div {
        height: 1px;
        flex: 1;
        background: linear-gradient(90deg, transparent, rgba(148,163,184,.45), transparent);
    }

    .mh-divider span {
        font-size: 12px;
        font-weight: 800;
        color: #94A3B8;
    }

    .mh-login-text {
        text-align: center;
        font-size: 12px;
        font-weight: 750;
        color: #64748B;
    }

    .mh-login-text a {
        color: #18A9D7;
        font-weight: 950;
    }

    .mh-login-text a:hover {
        text-decoration: underline;
    }

    .mh-reveal {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity .75s ease, transform .75s ease;
    }

    .mh-reveal.mh-show {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 1024px) {
        .mh-register-layout { display: block; }
        .mh-brand-panel { display: none; }
        .mh-form-panel { padding: 28px; }
        .mh-form-card { max-width: 760px; padding: 26px; }
    }

    @media (max-width: 640px) {
        .mh-form-panel { padding: 16px; }
        .mh-form-card { border-radius: 24px; padding: 20px; }
        .mh-page-title { font-size: 21px; }
    }
</style>

<div class="mh-register-page">

    <div class="mh-bg-orb mh-bg-orb-1"></div>
    <div class="mh-bg-orb mh-bg-orb-2"></div>
    <div class="mh-bg-orb mh-bg-orb-3"></div>
    <div class="mh-dot-grid left-10 top-24"></div>
    <div class="mh-dot-grid bottom-16 right-16"></div>

    <div class="mh-register-layout mh-reveal">

        <section class="mh-brand-panel">
            <div class="relative z-10">
                <h1 class="mh-brand-title">
                    Mulai langkah tenang bersama MindHaven.
                </h1>

                <p class="mh-brand-desc">
                    Buat akun pasien untuk mengakses konsultasi psikolog, melihat hasil konsultasi,
                    dan mendapatkan rujukan psikiater jika dibutuhkan.
                </p>

                <div class="mh-benefit-list">
                    <div class="mh-benefit-item">
                        <span class="mh-benefit-icon">✓</span>
                        Data pasien tersimpan rapi dan aman
                    </div>
                    <div class="mh-benefit-item">
                        <span class="mh-benefit-icon">✓</span>
                        Konsultasi dengan psikolog terverifikasi
                    </div>
                    <div class="mh-benefit-item">
                        <span class="mh-benefit-icon">✓</span>
                        Hasil konsultasi dan rujukan mudah diakses
                    </div>
                </div>
            </div>
        </section>

        <section class="mh-form-panel">
            <div class="mh-form-card">
                <div class="mh-form-content">

                    <div class="mb-6 text-center">
                        <a href="{{ route('home') }}" class="mh-logo-card">
                            <img src="{{ asset('assets/images/logo_polos.png') }}"
                                 alt="MindHaven Logo"
                                 class="h-14 w-14 object-contain">
                        </a>

                        <h2 class="mh-page-title">
                            Daftar Akun MindHaven
                        </h2>

                        <p class="mh-page-subtitle">
                            Lengkapi data pasien untuk membuat akun baru.
                        </p>
                    </div>

                    <form action="{{ route('register.process') }}" method="POST" novalidate>
                        @csrf

                        <div class="mh-section-title">
                            <span class="mh-section-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M5 20C5.8 16.6 8.4 15 12 15C15.6 15 18.2 16.6 19 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            Data Akun
                        </div>

                        <div class="grid grid-cols-1 gap-x-5 gap-y-4 md:grid-cols-2">
                            <div>
                                <label class="mh-label">Nama</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="mh-input {{ $errors->has('name') ? 'mh-input-error' : '' }}"
                                       placeholder="Masukkan nama lengkap">

                                @error('name')
                                    <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mh-label">E-mail</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="mh-input {{ $errors->has('email') ? 'mh-input-error' : '' }}"
                                       placeholder="Masukkan e-mail aktif">

                                @error('email')
                                    <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mh-label">Password</label>
                                <input type="password"
                                       name="password"
                                       class="mh-input {{ $errors->has('password') ? 'mh-input-error' : '' }}"
                                       placeholder="Minimal 8 karakter">

                                @error('password')
                                    <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mh-label">Konfirmasi Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="mh-input {{ $errors->has('password') ? 'mh-input-error' : '' }}"
                                       placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="mh-section-title">
                            <span class="mh-section-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 4H17C18.1046 4 19 4.89543 19 6V18C19 19.1046 18.1046 20 17 20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M9 9H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M9 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            Data Pribadi Pasien
                        </div>

                        <div class="grid grid-cols-1 gap-x-5 gap-y-4 md:grid-cols-2">
                            <div>
                                <label class="mh-label">No Telepon</label>
                                <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                                inputmode="numeric" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                class="mh-input {{ $errors->has('no_telepon') ? 'mh-input-error' : '' }}"
                                placeholder="08123456789">
                                
                                @error('no_telepon')
                                <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mh-label">Tanggal Lahir</label>
                                <input type="date"
                                       name="tanggal_lahir"
                                       value="{{ old('tanggal_lahir') }}"
                                       class="mh-input {{ $errors->has('tanggal_lahir') ? 'mh-input-error' : '' }}">

                                @error('tanggal_lahir')
                                    <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="mh-label">Jenis Kelamin</label>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <label class="mh-radio-card {{ $errors->has('jenis_kelamin') ? 'mh-radio-card-error' : '' }}">
                                        <input type="radio"
                                               name="jenis_kelamin"
                                               value="laki-laki"
                                               {{ old('jenis_kelamin') === 'laki-laki' ? 'checked' : '' }}>
                                        Laki-laki
                                    </label>

                                    <label class="mh-radio-card {{ $errors->has('jenis_kelamin') ? 'mh-radio-card-error' : '' }}">
                                        <input type="radio"
                                               name="jenis_kelamin"
                                               value="perempuan"
                                               {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }}>
                                        Perempuan
                                    </label>
                                </div>

                                @error('jenis_kelamin')
                                    <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="mh-label">Alamat</label>
                                <textarea name="alamat"
                                          rows="3"
                                          class="mh-textarea {{ $errors->has('alamat') ? 'mh-textarea-error' : '' }}"
                                          placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>

                                @error('alamat')
                                    <p class="mh-error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mh-note">
                            <p>
                                Pastikan email dan nomor telepon aktif agar proses konsultasi, pembayaran,
                                hasil konsultasi, dan rujukan MindHaven dapat berjalan dengan baik.
                            </p>
                        </div>

                        <button type="submit" class="mh-submit-btn">
                            Daftar Sekarang
                        </button>

                        <div class="mh-divider">
                            <div></div>
                            <span>atau</span>
                            <div></div>
                        </div>

                        <p class="mh-login-text">
                            Sudah punya akun?
                            <a href="{{ route('login') }}">
                                Masuk di sini
                            </a>
                        </p>
                    </form>

                </div>
            </div>
        </section>

    </div>
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
    });
</script>

@endsection