@extends('frontend.layouts.app')

@section('title', $meditasi->judul . ' - MindHaven')
@section('page_title', 'Detail Meditasi')
@section('page_subtitle', 'Dengarkan dan ikuti panduan meditasi.')

@section('content')
<div class="space-y-6">

    <section class="rounded-[2rem] border border-slate-100 bg-white p-8 shadow-soft">

        <a href="{{ route('pasien.meditasi.index') }}"
           class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2 text-sm font-black text-slate-600 hover:bg-[#01588E] hover:text-white">
            ← Kembali
        </a>

        <div class="mt-6">
            <span class="inline-flex rounded-full bg-[#28AEDA]/10 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-[#01588E]">
                {{ $meditasi->kategori ?? 'Relaksasi' }}
            </span>
        </div>

        <h2 class="mt-5 text-3xl font-black text-slate-900">
            {{ $meditasi->judul }}
        </h2>

        <p class="mt-3 text-sm font-bold text-[#41AD01]">
            Durasi: {{ $meditasi->durasi ?? '-' }} Menit
        </p>

        <p class="mt-5 text-sm leading-8 text-slate-600">
            {{ $meditasi->deskripsi }}
        </p>

        @if($meditasi->audio)
            @php
                $extension = strtolower(pathinfo($meditasi->audio, PATHINFO_EXTENSION));
                $fileUrl = asset('storage/' . $meditasi->audio);
            @endphp

            <div class="mt-8 rounded-[2rem] bg-slate-50 p-6">
                <h3 class="mb-4 text-lg font-black text-slate-900">
                    File Meditasi
                </h3>

                @if(in_array($extension, ['mp4', 'mov', 'webm']))
                    <video controls class="w-full rounded-2xl">
                        <source src="{{ $fileUrl }}">
                        Browser Anda tidak mendukung video.
                    </video>
                @else
                    <audio controls class="w-full">
                        <source src="{{ $fileUrl }}">
                        Browser Anda tidak mendukung audio.
                    </audio>
                @endif
            </div>
        @else
            <div class="mt-8 rounded-[2rem] bg-yellow-50 p-6 text-sm font-bold text-yellow-700">
                Audio atau video meditasi belum tersedia.
            </div>
        @endif

    </section>

</div>
@endsection