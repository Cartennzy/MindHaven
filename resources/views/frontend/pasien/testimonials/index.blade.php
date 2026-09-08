@extends('frontend.layouts.app')

@section('title', 'Tulis Testimoni - MindHaven')
@section('page_title', 'Tulis Testimoni')
@section('page_subtitle', 'Bagikan pengalaman konsultasi Anda bersama layanan MindHaven.')

@section('content')
<div class="space-y-6">
    
    {{-- FORM SECTION --}}
    <section class="max-w-3xl mx-auto rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_24px_70px_rgba(15,23,42,0.04)] md:p-8">
        
        {{-- HEADER FORM --}}
        <div class="mb-8 border-b border-slate-100 pb-5">
            <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3.5 py-1.5 text-xs font-semibold text-amber-600 mb-3">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Share Your Experience
            </div>
            <h2 class="text-2xl font-black text-[#061A33]">Tulis Cerita & Ulasan Anda</h2>
            <p class="mt-1 text-sm text-slate-500">Ulasan yang Anda kirimkan akan ditampilkan secara realtime pada halaman utama landing page MindHaven.</p>
        </div>

        {{-- FORM INPUT --}}
        <form action="{{ url('/pasien/testimonials') }}" method="POST" class="space-y-6">
            @csrf

            {{-- ERROR VALIDATION FLASH --}}
            @if ($errors->any())
                <div class="rounded-2xl bg-red-50 p-4 border border-red-100 text-sm font-semibold text-red-600">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SELEKSI BINTANG RATING --}}
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-800 block">Tingkat Kepuasan Layanan</label>
                <div class="relative max-w-md">
                    <select name="bintang" required 
                        class="w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-bold text-slate-800 focus:border-[#01588E] focus:bg-white focus:outline-none transition duration-200">
                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                        <option value="4">⭐⭐⭐⭐ (Puas)</option>
                        <option value="3">⭐⭐⭐ (Cukup Puas)</option>
                        <option value="2">⭐⭐ (Kurang Puas)</option>
                        <option value="1">⭐ (Tidak Puas)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- TEXTAREA ISI ULASAN --}}
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-800 block">Ulasan / Pengalaman Anda</label>
                <textarea name="ulasan" rows="6" required maxlength="1000"
                    placeholder="Ceritakan bagaimana platform digital MindHaven membantu ketenangan mental, proses terapi, atau kemudahan konsultasi dengan psikolog pilihan Anda..." 
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-[#01588E] focus:bg-white focus:outline-none transition duration-200"></textarea>
                <div class="text-right text-xs text-slate-400 font-medium">Maksimal 1000 Karakter</div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex flex-col-reverse gap-3 pt-4 border-t border-slate-100 sm:flex-row sm:justify-end">
                <a href="{{ url('/pasien/dashboard') }}" 
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 px-6 py-3.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#01588E] px-6 py-3.5 text-sm font-bold text-white shadow-[0_12px_30px_rgba(1,88,142,0.20)] hover:bg-[#0574A7] transition duration-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kirim
                </button>
            </div>
        </form>
    </section>

</div>
@endsection