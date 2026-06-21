@extends('frontend.layouts.app')

@section('title', 'Upload Bukti Pembayaran - MindHaven')
@section('page_title', 'Upload Bukti Pembayaran')
@section('page_subtitle', 'Unggah bukti pembayaran untuk melanjutkan pengaturan konsultasi.')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $konsultasi = $pembayaran->konsultasi;
    $psikolog = $konsultasi?->psikolog;

    $idPembayaran = $pembayaran->id_pembayaran ?? $pembayaran->id ?? null;
    $idKonsultasi = $pembayaran->id_konsultasi ?? $konsultasi?->id_konsultasi ?? $konsultasi?->id ?? null;

    $foto = $psikolog && $psikolog->foto_profil && Storage::disk('public')->exists($psikolog->foto_profil)
        ? asset('storage/' . $psikolog->foto_profil)
        : null;

    $biayaPsikolog = $pembayaran->biaya_psikolog ?? 0;
    $biayaAdmin = $pembayaran->biaya_admin ?? 0;
    $totalBayar = $pembayaran->total_pembayaran ?? ($biayaPsikolog + $biayaAdmin);

    $storeBuktiUrl = $idPembayaran
        ? route('pasien.pembayaran.store-bukti', ['pembayaran' => $idPembayaran])
        : '#';

    // PERBAIKAN UTL: Mengubah 'consultasi' menjadi 'konsultasi' agar lolos dari UrlGenerationException Laravel
    $metodeUrl = $idKonsultasi
        ? route('pasien.konsultasi.metode', ['konsultasi' => $idKonsultasi])
        : '#';

    $pembayaranUrl = $idKonsultasi
        ? route('pasien.pembayaran.create', ['konsultasi_id' => $idKonsultasi])
        : route('pasien.konsultasi.index');
@endphp

