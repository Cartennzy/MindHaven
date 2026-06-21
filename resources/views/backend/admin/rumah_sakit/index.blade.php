@extends('backend.layouts.app')

@section('title', 'Data Rumah Sakit')

@section('content')

@php
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\Storage;

    $hasStatus = Schema::hasColumn('rumah_sakits', 'status');
    $hasFoto = Schema::hasColumn('rumah_sakits', 'foto');

    $totalRumahSakit = $rumahSakits->count();
    $totalAktif = $hasStatus
        ? $rumahSakits->where('status', 'aktif')->count()
        : $rumahSakits->count();
@endphp

<div class="space-y-5">

    {{-- HERO COMPACT --}}
    <section class="rounded-[1.7rem] bg-gradient-to-br from-white via-[#F7FCFF] to-[#F1FAF4] p-5 shadow-sm">

        <div class="mb-4 inline-flex items-center gap-2 rounded-xl border border-slate-100 bg-white px-4 py-2 shadow-sm">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-medium text-[#01588E]">
                Data Rumah Sakit MindHaven
            </span>
        </div>

        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <h1 class="text-2xl font-medium tracking-tight text-[#061A33] lg:text-3xl">
                    Kelola Data <span class="text-[#0189CA]">Rumah Sakit</span>
                </h1>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                    Pantau rumah sakit rujukan, kontak, status, dan fasilitas rumah sakit.
                </p>

                <div class="mt-4 grid max-w-xl grid-cols-1 gap-3 md:grid-cols-2">

                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-[#01588E]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.22em] text-slate-400">
                                    Total Rumah Sakit
                                </p>
                                <h3 class="mt-1 text-xl font-medium text-[#061A33]">
                                    {{ $totalRumahSakit }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.22em] text-slate-400">
                                    Rumah Sakit Aktif
                                </p>
                                <h3 class="mt-1 text-xl font-medium text-[#061A33]">
                                    {{ $totalAktif }}
                                </h3>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <a href="{{ route('admin.rumah-sakit.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#01588E] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-[#01466f]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Rumah Sakit
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

    {{-- LIST COMPACT --}}
    <section class="rounded-[1.7rem] bg-white p-5 shadow-sm">

        <div class="mb-4">
            <h2 class="text-xl font-medium text-[#061A33]">
                Daftar Rumah Sakit
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Rumah sakit rujukan yang tersedia di sistem.
            </p>
        </div>

        <div class="space-y-3">

            @forelse ($rumahSakits as $rumahSakit)

                @php
                    $fotoRumahSakit = null;

                    if (
                        $hasFoto &&
                        !empty($rumahSakit->foto) &&
                        Storage::disk('public')->exists($rumahSakit->foto)
                    ) {
                        $fotoRumahSakit = asset('storage/' . $rumahSakit->foto);
                    }

                    $statusRumahSakit = $hasStatus
                        ? ($rumahSakit->status ?? 'aktif')
                        : 'aktif';
                @endphp

                <div class="rounded-[1.4rem] border border-slate-100 bg-white p-4 shadow-sm transition hover:bg-slate-50">

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-[76px_1.3fr_2fr_1fr_auto] lg:items-center">

                        <div>
                            @if($fotoRumahSakit)
                                <img src="{{ $fotoRumahSakit }}"
                                     alt="{{ $rumahSakit->nama_rumahsakit }}"
                                     class="h-16 w-16 rounded-xl object-cover">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-slate-100 text-xs text-slate-400">
                                    RS
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-base font-medium text-[#061A33]">
                                {{ $rumahSakit->nama_rumahsakit }}
                            </h3>

                            <div class="mt-2">
                                @if($statusRumahSakit == 'aktif')
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-slate-400">
                                Alamat
                            </p>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                {{ $rumahSakit->alamat }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-slate-400">
                                Telepon
                            </p>
                            <p class="mt-1 text-sm text-[#061A33]">
                                {{ $rumahSakit->no_telepon }}
                            </p>
                        </div>

                        <div class="flex gap-2 lg:justify-end">
                            <a href="{{ route('admin.rumah-sakit.edit', $rumahSakit->id_rumahsakit) }}"
                               class="rounded-xl bg-[#01588E] px-4 py-2.5 text-xs font-medium text-white transition hover:bg-[#01466f]">
                                Edit
                            </a>

                            <form action="{{ route('admin.rumah-sakit.destroy', $rumahSakit->id_rumahsakit) }}"
                                  method="POST"
                                  class="delete-rumahsakit-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="rounded-xl bg-red-100 px-4 py-2.5 text-xs font-medium text-red-700 transition hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>

                </div>

            @empty

                <div class="rounded-[1.4rem] bg-slate-50 px-6 py-12 text-center">
                    <p class="text-lg font-medium text-[#061A33]">
                        Belum ada data rumah sakit
                    </p>
                    <p class="mt-2 text-sm text-slate-400">
                        Tambahkan rumah sakit baru untuk kebutuhan rujukan psikiater.
                    </p>
                </div>

            @endforelse

        </div>

    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-rumahsakit-form');

        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Hapus Rumah Sakit?',
                    text: 'Data rumah sakit yang dihapus tidak dapat dikembalikan.',
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