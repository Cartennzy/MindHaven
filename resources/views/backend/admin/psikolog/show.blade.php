@extends('backend.layouts.app')

@section('title', 'Detail Psikolog')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;

    $userPsikolog = $psikolog->user;

    $psikologRouteKey = $psikolog->getKey()
        ?? $psikolog->id_psikolog
        ?? $psikolog->id
        ?? null;

    $foto = $psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)
        ? asset('storage/' . $psikolog->foto_profil)
        : null;

    $dokumenUrl = $psikolog->dokumen_verifikasi && Storage::disk('public')->exists($psikolog->dokumen_verifikasi)
        ? asset('storage/' . $psikolog->dokumen_verifikasi)
        : null;

    $dokumenPendidikanUrl = $psikolog->dokumen_pendidikan && Storage::disk('public')->exists($psikolog->dokumen_pendidikan)
        ? asset('storage/' . $psikolog->dokumen_pendidikan)
        : null;

    $dokumenStrUrl = $psikolog->dokumen_str_psikolog && Storage::disk('public')->exists($psikolog->dokumen_str_psikolog)
        ? asset('storage/' . $psikolog->dokumen_str_psikolog)
        : null;

    $dokumenSipUrl = $psikolog->dokumen_sip_psikolog && Storage::disk('public')->exists($psikolog->dokumen_sip_psikolog)
        ? asset('storage/' . $psikolog->dokumen_sip_psikolog)
        : null;

    $dokumenLengkap =
        !empty($psikolog->dokumen_verifikasi) &&
        !empty($psikolog->dokumen_pendidikan) &&
        !empty($psikolog->dokumen_str_psikolog) &&
        !empty($psikolog->dokumen_sip_psikolog);

    $statusVerifikasi = $psikolog->status_verifikasi ?? 'pending';

    $statusClass = match ($statusVerifikasi) {
        'verified' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'rejected' => 'bg-rose-50 text-rose-700 ring-rose-200',
        default => 'bg-slate-50 text-slate-600 ring-slate-200',
    };

    $statusLabel = match ($statusVerifikasi) {
        'verified' => 'Terverifikasi',
        'pending' => 'Menunggu Verifikasi',
        'rejected' => 'Ditolak',
        default => ucfirst($statusVerifikasi),
    };

    $documentCards = [
        [
            'title' => 'CV',
            'description' => 'Curriculum vitae atau dokumen pendukung utama.',
            'url' => $dokumenUrl,
            'button' => 'Lihat CV',
        ],
        [
            'title' => 'Pendidikan',
            'description' => 'Ijazah, transkrip, atau bukti pendidikan.',
            'url' => $dokumenPendidikanUrl,
            'button' => 'Lihat Pendidikan',
        ],
        [
            'title' => 'STR Psikolog',
            'description' => 'Dokumen Surat Tanda Registrasi psikolog.',
            'url' => $dokumenStrUrl,
            'button' => 'Lihat STR',
        ],
        [
            'title' => 'SIP Psikolog',
            'description' => 'Dokumen Surat Izin Praktik psikolog.',
            'url' => $dokumenSipUrl,
            'button' => 'Lihat SIP',
        ],
    ];

    $jadwalPraktik = '-';

    if (
        $psikolog->relationLoaded('jadwalPraktiks')
        && $psikolog->jadwalPraktiks->count()
        )
        
        {
            $jadwalPraktik = $psikolog->jadwalPraktiks
            ->map(function ($jadwal) {

                return $jadwal->hari .
                    ' (' .
                    date('H:i', strtotime($jadwal->jam_mulai))
                    . ' - ' .
                    date('H:i', strtotime($jadwal->jam_selesai))
                    . ')';

                })
            ->implode(', ');
        }

    $tanggalLahirFormat = $psikolog->tanggal_lahir 
        ? Carbon::parse($psikolog->tanggal_lahir)->translatedFormat('d F Y') 
        : '-';

    $jenisKelaminFormat = match(strtolower($psikolog->jenis_kelamin)) {
        'perempuan' => 'Perempuan',
        'laki-laki' => 'Laki-Laki',
        default => $psikolog->jenis_kelamin ?? '-'
    };

    $items = [
        ['label' => 'Nama Akun', 'value' => $userPsikolog->name ?? '-'],
        ['label' => 'Email', 'value' => $userPsikolog->email ?? $psikolog->email ?? '-'],
        ['label' => 'No Telepon', 'value' => $psikolog->no_telepon ?? '-'],
        ['label' => 'Tanggal Lahir', 'value' => $tanggalLahirFormat],
        ['label' => 'Jenis Kelamin', 'value' => $jenisKelaminFormat],
        ['label' => 'Pengalaman', 'value' => ($psikolog->pengalaman ?? 0) . ' Tahun'],
        ['label' => 'Spesialisasi', 'value' => $psikolog->spesialisasi ?? '-'],
        ['label' => 'Biaya Konsultasi', 'value' => 'Rp' . number_format($psikolog->biaya_konsultasi ?? 0, 0, ',', '.')],
        ['label' => 'Metode Konsultasi', 'value' => $psikolog->metode_konsultasi ?? '-'],
        ['label' => 'Jadwal Praktik', 'value' => $jadwalPraktik],
        ['label' => 'STR Psikolog', 'value' => $psikolog->str_psikolog ?? '-'],
        ['label' => 'SIP Psikolog', 'value' => $psikolog->sip_psikolog ?? '-'],
    ];
