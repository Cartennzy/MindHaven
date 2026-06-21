@extends('frontend.layouts.app')

@section('title', 'Pembayaran Konsultasi - MindHaven')
@section('page_title', 'Pembayaran')
@section('page_subtitle', 'Selesaikan pembayaran untuk mengaktifkan sesi konsultasi.')

@section('content')

@php
    $idPembayaran = $pembayaran->id_pembayaran ?? null;

    $statusPembayaran = $pembayaran->status_pembayaran ?? 'pending';

    $biayaPsikolog = $pembayaran->biaya_psikolog ?? 0;
    $biayaAdmin = $pembayaran->biaya_admin ?? 0;
    $totalBayar = $pembayaran->total_pembayaran ?? ($biayaPsikolog + $biayaAdmin);

    $namaPsikolog = $konsultasi->psikolog->nama_lengkap ?? '-';
    $topikKonsultasi = $konsultasi->topik_konsultasi ?? '-';

    // Dipaksa menjadi transfer_bank sesuai instruksi
    $metodePembayaranRaw = 'transfer_bank';
    $metodePembayaranText = 'Transfer Bank';

    $uploadBuktiUrl = $idPembayaran
        ? route('pasien.pembayaran.upload-bukti', ['pembayaran' => $idPembayaran])
        : '#';

    $finishUrl = $idPembayaran
        ? route('pasien.pembayaran.finish', ['pembayaran' => $idPembayaran])
        : '#';
@endphp

