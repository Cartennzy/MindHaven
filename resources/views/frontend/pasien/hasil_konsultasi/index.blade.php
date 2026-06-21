@extends('frontend.layouts.app')

@section('title', 'Hasil Konsultasi - MindHaven')
@section('page_title', 'Hasil Konsultasi')
@section('page_subtitle', 'Daftar hasil konsultasi yang telah dikirim oleh psikolog.')

@section('content')

<div class="space-y-7 max-w-6xl mx-auto">

    {{-- HEADER BANNER - FULL GRADIENT PREMIUM SAAS AESTHETIC --}}
    <div class="relative overflow-hidden rounded-[34px] bg-gradient-to-r from-[#01588E] via-[#0372A6] to-[#49C5B6] p-6 shadow-[0_20px_50px_rgba(1,88,142,0.2)] md:p-8">
        <!-- Efek Glow Seni Digital Elegan -->
        <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 h-44 w-44 rounded-full bg-black/10 blur-2xl"></div>

        <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <!-- Wrapper Ikon dengan Efek Glassmorphic -->
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[24px] bg-white/15 border border-white/20 text-white shadow-inner">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7V3z"/>
                    </svg>
                </div>

                <div>
                    <span class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/10 px-3.5 py-1 text-[11px] font-black uppercase tracking-[0.14em] text-white backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#A7E36F] animate-pulse"></span>
                        Rekap Pemeriksaan
                    </span>
                    <h2 class="text-3xl font-black tracking-tight text-white">Hasil Konsultasi</h2>
                    <p class="mt-1 text-sm font-semibold text-white/85"> Berikut daftar hasil konsultasi yang sudah dikirim oleh psikolog. Klik detail untuk melihat hasil lengkap.</p>
                </div>
            </div>

            <!-- Counter Data Bergaya Glassmorphism Minimalis -->
            <div class="rounded-[26px] border border-white/20 bg-white/10 px-6 py-4 text-center backdrop-blur-md shadow-inner min-w-[120px]">
                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-white/80">Total Hasil</p>
                <p class="mt-0.5 text-4xl font-black text-white">{{ $konsultasis->count() }}</p>
            </div>
        </div>
    </div>

    {{-- LIST HASIL --}}
    <div class="space-y-6">
        @forelse($konsultasis as $konsultasi)
            @php
                $detail = $konsultasi->detailKonsultasi;

                $namaPsikolog = $konsultasi->psikolog->nama_lengkap
                    ?? $konsultasi->psikolog->user->name
                    ?? '-';

                $tanggalHasil = $konsultasi->updated_at
                    ? $konsultasi->updated_at->format('d M Y H:i')
                    : '-';

                $statusText = match($konsultasi->status) {
                    'selesai' => 'Selesai',
                    'diproses' => 'Diproses',
                    'pending' => 'Menunggu',
                    'dibatalkan' => 'Dibatalkan',
                    default => ucfirst($konsultasi->status ?? '-'),
                };

                $statusClass = match($konsultasi->status) {
                    'selesai' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                    'diproses' => 'border-sky-200 bg-sky-50 text-sky-700',
                    'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
                    'dibatalkan' => 'border-rose-200 bg-rose-50 text-rose-700',
                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                };

                $metodeRaw = $konsultasi->metode_komunikasi ?? $konsultasi->metode_konsultasi ?? null;

                $metodeText = match($metodeRaw) {
                    'online' => 'Online',
                    'offline' => 'Offline',
                    'chat' => 'Chat',
                    'telepon' => 'Telepon',
                    'video' => 'Video Call',
                    'tatap_muka' => 'Tatap Muka',
                    default => $metodeRaw ? ucfirst(str_replace('_', ' ', $metodeRaw)) : '-',
                };

                $catatan = $detail->catatan
                    ?? $detail->keluhan_utama
                    ?? '-';

                $observasi = $detail->observasi
                    ?? $detail->hasil_observasi
                    ?? '-';

                $diagnosa = $detail->diagnosa
                    ?? $detail->diagnosis_awal
                    ?? '-';

                $saranTerapi = $detail->saran_terapi
                    ?? $detail->rencana_penanganan
                    ?? '-';

                $tindakLanjut = $detail->tindak_lanjut
                    ?? $detail->laporan_asesmen_psikologis
                    ?? '-';
            @endphp

            <div class="group relative overflow-hidden rounded-[30px] border border-slate-100 bg-gradient-to-br from-white via-[#F8FAFC] to-[#F1F5F9] p-6 shadow-[0_12px_40px_rgba(15,23,42,0.02)] transition duration-300 hover:shadow-[0_22px_50px_rgba(1,88,142,0.07)] hover:-translate-y-0.5 hover:border-slate-200">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b from-[#01588E] to-[#49C5B6]"></div>

                <div class="relative flex flex-col gap-6">
                    
                    {{-- Row Header Dalam Card --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between w-full">
                        <div class="flex items-center gap-3.5">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-[#01588E] text-xl border border-slate-100 shadow-sm transition-all duration-300 group-hover:scale-105">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 leading-tight">Hasil Sesi Konsultasi</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Lembar Rekam Diagnosis Pasien</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 self-start sm:self-center">
                            <div class="inline-flex h-9 items-center justify-center rounded-xl border px-4 text-xs font-black uppercase tracking-wider shadow-sm {{ $statusClass }}">
                                {{ $statusText }}
                            </div>
                            
                            @if($konsultasi->status === 'selesai')
                                @if($konsultasi->skor_rating)
                                    <div class="flex items-center h-9 gap-1 bg-amber-50 border border-amber-200 px-3.5 rounded-xl text-amber-600 font-black text-xs shadow-sm">
                                        <i class="fas fa-star text-amber-400"></i> {{ $konsultasi->skor_rating }} / 5
                                    </div>
                                @else
                                    <button type="button" 
                                            onclick="openRatingModal('{{ $konsultasi->id_konsultasi }}', '{{ $namaPsikolog }}')"
                                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 px-3.5 text-xs font-black text-white shadow-sm transition">
                                        <i class="fas fa-star"></i> Beri Rating
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Metadata Sesi Box --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 bg-white border border-slate-100/80 p-4 rounded-2xl shadow-inner">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Psikolog</span>
                            <span class="text-sm font-black text-slate-800 mt-0.5 block truncate">{{ $namaPsikolog }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Tanggal Rilis</span>
                            <span class="text-sm font-black text-slate-800 mt-0.5 block">{{ $tanggalHasil }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Topik Bahasan</span>
                            <span class="text-sm font-black text-slate-800 mt-0.5 block truncate">{{ $konsultasi->topik_konseling ?? $konsultasi->topik_konsultasi ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Metode Media</span>
                            <span class="text-sm font-black text-[#01588E] mt-0.5 block">{{ $metodeText }}</span>
                        </div>
                    </div>

                    {{-- Detail Deskripsi Komparasi Ganda --}}
                    @if($detail)
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <div class="rounded-2xl border border-white bg-white/60 p-4 shadow-sm">
                                <h4 class="flex items-center gap-2.5 text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 mb-2">
                                    <i class="fas fa-comment-dots text-[#01588E]"></i> Keluhan Utama
                                </h4>
                                <p class="text-xs font-semibold leading-relaxed text-slate-600 line-clamp-3">{{ $konsultasi->keluhan ?? '-' }}</p>
                            </div>

                            <div class="rounded-2xl border border-white bg-white/60 p-4 shadow-sm">
                                <h4 class="flex items-center gap-2.5 text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 mb-2">
                                    <i class="fas fa-notes-medical text-[#49C5B6]"></i> Catatan Konsultasi
                                </h4>
                                <p class="text-xs font-semibold leading-relaxed text-slate-600 line-clamp-3">{{ $catatan }}</p>
                            </div>

                            <div class="rounded-2xl border border-white bg-white/60 p-4 shadow-sm">
                                <h4 class="flex items-center gap-2.5 text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 mb-2">
                                    <i class="fas fa-magnifying-glass text-amber-500"></i> Hasil Observasi
                                </h4>
                                <p class="text-xs font-semibold leading-relaxed text-slate-600 line-clamp-3">{{ $observasi }}</p>
                            </div>

                            <div class="rounded-2xl border border-white bg-white/60 p-4 shadow-sm">
                                <h4 class="flex items-center gap-2.5 text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 mb-2">
                                    <i class="fas fa-stethoscope text-indigo-500"></i> Diagnosis Psikolog
                                </h4>
                                <!-- PERBAIKAN DI SINI: Membuang font-semibold mubah agar tidak konflik dengan font-bold -->
                                <p class="text-xs leading-relaxed text-slate-700 font-bold line-clamp-3">{{ $diagnosa }}</p>
                            </div>

                            <div class="rounded-2xl border border-white bg-white/60 p-4 shadow-sm">
                                <h4 class="flex items-center gap-2.5 text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 mb-2">
                                    <i class="fas fa-shield-heart text-emerald-500"></i> Saran Terapi Penanganan
                                </h4>
                                <p class="text-xs font-semibold leading-relaxed text-slate-600 line-clamp-3">{{ $saranTerapi }}</p>
                            </div>

                            <div class="rounded-2xl border border-white bg-white/60 p-4 shadow-sm">
                                <h4 class="flex items-center gap-2.5 text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 mb-2">
                                    <i class="fas fa-chart-line text-rose-500"></i> Alur Tindak Lanjut
                                </h4>
                                <p class="text-xs font-semibold leading-relaxed text-slate-600 line-clamp-3">{{ $tindakLanjut }}</p>
                            </div>
                        </div>

                        {{-- Action Button Baris Bawah Card --}}
                        <div class="border-t border-slate-200/50 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
                            <p class="text-xs font-semibold text-slate-400"><i class="fas fa-info-circle mr-1"></i> Data rekam medis di atas bersifat rahasia di bawah sumpah janji klinis platform.</p>
                            <a href="{{ route('pasien.hasil-konsultasi.show', $konsultasi->id_konsultasi) }}"
                               class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-5 text-xs font-black text-white shadow-sm hover:scale-[1.01] transition whitespace-nowrap">
                                <i class="fas fa-eye"></i> Tinjau Laporan Detil
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            {{-- ELEMEN @EMPTY DYNAMIC GRADIENT CARD --}}
            <div class="rounded-[34px] border border-[#BFE7F3] bg-gradient-to-br from-white via-[#F4FAFF] to-[#EFFDF9] p-10 text-center shadow-[0_20px_50px_rgba(1,88,142,0.05)] md:p-14">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[26px] bg-gradient-to-br from-[#01588E] to-[#49C5B6] text-white shadow-[0_12px_30px_rgba(1,88,142,0.25)] animate-pulse">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7V3z"/>
                    </svg>
                </div>

                <h3 class="mt-7 text-2xl font-black text-slate-900">Belum Ada Hasil Konsultasi</h3>
                <p class="mx-auto mt-2 max-w-xl text-sm font-semibold leading-7 text-slate-400">
                    Hasil konsultasi klinis akan otomatis muncul setelah Tim Psikolog MindHaven selesai menganalisis, mengonfirmasi, dan mengirimkan lembar rekam medis Anda.
                </p>

                <a href="{{ route('pasien.konsultasi.index') }}"
                   class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#01588E] to-[#0488B8] px-7 text-sm font-black text-white shadow-[0_12px_24px_rgba(1,88,142,0.15)] hover:-translate-y-0.5 transition duration-300">
                    <i class="fas fa-clipboard-list"></i> Lihat Consultasi Saya
                </a>
            </div>
        @endforelse
    </div>

</div>

{{-- Script Rating Modal Handler --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openRatingModal(idKonsultasi, namaPsikolog) {
        let currentRating = 0;

        Swal.fire({
            title: `Beri Ulasan untuk ${namaPsikolog}`,
            html: `
                <div class="flex justify-center gap-2 my-4">
                    <i class="far fa-star text-3xl text-amber-400 cursor-pointer star-btn" data-value="1"></i>
                    <i class="far fa-star text-3xl text-amber-400 cursor-pointer star-btn" data-value="2"></i>
                    <i class="far fa-star text-3xl text-amber-400 cursor-pointer star-btn" data-value="3"></i>
                    <i class="far fa-star text-3xl text-amber-400 cursor-pointer star-btn" data-value="4"></i>
                    <i class="far fa-star text-3xl text-amber-400 cursor-pointer star-btn" data-value="5"></i>
                </div>
                <textarea id="catatan_ulasan" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none font-medium resize-none focus:border-[#01588E]" rows="3" placeholder="Bagikan pengalaman konsultasi Anda..."></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kirim Ulasan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#01588E',
            background: '#FFFFFF',
            color: '#1E293B',
            didOpen: () => {
                const stars = Swal.getHtmlContainer().querySelectorAll('.star-btn');
                stars.forEach(star => {
                    star.addEventListener('click', (e) => {
                        currentRating = parseInt(e.target.getAttribute('data-value'));
                        stars.forEach((s, idx) => {
                            if (idx < currentRating) {
                                s.classList.remove('far');
                                s.classList.add('fas');
                            } else {
                                s.classList.remove('fas');
                                s.classList.add('far');
                            }
                        });
                    });
                });
            },
            preConfirm: () => {
                const ulasan = Swal.getHtmlContainer().querySelector('#catatan_ulasan').value;
                if (currentRating === 0) {
                    Swal.showValidationMessage('Silakan pilih rating bintang terlebih dahulu!');
                    return false;
                }
                return { skor_rating: currentRating, catatan_ulasan: ulasan };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/pasien/hasil-konsultasi/${idKonsultasi}/rate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terima Kasih!',
                            text: 'Ulasan Anda berhasil disimpan.',
                            confirmButtonColor: '#01588E'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan rating.', 'error');
                    }
                });
            }
        });
    }
</script>

@endsection