@extends('frontend.layouts.psikolog')

@section('title', 'Data Psikiater')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Data Psikiater
        </h1>

        <p class="text-slate-500 mt-2">
            Referensi psikiater berdasarkan spesialisasi dan rumah sakit.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @for ($i = 1; $i <= 9; $i++)

        <div class="card-glass p-6">

            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">
                    DR
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        dr. Psikiater {{ $i }}
                    </h3>

                    <p class="text-sm text-slate-500">
                        Spesialis Kesehatan Jiwa
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-4">

                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-sm text-slate-500">
                        Spesialisasi
                    </p>

                    <h4 class="font-semibold text-slate-800 mt-1">
                        Depresi Berat & Anxiety
                    </h4>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-sm text-slate-500">
                        Rumah Sakit
                    </p>

                    <h4 class="font-semibold text-slate-800 mt-1">
                        RS MindHaven Sehat
                    </h4>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-sm text-slate-500">
                        No STR
                    </p>

                    <h4 class="font-semibold text-slate-800 mt-1">
                        STR-PSI-00{{ $i }}
                    </h4>
                </div>

            </div>

            <div class="mt-6">
                <a href="{{ route('psikolog.rujukan.create') }}"
                   class="btn-primary w-full">
                    Pilih Untuk Rujukan
                </a>
            </div>

        </div>

        @endfor

    </div>

</div>

@endsection