<div class="grid grid-cols-1 gap-7 xl:grid-cols-12">

    <div class="xl:col-span-8">
        <div class="overflow-hidden rounded-[34px] border border-slate-100 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)]">

            <div class="relative overflow-hidden bg-gradient-to-br from-[#061A33] via-[#01588E] to-[#39A900] px-6 py-8 text-white md:px-8 md:py-10">
                <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-28 -left-24 h-72 w-72 rounded-full bg-[#41AD01]/30 blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.10]"
                     style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;">
                </div>

                <div class="relative">
                    <div class="mb-5 inline-flex items-center rounded-full bg-white/16 px-4 py-2 text-xs font-medium text-white backdrop-blur">
                        <i class="fas fa-shield-heart mr-2"></i>
                        Pembayaran MindHaven
                    </div>

                    <h2 class="text-3xl font-semibold leading-tight tracking-tight md:text-5xl">
                        Selesaikan Pembayaran
                    </h2>

                    <p class="mt-4 max-w-3xl text-base font-normal leading-8 text-blue-100 md:text-lg">
                        Selesaikan pembayaran Anda menggunakan opsi Transfer Bank online aman yang terintegrasi dengan sistem kami.
                    </p>
                </div>
            </div>

            <div class="p-6 md:p-8">

                @if (!$idPembayaran)
                    <div class="mb-6 rounded-[28px] border border-red-100 bg-red-50 px-6 py-5 text-sm font-medium text-red-700">
                        Data pembayaran tidak valid. ID pembayaran tidak ditemukan.
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-[26px] border border-[#01588E]/10 bg-[#01588E]/5 p-5">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-500">Order ID</p>
                        <h3 class="mt-2 break-all text-base font-semibold text-slate-900">
                            {{ $pembayaran->id_order ?? '-' }}
                        </h3>
                    </div>

                    <div class="rounded-[26px] border border-[#41AD01]/10 bg-[#41AD01]/5 p-5">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#41AD01] shadow-sm">
                            <i class="fas fa-user-doctor text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-500">Psikolog</p>
                        <h3 class="mt-2 text-base font-semibold leading-7 text-slate-900">
                            {{ $namaPsikolog }}
                        </h3>
                    </div>

                    <div class="rounded-[26px] border border-slate-100 bg-slate-50 p-5">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                            <i class="fas fa-circle-check text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-500">Status</p>
                        <h3 class="mt-2 text-base font-semibold text-slate-900">
                            {{ ucfirst($statusPembayaran) }}
                        </h3>
                    </div>
                </div>

                <div class="mt-7 rounded-[30px] border border-slate-100 bg-slate-50 p-6 md:p-7">
                    <div class="mb-5 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold text-slate-900">
                                Detail Tagihan
                            </h3>
                            <p class="mt-1 text-sm font-normal text-slate-500">
                                Pastikan data pembayaran sudah sesuai.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Topik Konsultasi</span>
                            <span class="max-w-[60%] text-right text-sm font-semibold leading-6 text-slate-900">
                                {{ $topikKonsultasi }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Biaya Psikolog</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                Rp {{ number_format($biayaPsikolog, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Biaya Admin</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                Rp {{ number_format($biayaAdmin, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Metode Pembayaran</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                {{ $metodePembayaranText }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex justify-between gap-4">
                                <span class="text-base font-semibold text-slate-900">Total Bayar</span>
                                <span class="text-right text-2xl font-semibold text-[#01588E]">
                                    Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('pasien.pembayaran.store') }}" class="mt-7">
                    @csrf
                    <input type="hidden" name="konsultasi_id" value="{{ $konsultasi->id_konsultasi }}">

                    <div class="rounded-[30px] border border-blue-100 bg-blue-50 p-6 md:p-7">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>

                            <div class="w-full">
                                <h3 class="text-lg font-semibold text-slate-900">
                                    Metode Pembayaran Terpilih
                                </h3>

                                <p class="mt-2 text-sm font-normal leading-7 text-slate-500">
                                    Sistem menggunakan metode Transfer Bank otomatis demi keamanan, kecepatan, dan kenyamanan konfirmasi transaksi konsultasi Anda.
                                </p>

                                <div class="mt-5 grid grid-cols-1 gap-4">
                                    <label class="cursor-pointer rounded-2xl border bg-white p-5 transition hover:shadow-sm border-[#01588E]">
                                        <div class="flex items-start gap-3">
                                            <input type="radio"
                                                   name="metode_pembayaran"
                                                   value="transfer_bank"
                                                   class="mt-1"
                                                   checked>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">Transfer Bank</p>
                                                <p class="mt-1 text-xs font-normal leading-5 text-slate-500">
                                                    Bayar melalui Midtrans atau transfer manual, lalu upload bukti pembayaran Anda.
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                @error('metode_pembayaran')
                                    <p class="mt-3 text-sm font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-4">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-[#01588E] px-6 py-4 text-sm font-semibold text-white shadow-[0_18px_35px_rgba(1,88,142,0.22)] transition hover:-translate-y-0.5 hover:bg-[#01446e]">
                            Simpan Metode
                        </button>

                        <button type="button"
                                id="pay-button"
                                class="inline-flex items-center justify-center rounded-2xl bg-[#41AD01] px-6 py-4 text-sm font-semibold text-white shadow-[0_18px_35px_rgba(65,173,1,0.22)] transition hover:-translate-y-0.5 hover:bg-[#329000] disabled:cursor-not-allowed disabled:opacity-60"
                                {{ !$idPembayaran ? 'disabled' : '' }}>
                            Bayar Midtrans
                            <i class="fas fa-arrow-right ml-3"></i>
                        </button>

                        <a href="{{ $uploadBuktiUrl }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-4 text-sm font-semibold text-[#01588E] shadow-sm ring-1 ring-[#01588E]/15 transition hover:-translate-y-0.5 hover:bg-[#01588E]/5 {{ !$idPembayaran ? 'pointer-events-none opacity-50' : '' }}">
                            <i class="fas fa-upload mr-3"></i>
                            Upload Bukti
                        </a>

                        <a href="{{ route('pasien.konsultasi.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-slate-50 px-6 py-4 text-sm font-semibold text-slate-600 ring-1 ring-slate-200 transition hover:bg-slate-100">
                            <i class="fas fa-arrow-left mr-3"></i>
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="xl:col-span-4">
        <div class="sticky top-6 space-y-6">

            <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-[0_24px_70px_rgba(15,23,42,0.08)]">
                <div class="mb-5 flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">
                            Ringkasan
                        </h3>
                        <p class="mt-1 text-sm font-normal text-slate-500">
                            Total pembayaran konsultasi.
                        </p>
                    </div>
                </div>

                <div class="rounded-[26px] bg-slate-50 p-5">
                    <p class="text-sm font-normal text-slate-500">Total Bayar</p>
                    <h2 class="mt-2 text-3xl font-semibold text-[#01588E]">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="mt-5 rounded-[26px] bg-[#41AD01]/5 p-5 ring-1 ring-[#41AD01]/10">
                    <p class="text-sm font-normal text-slate-500">Status Pembayaran</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">
                        {{ ucfirst($statusPembayaran) }}
                    </h3>
                </div>

                <div class="mt-5 rounded-[26px] bg-[#01588E]/5 p-5 ring-1 ring-[#01588E]/10">
                    <p class="text-sm font-normal text-slate-500">Metode Pembayaran</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">
                        {{ $metodePembayaranText }}
                    </h3>
                </div>
            </div>

            <div class="rounded-[30px] bg-gradient-to-br from-[#01588E] to-[#01446e] p-6 text-white shadow-[0_24px_70px_rgba(1,88,142,0.22)]">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                    <i class="fas fa-shield-heart text-xl"></i>
                </div>

                <h3 class="mt-5 text-xl font-semibold">
                    Aman dan Terverifikasi
                </h3>

                <p class="mt-3 text-sm font-normal leading-7 text-blue-100">
                    Setiap pembayaran transfer bank akan melalui sistem enkripsi Midtrans atau verifikasi admin untuk memastikan sesi Anda terjadwal dengan aman.
                </p>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payButton = document.getElementById('pay-button');

        if (!payButton) {
            return;
        }

        payButton.addEventListener('click', function () {
            const snapToken = "{{ $pembayaran->snap_token ?? '' }}";
            const finishUrl = "{{ $finishUrl }}";
            const targetUploadUrl = "{{ $uploadBuktiUrl }}"; 

            if (!snapToken) {
                alert('Snap token pembayaran tidak ditemukan.');
                return;
            }

            if (typeof snap === 'undefined') {
                alert('Midtrans Snap belum siap. Coba refresh halaman.');
                return;
            }

            function handleBypassSuccess(paymentType) {
                fetch(finishUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        payment_type: paymentType || 'transfer_bank'
                    })
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Terima kasih, pembayaran Anda telah diverifikasi oleh MindHaven.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#01588E',
                        background: '#FFFFFF',
                        color: '#1E293B',
                        allowOutsideClick: false
                    }).then((result) => {
                        window.location.href = targetUploadUrl;
                    });
                })
                .catch(function () {
                    window.location.href = targetUploadUrl;
                });
            }

            snap.pay(snapToken, {
                onSuccess: function (result) {
                    handleBypassSuccess(result.payment_type);
                },
                onPending: function (result) {
                    handleBypassSuccess(result.payment_type || 'transfer_bank');
                },
                onError: function () {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function () {
                    handleBypassSuccess('transfer_bank');
                }
            });
        });
    });
</script>

@endsection