<div class="space-y-7">

    @if (session('success'))
        <div class="rounded-[28px] border border-green-100 bg-green-50 px-6 py-5 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-[28px] border border-red-100 bg-red-50 px-6 py-5 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

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
                            <i class="fas fa-cloud-arrow-up mr-2"></i>
                            Verifikasi pembayaran
                        </div>

                        <h2 class="text-3xl font-semibold leading-tight tracking-tight md:text-5xl">
                            Upload Bukti Pembayaran
                        </h2>

                        <p class="mt-4 max-w-3xl text-base font-normal leading-8 text-blue-50 md:text-lg">
                            Unggah screenshot atau bukti pembayaran. Setelah bukti tersimpan, Anda dapat memilih metode dan jadwal konsultasi.
                        </p>
                    </div>
                </div>

                <div class="p-6 md:p-8">

                    @if (!$idPembayaran)
                        <div class="mb-6 rounded-[28px] border border-red-100 bg-red-50 px-6 py-5 text-sm font-medium text-red-700">
                            Data pembayaran tidak valid. ID pembayaran tidak ditemukan.
                        </div>
                    @endif

                    @if($pembayaran->bukti_pembayaran)
                        <div class="mb-7 rounded-[30px] border border-green-100 bg-green-50 p-6 md:p-7">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-green-700 shadow-sm">
                                    <i class="fas fa-circle-check text-xl"></i>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-green-800">
                                        Bukti pembayaran sudah diunggah
                                    </h3>

                                    <p class="mt-2 text-sm font-normal leading-7 text-green-700">
                                        Anda bisa mengganti bukti melalui form di bawah, atau lanjut memilih metode konsultasi.
                                    </p>

                                    @php
                                        $buktiExt = strtolower(pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION));
                                    @endphp

                                    <div class="mt-5 overflow-hidden rounded-[24px] bg-white p-3 shadow-sm">
                                        @if(in_array($buktiExt, ['jpg', 'jpeg', 'png']))
                                            <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                                 alt="Bukti Pembayaran"
                                                 class="max-h-[420px] w-full rounded-2xl object-contain">
                                        @else
                                            <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                               target="_blank"
                                               class="flex items-center justify-center rounded-2xl bg-slate-50 px-6 py-10 text-sm font-semibold text-[#01588E] ring-1 ring-slate-200">
                                                <i class="fas fa-file-pdf mr-3 text-xl"></i>
                                                Lihat Bukti Pembayaran PDF
                                            </a>
                                        @endif
                                    </div>

                                    <a href="{{ $metodeUrl }}"
                                       class="mt-5 inline-flex items-center justify-center rounded-2xl bg-[#41AD01] px-7 py-4 text-sm font-semibold text-white shadow-[0_18px_35px_rgba(65,173,1,0.22)] transition hover:-translate-y-0.5 hover:bg-[#329000]">
                                        Lanjut Pilih Metode
                                        <i class="fas fa-arrow-right ml-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ $storeBuktiUrl }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="uploadBuktiForm"
                          novalidate>

                        @csrf

                        <div class="rounded-[30px] border {{ $errors->has('bukti_pembayaran') ? 'border-red-200 bg-red-50' : 'border-slate-100 bg-slate-50' }} p-6 md:p-8">
                            <div class="flex flex-col gap-6 md:flex-row md:items-start">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-[24px] bg-white text-[#01588E] shadow-sm">
                                    <i class="fas fa-cloud-arrow-up text-3xl"></i>
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-2xl font-semibold text-slate-900">
                                        Pilih file bukti pembayaran
                                    </h3>

                                    <p class="mt-2 text-sm font-normal leading-7 text-slate-500">
                                        Format file: JPG, JPEG, PNG, atau PDF. Maksimal ukuran file 5MB.
                                    </p>

                                    <label for="buktiPembayaranInput"
                                           id="dropArea"
                                           class="mt-5 flex cursor-pointer flex-col items-center justify-center rounded-[28px] border-2 border-dashed {{ $errors->has('bukti_pembayaran') ? 'border-red-300 bg-white' : 'border-slate-200 bg-white' }} px-6 py-10 text-center transition hover:border-[#01588E]/40 hover:bg-[#01588E]/5">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]">
                                            <i class="fas fa-file-arrow-up text-2xl"></i>
                                        </div>

                                        <p id="dropTitleText" class="mt-4 text-base font-semibold text-slate-800">
                                            Klik atau drag file ke sini
                                        </p>

                                        <p id="fileNameText" class="mt-2 text-sm font-normal text-slate-500">
                                            Belum ada file dipilih
                                        </p>

                                        <input type="file"
                                               name="bukti_pembayaran"
                                               id="buktiPembayaranInput"
                                               class="hidden">
                                    </label>

                                    @error('bukti_pembayaran')
                                        <p class="mt-3 text-sm font-semibold text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="previewWrapper" class="mt-7 hidden rounded-[30px] border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="mb-5 flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#41AD01]/10 text-[#41AD01]">
                                    <i class="fas fa-eye text-xl"></i>
                                </div>

                                <div>
                                    <h3 class="text-xl font-semibold text-slate-900">
                                        Preview Bukti
                                    </h3>
                                    <p class="mt-1 text-sm font-normal text-slate-500">
                                        Pastikan bukti pembayaran terbaca jelas sebelum diunggah.
                                    </p>
                                </div>
                            </div>

                            <div id="imagePreviewBox" class="hidden overflow-hidden rounded-[24px] bg-slate-50 p-3">
                                <img id="previewImage"
                                     src=""
                                     alt="Preview Bukti Pembayaran"
                                     class="max-h-[420px] w-full rounded-2xl object-contain">
                            </div>

                            <div id="pdfPreviewBox" class="hidden rounded-[24px] bg-slate-50 p-6 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                                    <i class="fas fa-file-pdf text-3xl"></i>
                                </div>

                                <p id="pdfNameText" class="mt-4 text-sm font-semibold text-slate-800">
                                    File PDF dipilih
                                </p>

                                <p class="mt-2 text-xs font-medium text-slate-500">
                                    Preview gambar tidak tersedia untuk PDF, tetapi file tetap bisa diupload.
                                </p>
                            </div>
                        </div>

                        <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[#41AD01] px-7 py-4 text-sm font-semibold text-white shadow-[0_18px_35px_rgba(65,173,1,0.22)] transition hover:-translate-y-0.5 hover:bg-[#329000] disabled:cursor-not-allowed disabled:opacity-60"
                                    {{ !$idPembayaran ? 'disabled' : '' }}>
                                Upload Bukti
                                <i class="fas fa-arrow-right ml-3"></i>
                            </button>

                            <a href="{{ $pembayaranUrl }}"
                               class="inline-flex items-center justify-center rounded-2xl bg-white px-7 py-4 text-sm font-semibold text-[#01588E] shadow-sm ring-1 ring-[#01588E]/15 transition hover:-translate-y-0.5 hover:bg-[#01588E]/5">
                                <i class="fas fa-arrow-left mr-3"></i>
                                Kembali ke Pembayaran
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
                            <i class="fas fa-user-doctor text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Detail Konsultasi
                            </h3>
                            <p class="mt-1 text-sm font-normal text-slate-500">
                                Psikolog yang dipilih.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4 rounded-[26px] bg-slate-50 p-4">
                        @if($foto)
                            <img src="{{ $foto }}"
                                 alt="{{ $psikolog->nama_lengkap ?? 'Psikolog' }}"
                                 class="h-24 w-20 rounded-[22px] object-cover shadow-sm">
                        @else
                            <div class="flex h-24 w-20 shrink-0 items-center justify-center rounded-[22px] bg-[#01588E]/10 text-3xl font-semibold text-[#01588E] shadow-sm">
                                {{ strtoupper(substr($psikolog->nama_lengkap ?? 'P', 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <h4 class="text-base font-semibold leading-6 text-slate-900">
                                {{ $psikolog->nama_lengkap ?? '-' }}
                            </h4>

                            <p class="mt-1 line-clamp-2 text-sm font-normal leading-6 text-slate-500">
                                {{ $psikolog->spesialisasi ?? '-' }}
                            </p>

                            <div class="mt-3 inline-flex items-center rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                                <i class="fas fa-circle-check mr-1.5"></i>
                                Terverifikasi
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[30px] border border-slate-100 bg-white p-6 shadow-[0_24px_70px_rgba(15,23,42,0.08)]">
                    <div class="mb-5 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#41AD01]/10 text-[#41AD01]">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">
                                Ringkasan Pembayaran
                            </h3>
                            <p class="mt-1 text-sm font-normal text-slate-500">
                                Data pembayaran Anda.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Order ID</span>
                            <span class="max-w-[58%] break-all text-right text-sm font-semibold text-slate-900">
                                {{ $pembayaran->id_order ?? '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Biaya Psikolog</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                Rp {{ number_format($pembayaran->biaya_psikolog ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm font-normal text-slate-500">Biaya Admin</span>
                            <span class="text-right text-sm font-semibold text-slate-900">
                                Rp {{ number_format($pembayaran->biaya_admin ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex justify-between gap-4">
                                <span class="text-base font-semibold text-slate-900">Total</span>
                                <span class="text-right text-xl font-semibold text-[#01588E]">
                                    Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-green-100 px-5 py-4 text-center text-sm font-semibold text-green-700">
                            Status: {{ ucfirst($pembayaran->status_pembayaran ?? 'pending') }}
                        </div>
                    </div>
                </div>

                <div class="rounded-[30px] border border-[#01588E]/10 bg-[#01588E]/5 p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-[#01588E] shadow-sm">
                            <i class="fas fa-list-check text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">
                                Setelah Bukti Diupload
                            </h3>

                            <p class="mt-2 text-sm font-normal leading-7 text-slate-600">
                                Anda akan melanjutkan ke halaman metode konsultasi untuk memilih jenis layanan, tanggal, jam, dan melengkapi keluhan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('uploadBuktiForm');
        const input = document.getElementById('buktiPembayaranInput');
        const dropArea = document.getElementById('dropArea');
        const dropTitleText = document.getElementById('dropTitleText');
        const fileNameText = document.getElementById('fileNameText');
        const previewWrapper = document.getElementById('previewWrapper');
        const previewImage = document.getElementById('previewImage');
        const imagePreviewBox = document.getElementById('imagePreviewBox');
        const pdfPreviewBox = document.getElementById('pdfPreviewBox');
        const pdfNameText = document.getElementById('pdfNameText');

        function showUploadAlert(title, text) {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#1E293B'
            });
        }

        function resetPreview() {
            input.value = '';
            previewWrapper.classList.add('hidden');
            imagePreviewBox.classList.add('hidden');
            pdfPreviewBox.classList.add('hidden');
            previewImage.src = '';
            pdfNameText.textContent = 'File PDF dipilih';
            fileNameText.textContent = 'Belum ada file dipilih';

            if (dropTitleText) {
                dropTitleText.textContent = 'Klik atau drag file ke sini';
            }
        }

        function showSelectedFile(file) {
            if (!file) {
                resetPreview();
                return;
            }

            fileNameText.textContent = file.name;

            if (dropTitleText) {
                dropTitleText.textContent = 'File berhasil dipilih';
            }

            previewWrapper.classList.remove('hidden');

            imagePreviewBox.classList.add('hidden');
            pdfPreviewBox.classList.add('hidden');
            previewImage.src = '';

            if (file.type === 'application/pdf') {
                pdfPreviewBox.classList.remove('hidden');
                pdfNameText.textContent = file.name;
                return;
            }

            if (file.type === 'image/jpeg' || file.type === 'image/jpg' || file.type === 'image/png') {
                imagePreviewBox.classList.remove('hidden');

                const reader = new FileReader();

                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                };

                reader.readAsDataURL(file);
                return;
            }

            pdfPreviewBox.classList.remove('hidden');
            pdfNameText.textContent = file.name;
        }

        function validateFile(file) {
            if (!file) {
                showUploadAlert('Bukti Pembayaran Belum Diupload', 'Silakan pilih file bukti pembayaran terlebih dahulu.');
                return false;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'application/pdf'
            ];

            const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
            const extension = file.name.split('.').pop().toLowerCase();

            if (!allowedTypes.includes(file.type) || !allowedExtensions.includes(extension)) {
                showUploadAlert('Format File Tidak Sesuai', 'Format bukti pembayaran harus JPG, JPEG, PNG, atau PDF.');
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                showUploadAlert('Ukuran File Terlalu Besar', 'Ukuran bukti pembayaran maksimal 5 MB.');
                return false;
            }

            return true;
        }

        if (!input) {
            return;
        }

        input.addEventListener('change', function () {
            const file = input.files[0];
            showSelectedFile(file);
        });

        if (dropArea) {
            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropArea.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    dropArea.classList.add('border-[#01588E]', 'bg-[#01588E]/5');
                    dropArea.classList.remove('border-slate-200');

                    if (dropTitleText) {
                        dropTitleText.textContent = 'Lepaskan file di sini';
                    }
                });
            });

            ['dragleave', 'drop'].forEach(function (eventName) {
                dropArea.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    dropArea.classList.remove('border-[#01588E]', 'bg-[#01588E]/5');
                    dropArea.classList.add('border-slate-200');

                    if (dropTitleText && !input.files.length) {
                        dropTitleText.textContent = 'Klik atau drag file ke sini';
                    }
                });
            });

            dropArea.addEventListener('drop', function (event) {
                const files = event.dataTransfer.files;

                if (!files || files.length === 0) {
                    return;
                }

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                input.files = dataTransfer.files;

                showSelectedFile(files[0]);
            });
        }

        if (form) {
            form.addEventListener('submit', function (event) {
                const file = input.files[0];

                if (!validateFile(file)) {
                    event.preventDefault();
                }
            });
        }
    });
</script>

@endsection