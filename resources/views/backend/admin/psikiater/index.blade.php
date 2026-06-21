@extends('backend.layouts.app')

@section('title', 'Data Psikiater')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $totalPsikiater = $psikiaters->count();
    $totalAktif = $psikiaters->where('status', true)->count();
@endphp

<div class="space-y-5">

    <section class="relative overflow-hidden rounded-[1.8rem] bg-gradient-to-br from-cyan-50 via-white to-blue-50 px-6 py-6 shadow-[0_10px_35px_rgba(15,23,42,.05)]">

        <div class="absolute -right-24 -top-24 h-60 w-60 rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 h-60 w-60 rounded-full bg-cyan-100/50 blur-3xl"></div>

        <div class="relative flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <div class="inline-flex items-center gap-3 rounded-full border border-blue-100 bg-white/90 px-4 py-2 text-xs font-medium text-[#01588E] shadow-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Data Psikiater MindHaven
                </div>

                <h1 class="mt-5 text-3xl font-medium tracking-tight text-slate-900 md:text-5xl">
                    Kelola Data
                    <span class="text-[#0284C7]">Psikiater</span>
                </h1>

                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
                    Pantau data psikiater, spesialisasi, relasi rumah sakit, dan status keaktifan dalam satu tampilan modern.
                </p>

                <div class="mt-6 grid max-w-xl grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="rounded-[1.5rem] border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M12 9v6M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400">
                                    Total Psikiater
                                </p>

                                <h3 class="mt-1 text-2xl font-medium text-slate-900">
                                    {{ $totalPsikiater }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m6 2.25a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400">
                                    Akun Aktif
                                </p>

                                <h3 class="mt-1 text-2xl font-medium text-slate-900">
                                    {{ $totalAktif }}
                                </h3>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <a href="{{ route('admin.psikiater.create') }}"
               class="inline-flex items-center justify-center gap-3 rounded-[1.4rem] bg-[#01588E] px-6 py-4 text-sm font-medium text-white shadow-lg shadow-[#01588E]/20 transition duration-300 hover:bg-[#01446e]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Psikiater
            </a>

        </div>

    </section>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <section class="rounded-[1.8rem] border border-slate-100 bg-white p-6 shadow-[0_10px_35px_rgba(15,23,42,.05)]">

        <div class="mb-5">
            <h2 class="text-2xl font-medium text-slate-900">
                Daftar Psikiater
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Semua data psikiater yang tersedia di sistem.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">

            @forelse ($psikiaters as $psikiater)

                @php
                    $fotoPsikiater = $psikiater->foto_profil && Storage::disk('public')->exists($psikiater->foto_profil)
                        ? asset('storage/' . $psikiater->foto_profil)
                        : null;
                @endphp

                <div class="rounded-[1.6rem] border border-slate-100 bg-gradient-to-br from-white to-slate-50 p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_14px_35px_rgba(15,23,42,.06)]">

                    <div class="flex items-start justify-between gap-4">

                        <div class="flex gap-4">
                            @if($fotoPsikiater)
                                <img src="{{ $fotoPsikiater }}"
                                     alt="{{ $psikiater->nama_lengkap }}"
                                     class="h-16 w-16 rounded-[1.2rem] object-cover ring-2 ring-white shadow-md">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-[1.2rem] bg-[#01588E]/10 text-lg font-medium text-[#01588E]">
                                    {{ strtoupper(substr($psikiater->nama_lengkap, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <h3 class="text-lg font-medium text-slate-900">
                                    {{ $psikiater->nama_lengkap }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $psikiater->spesialisasi ?? '-' }}
                                </p>

                                @if($psikiater->status)
                                    <span class="mt-3 inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-medium text-emerald-700">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.psikiater.edit', $psikiater->id_psikiater) }}"
                               class="rounded-xl bg-[#01588E] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-[#01446e]">
                                Edit
                            </a>

                            <form action="{{ route('admin.psikiater.destroy', $psikiater->id_psikiater) }}"
                                  method="POST"
                                  class="delete-psikiater-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="rounded-xl bg-red-100 px-4 py-2.5 text-xs font-medium text-red-700 transition hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-3">

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs text-slate-400">
                                Rumah Sakit
                            </p>

                            <p class="mt-2 text-sm font-medium text-slate-900">
                                {{ optional($psikiater->rumahSakit)->nama_rumahsakit ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs text-slate-400">
                                No Telepon
                            </p>

                            <p class="mt-2 text-sm font-medium text-slate-900">
                                {{ $psikiater->no_telepon ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <p class="text-xs text-slate-400">
                                No STR
                            </p>

                            <p class="mt-2 line-clamp-1 text-sm font-medium text-slate-900">
                                {{ $psikiater->str_psikiater ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

            @empty

                <div class="rounded-[1.7rem] border border-dashed border-slate-200 bg-slate-50 p-10 text-center xl:col-span-2">
                    <h3 class="text-lg font-medium text-slate-800">
                        Belum ada data psikiater
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Data psikiater akan muncul setelah admin menambahkan data baru.
                    </p>
                </div>

            @endforelse

        </div>

    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-psikiater-form');

        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Hapus Psikiater?',
                    text: 'Data psikiater yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#64748B',
                    reverseButtons: true,
                    background: '#FFFFFF',
                    color: '#0F172A'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#0F172A'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal',
                text: @json(session('error')),
                icon: 'error',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#0F172A'
            });
        @endif
    });
</script>

@endsection