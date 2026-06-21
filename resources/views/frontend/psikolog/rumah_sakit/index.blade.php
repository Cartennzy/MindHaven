@extends('frontend.layouts.psikolog')

@section('title', 'Data Rumah Sakit')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Data Rumah Sakit
        </h1>

        <p class="text-slate-500 mt-2">
            Daftar rumah sakit yang terhubung dengan psikiater rujukan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @for ($i = 1; $i <= 9; $i++)

        <div class="card-glass p-6">

            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center mb-5">
                <svg class="w-7 h-7 text-primary"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800">
                RS MindHaven {{ $i }}
            </h3>

            <p class="text-slate-500 mt-2 leading-relaxed">
                Rumah sakit rujukan kesehatan jiwa dan layanan psikiatri.
            </p>

            <div class="mt-6 space-y-4">

                <div>
                    <p class="text-sm text-slate-500">
                        Alamat
                    </p>

                    <h4 class="font-semibold text-slate-800">
                        Jl. Kesehatan Mental No. {{ $i }}
                    </h4>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Telepon
                    </p>

                    <h4 class="font-semibold text-slate-800">
                        021-555-00{{ $i }}
                    </h4>
                </div>

                <div>
                    <p class="text-sm text-slate-500">
                        Status
                    </p>

                    <span class="badge-success">
                        Aktif
                    </span>
                </div>

            </div>

        </div>

        @endfor

    </div>

</div>

@endsection