@endphp

<div class="space-y-6 max-w-7xl mx-auto">

    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-gradient-to-br from-slate-50 via-white to-sky-50 px-6 py-7 md:px-8">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">

                <div class="flex flex-col gap-5 md:flex-row md:items-center">
                    <div class="relative">
                        @if($foto)
                            <img src="{{ $foto }}"
                                 class="h-28 w-28 rounded-[1.5rem] border border-white object-cover shadow-sm ring-1 ring-slate-200"
                                 alt="Foto Psikolog">
                        @else
                            <div class="flex h-28 w-28 items-center justify-center rounded-[1.5rem] border border-slate-200 bg-slate-100 text-4xl font-medium text-[#01588E]">
                                {{ strtoupper(substr($psikolog->nama_lengkap ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="inline-flex items-center rounded-full bg-[#01588E]/10 px-3 py-1.5 text-xs font-medium uppercase tracking-[0.14em] text-[#01588E]">
                            Detail Psikolog
                        </div>

                        <h1 class="mt-4 text-3xl font-semibold leading-tight text-slate-950 md:text-4xl">
                            {{ $psikolog->nama_lengkap ?? '-' }}
                        </h1>

                        <p class="mt-2 text-sm font-medium text-slate-500">
                            {{ $userPsikolog->email ?? $psikolog->email ?? '-' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-medium ring-1 {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                            @if($psikolog->is_active)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                                    Akun Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-4 py-2 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                    Akun Nonaktif
                                </span>
                            @endif

                            @if($dokumenLengkap)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                                    Dokumen Lengkap
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-rose-50 px-4 py-2 text-xs font-medium text-rose-700 ring-1 ring-rose-200">
                                    Dokumen Belum Lengkap
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.psikolog.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] h-12 px-6 text-sm font-bold text-white shadow-sm transition hover:bg-[#01446e]">
                    Kembali
                </a>

            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="space-y-6 xl:col-span-2">

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <div class="mb-6 border-b border-slate-50 pb-4">
                    <h2 class="text-xl font-bold text-slate-950 tracking-tight">Data Profil Psikolog</h2>
                    <p class="mt-1 text-xs font-semibold text-slate-400">Informasi utama kredensial akun psikolog MindHaven.</p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach($items as $item)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">
                                {{ $item['label'] }}
                            </p>
                            <p class="mt-1.5 break-words text-sm font-semibold text-slate-900">
                                {{ $item['value'] }}
                            </p>
                        </div>
                    @endforeach

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Riwayat Pendidikan</p>
                        <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-relaxed text-slate-800">
                            {{ $psikolog->pendidikan ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Bio Deskripsi</p>
                        <p class="mt-2 whitespace-pre-line text-sm font-semibold leading-relaxed text-slate-800">
                            {{ $psikolog->bio ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Alamat Rumah Tinggal</p>
                        <p class="mt-2 text-sm font-semibold leading-relaxed text-slate-800">
                            {{ $psikolog->alamat ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between border-b border-slate-50 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-950 tracking-tight">Dokumen Verifikasi Hukum</h2>
                        <p class="mt-1 text-xs font-semibold text-slate-400">
                            CV, ijazah pendidikan, lembar berkas STR, dan SIP asli psikolog.
                        </p>
                    </div>

                    <span class="w-fit rounded-xl px-3.5 h-7 inline-flex items-center text-[10px] font-bold uppercase tracking-wider {{ $dokumenLengkap ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' }}">
                        {{ $dokumenLengkap ? 'Berkas Lengkap' : 'Belum Lengkap' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach($documentCards as $document)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="text-base font-bold text-slate-900">
                                        {{ $document['title'] }}
                                    </h3>

                                    @if($document['url'])
                                        <span class="shrink-0 rounded-lg bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-100 uppercase tracking-wide">
                                            Ada
                                        </span>
                                    @else
                                        <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 ring-1 ring-slate-200 uppercase tracking-wide">
                                            Kosong
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-2 text-xs font-semibold leading-relaxed text-slate-400">
                                    {{ $document['description'] }}
                                </p>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100/60">
                                @if($document['url'])
                                    <a href="{{ $document['url'] }}"
                                       target="_blank"
                                       class="inline-flex h-9 items-center justify-center rounded-xl bg-[#01588E] px-4 text-xs font-bold text-white transition hover:bg-[#01446e]">
                                        {{ $document['button'] }}
                                    </a>
                                @else
                                    <p class="text-xs font-bold text-slate-400 italic">
                                        Berkas belum terunduh.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- SIDEBAR VERIFIKASI ADMIN --}}
        <div class="space-y-6">

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm md:p-7">
                <div class="mb-6 border-b border-slate-50 pb-4">
                    <h2 class="text-xl font-bold text-slate-950 tracking-tight">Eksaminasi Admin</h2>
                    <p class="mt-1 text-xs font-semibold text-slate-400">Kelola status hak operasional psikolog.</p>
                </div>

                <form action="{{ route('admin.psikolog.verifikasi', ['psikolog' => $psikologRouteKey]) }}"
                      method="POST"
                      class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Status Verifikasi Akun
                        </label>

                        <select name="status_verifikasi" class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-800 outline-none transition focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10">
                            <option value="pending" {{ $psikolog->status_verifikasi === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $psikolog->status_verifikasi === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ $psikolog->status_verifikasi === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Catatan Pengesahan / Berita Acara
                        </label>

                        <textarea name="catatan_verifikasi"
                                  rows="5"
                                  class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold leading-relaxed text-slate-800 outline-none transition focus:border-[#01588E] focus:bg-white focus:ring-4 focus:ring-[#01588E]/10"
                                  placeholder="Tulis lembar catatan keputusan verifikasi resmi faskes untuk peninjauan psikolog...">{{ old('catatan_verifikasi', $psikolog->catatan_verifikasi) }}</textarea>
                    </div>

                    <button type="submit"
                            class="inline-flex w-full h-12 items-center justify-center rounded-xl bg-[#01588E] text-sm font-bold text-white shadow-md shadow-blue-900/10 transition hover:bg-[#01446e]">
                        Simpan Verifikasi
                    </button>
                </form>
            </div>

        </div>

    </section>

</div>

@endsection