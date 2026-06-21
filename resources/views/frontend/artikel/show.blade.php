@extends('frontend.layouts.guest')

@section('title', $artikel->judul . ' - MindHaven')

@section('content')

@php
    use Illuminate\Support\Str;

    $penulis = $artikel->penulis
        ?? optional(optional($artikel->admin)->user)->name
        ?? optional($artikel->admin)->nama_lengkap
        ?? optional($artikel->admin)->nama
        ?? 'Admin MindHaven';

    $tanggalArtikel = $artikel->tanggal_publish
        ? $artikel->tanggal_publish->format('d M Y')
        : ($artikel->created_at ? $artikel->created_at->format('d M Y') : '-');

    $gambarArtikel = $artikel->gambar
        ? asset('storage/' . $artikel->gambar)
        : null;
@endphp

<div class="min-h-screen bg-[#F6FAFD] text-slate-900">

    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EEF9FF] shadow-sm">
                    <img src="{{ asset('assets/images/logo_polos.png') }}"
                         alt="MindHaven Logo"
                         class="h-9 w-9 object-contain">
                </div>

                <div>
                    <h1 class="text-xl font-bold tracking-tight text-[#061A33]">
                        MindHaven
                    </h1>
                    <p class="text-xs font-semibold text-slate-500">
                        Digital Mental Care
                    </p>
                </div>
            </a>

            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- PERBAIKAN: Tombol Kembali menjadi dinamis --}}
                <a href="{{ str_contains(url()->previous(), 'self-assessment') ? url()->previous() : route('artikel.index') }}"
                   class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>

                <a href="{{ route('artikel.index') }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-[#01588E] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#01446E]">
                    Semua Artikel
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white px-6 pb-10 pt-12">
        <div class="mx-auto max-w-4xl text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#EEF9FF] px-4 py-2 text-sm font-semibold text-[#01588E]">
                <span class="h-2 w-2 rounded-full bg-[#28AEDA]"></span>
                {{ $artikel->kategori ?? 'Artikel MindHaven' }}
            </div>

            <h2 class="mt-6 text-4xl font-bold leading-tight tracking-tight text-[#061A33] md:text-5xl">
                {{ $artikel->judul }}
            </h2>

            <p class="mt-5 text-sm font-semibold text-slate-500">
                Dipublikasikan oleh {{ $penulis }} · {{ $tanggalArtikel }}
            </p>
        </div>
    </section>

    <section class="px-6 pb-16">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-8 lg:grid-cols-[1fr_320px]">

            <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.08)]">

                <div class="bg-slate-100">
                    @if($gambarArtikel)
                        <img src="{{ $gambarArtikel }}"
                             alt="{{ $artikel->judul }}"
                             class="h-[420px] w-full object-cover">
                    @else
                        <div class="flex h-[360px] w-full items-center justify-center bg-gradient-to-br from-[#DFF5FF] to-[#EEF9FF] text-[#01588E]">
                            <svg class="h-20 w-20" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4V5Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 13h8M8 17h4"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-7 md:p-10">
                    <div class="prose prose-slate max-w-none
                                prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-[#061A33]
                                prose-h1:text-4xl prose-h2:text-3xl prose-h3:text-2xl
                                prose-p:text-[15px] prose-p:leading-8 prose-p:text-slate-600
                                prose-strong:text-[#061A33]
                                prose-a:text-[#01588E] prose-a:no-underline hover:prose-a:underline
                                prose-ul:text-slate-600 prose-ol:text-slate-600">
                        {!! $artikel->konten !!}
                    </div>
                </div>

            </article>

            <aside class="space-y-6 lg:sticky lg:top-8 lg:self-start">

                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.06)]">
                    <h3 class="text-xl font-bold text-[#061A33]">
                        Tentang Artikel
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Artikel ini merupakan konten edukasi MindHaven sebagai informasi pendukung kesehatan mental.
                    </p>

                    <div class="mt-5 rounded-2xl bg-[#F8FAFC] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Kategori
                        </p>
                        <p class="mt-2 text-sm font-bold text-[#061A33]">
                            {{ $artikel->kategori ?? '-' }}
                        </p>
                    </div>

                    <div class="mt-3 rounded-2xl bg-[#F8FAFC] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Penulis
                        </p>
                        <p class="mt-2 text-sm font-bold text-[#061A33]">
                            {{ $penulis }}
                        </p>
                    </div>

                    <div class="mt-3 rounded-2xl bg-[#F8FAFC] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Sumber Artikel
                        </p>
                        <p class="mt-2 text-sm font-bold text-[#061A33]">
                            {{ $artikel->sumber_artikel ?? '-' }}
                        </p>
                    </div>

                    <div class="mt-3 rounded-2xl bg-[#F8FAFC] p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                            Tanggal
                        </p>
                        <p class="mt-2 text-sm font-bold text-[#061A33]">
                            {{ $tanggalArtikel }}
                        </p>
                    </div>
                </div>

                @if(isset($relatedArtikels) && $relatedArtikels->count() > 0)
                    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.06)]">
                        <h3 class="text-xl font-bold text-[#061A33]">
                            Artikel Lainnya
                        </h3>

                        <div class="mt-5 space-y-4">
                            @foreach($relatedArtikels as $related)
                                <a href="{{ route('artikel.show', $related->id_artikel) }}"
                                   class="block rounded-2xl border border-slate-100 bg-[#F8FAFC] p-4 transition hover:-translate-y-1 hover:border-[#01588E]/30 hover:bg-[#EEF9FF]">
                                    <p class="mb-2 text-[11px] font-black uppercase tracking-[0.16em] text-[#41AD01]">
                                        {{ $related->kategori ?? 'Artikel' }}
                                    </p>

                                    <h4 class="line-clamp-2 text-sm font-bold leading-6 text-[#061A33]">
                                        {{ $related->judul }}
                                    </h4>

                                    <p class="mt-2 line-clamp-2 text-xs leading-6 text-slate-500">
                                        {{ Str::limit(strip_tags($related->konten), 90) }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </aside>
        </div>
    </section>
</div>
@endsection