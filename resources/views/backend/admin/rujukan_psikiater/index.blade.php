@extends('backend.layouts.app')

@section('title', 'Data Rujukan Psikiater')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="admin-title">Data Rujukan Psikiater</h1>
        <p class="admin-subtitle">Monitoring surat rujukan dari psikolog ke psikiater.</p>
    </div>

    <div class="admin-card p-6">

        <div class="overflow-x-auto">
            <table class="w-full">

                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="admin-table-head">Pasien</th>
                        <th class="admin-table-head">Psikolog</th>
                        <th class="admin-table-head">Psikiater</th>
                        <th class="admin-table-head">Rumah Sakit</th>
                        <th class="admin-table-head">Tanggal</th>
                        <th class="admin-table-head text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @for ($i = 1; $i <= 10; $i++)

                    <tr class="hover:bg-slate-50 transition">
                        <td class="admin-table-body font-semibold">
                            Pasien {{ $i }}
                        </td>

                        <td class="admin-table-body">
                            Psikolog {{ $i }}
                        </td>

                        <td class="admin-table-body">
                            dr. Psikiater {{ $i }}
                        </td>

                        <td class="admin-table-body">
                            RS MindHaven {{ $i }}
                        </td>

                        <td class="admin-table-body">
                            12 Mei 2026
                        </td>

                        <td class="admin-table-body text-right">
                            <a href="{{ route('admin.rujukan.show', $i) }}" class="admin-btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>

                    @endfor

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection