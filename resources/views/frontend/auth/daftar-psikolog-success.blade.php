@extends('frontend.layouts.guest')

@section('title', 'Pengajuan Berhasil - MindHaven')

@section('content')

<div class="min-h-screen bg-[#F5FAFC] px-5 py-10">

    <div class="mx-auto flex min-h-[80vh] max-w-5xl items-center justify-center">

        <div class="w-full overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-[0_30px_100px_rgba(15,23,42,0.10)]">

            <div class="grid grid-cols-1 lg:grid-cols-2">

                <div class="relative hidden bg-gradient-to-br from-[#01588E] via-[#0B5F8E] to-[#41AD01] p-10 text-white lg:block">

                    <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-[#41AD01]/30 blur-3xl"></div>

                    <div class="relative flex h-full flex-col justify-between">

                        <div>
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('assets/images/logo_polos.png') }}"
                                     class="h-16 w-16 rounded-2xl bg-white p-2"
                                     alt="MindHaven">

                                <div>
                                    <h2 class="text-3xl font-black">MindHaven</h2>
                                    <p class="text-sm text-white/70">Program Mitra Psikolog</p>
                                </div>
                            </div>

                            <h1 class="mt-12 text-4xl font-black leading-tight">
                                Pengajuan Anda Berhasil Masuk ke Sistem
                            </h1>

                            <p class="mt-5 leading-8 text-white/80">
                                Admin MindHaven akan memeriksa data dan dokumen yang Anda kirim sebelum akun psikolog dapat digunakan.
                            </p>
                        </div>

                        <div class="mt-10 rounded-3xl bg-white/10 p-6 backdrop-blur">
                            <p class="text-sm leading-7 text-white/80">
                                Jangan login backend terlebih dahulu sebelum akun Anda disetujui oleh admin.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="p-8 text-center lg:p-12">

                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#41AD01]/10 text-[#41AD01]">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <h1 class="mt-8 text-4xl font-black text-slate-900">
                        Pengajuan Berhasil Dikirim
                    </h1>

                    <p class="mx-auto mt-4 max-w-xl text-base leading-8 text-slate-500">
                        Terima kasih sudah mengajukan kerja sama sebagai psikolog MindHaven.
                        Status akun Anda saat ini adalah <span class="font-black text-yellow-600">menunggu verifikasi admin</span>.
                    </p>

                    <div class="mt-8 rounded-3xl bg-[#01588E]/5 p-6 text-left">

                        <h2 class="text-lg font-black text-[#01588E]">
                            Tahapan Selanjutnya
                        </h2>

                        <div class="mt-6 space-y-5">

                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#41AD01] text-sm font-black text-white">
                                    1
                                </div>

                                <div>
                                    <h3 class="font-black text-slate-800">
                                        Pengajuan diterima sistem
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        Data psikolog berhasil masuk ke daftar pengajuan admin.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-500 text-sm font-black text-white">
                                    2
                                </div>

                                <div>
                                    <h3 class="font-black text-slate-800">
                                        Menunggu verifikasi admin
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        Admin akan memeriksa data pribadi, spesialisasi, pengalaman, dan dokumen pendukung.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-300 text-sm font-black text-white">
                                    3
                                </div>

                                <div>
                                    <h3 class="font-black text-slate-800">
                                        Akun aktif setelah disetujui
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        Jika disetujui, akun psikolog dapat digunakan untuk login ke panel psikolog.
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">

                        <a href="{{ route('home') }}"
                           class="rounded-2xl bg-[#01588E] px-7 py-4 text-sm font-black text-white transition hover:bg-[#01446e]">
                            Kembali ke Beranda
                        </a>

                        <a href="{{ route('psikolog.register') }}"
                           class="rounded-2xl bg-slate-100 px-7 py-4 text-sm font-black text-slate-600 transition hover:bg-slate-200">
                            Kirim Pengajuan Lagi
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection