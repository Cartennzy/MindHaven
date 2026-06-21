@extends('frontend.layouts.app')

@section('title', 'Metode Konsultasi - MindHaven')
@section('page_title', 'Metode Konsultasi')
@section('page_subtitle', 'Pilih metode, tanggal, dan jadwal konsultasi Anda.')

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $foto = $konsultasi->psikolog->foto_profil && Storage::disk('public')->exists($konsultasi->psikolog->foto_profil)
        ? asset('storage/' . $konsultasi->psikolog->foto_profil)
        : null;

    $metodeKomunikasi = [
        [
            'nama' => 'Konseling via Chat',
            'value' => 'chat',
            'deskripsi' => 'Konsultasi nyaman melalui pesan teks.',
            'icon' => 'fas fa-comments',
            'color' => 'from-sky-500 to-cyan-500',
        ],
        [
            'nama' => 'Konsultasi Video Call',
            'value' => 'video_call',
            'deskripsi' => 'Konsultasi online melalui video call.',
            'icon' => 'fas fa-video',
            'color' => 'from-violet-500 to-indigo-500',
        ],
        [
            'nama' => 'Tatap Muka',
            'value' => 'temu_janji',
            'deskripsi' => 'Pertemuan langsung dengan psikolog.',
            'icon' => 'fas fa-location-dot',
            'color' => 'from-emerald-500 to-green-500',
        ],
    ];

    $bookingClosed = $bookingClosed ?? false;

    $metodeTerpilih = old('metode_konsultasi');
    $tanggalTerpilih = old('tanggal_konsultasi');
    
    // SINKRONISASI DATA LAWAS VALUE: Tangkap string default hasil penapisan dari controller jika ada
    $defaultKeluhanValue = !empty($defaultKeluhanAssessment) ? $defaultKeluhanAssessment : '';
@endphp

<div class="space-y-8">

    @if ($errors->any())
        <div class="rounded-[32px] border border-red-100 bg-red-50 px-6 py-5 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                    <i class="fas fa-circle-exclamation text-lg"></i>
                </div>

                <div class="flex-1">
                    <h3 class="text-base font-black text-red-700">
                        Ada data yang belum sesuai
                    </h3>

                    <ul class="mt-3 space-y-2 pl-5 text-sm font-semibold text-red-600 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-[32px] border border-green-100 bg-green-50 px-6 py-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                    <i class="fas fa-circle-check text-lg"></i>
                </div>

                <div>
                    <h3 class="text-base font-black text-green-700">Berhasil</h3>
                    <p class="mt-1 text-sm font-semibold text-green-600">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-[32px] border border-red-100 bg-red-50 px-6 py-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-700">
                    <i class="fas fa-triangle-exclamation text-lg"></i>
                </div>

                <div>
                    <h3 class="text-base font-black text-red-700">Terjadi Kesalahan</h3>
                    <p class="mt-1 text-sm font-semibold text-red-600">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">

        <div class="xl:col-span-2">

            <form action="{{ route('pasien.konsultasi.store-metode', ['konsultasi' => $konsultasi->id_konsultasi]) }}"
                  method="POST"
                  id="metodeForm"
                  novalidate>

                @csrf
                @method('PUT')

                <input type="hidden" name="metode_konsultasi" id="metodeInput" value="{{ $metodeTerpilih }}">
                <input type="hidden" name="tanggal_konsultasi" id="tanggalInput" value="{{ $tanggalTerpilih }}">

                <div class="overflow-hidden rounded-[36px] bg-white shadow-soft">

                    <div class="relative overflow-hidden bg-gradient-to-br from-[#01588E] via-[#0169AB] to-[#01446e] p-8 text-white">
                        <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-cyan-400/20 blur-3xl"></div>

                        <div class="relative z-10">
                            <div class="flex h-16 w-16 items-center justify-center rounded-[24px] bg-white/15 backdrop-blur">
                                <i class="fas fa-calendar-check text-2xl"></i>
                            </div>

                            <h2 class="mt-6 text-3xl font-black leading-tight">
                                Lengkapi Jadwal Konsultasi
                            </h2>

                            <p class="mt-4 max-w-2xl text-sm font-medium leading-7 text-blue-100">
                                Pilih metode komunikasi, tanggal konsultasi, jam sesi, dan isi keluhan Anda sebelum konsultasi dimulai bersama psikolog.
                            </p>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">

                        <div>
                            <div class="mb-6">
                                <h3 class="text-2xl font-black text-slate-800">
                                    Pilih Metode Pembayaran
                                </h3>

                                <p class="mt-2 text-sm font-semibold text-slate-500">
                                    Pilih metode yang paling nyaman untuk sesi konsultasi Anda.
                                </p>

                                @error('metode_konsultasi')
                                    <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                                @foreach($metodeKomunikasi as $metode)
                                    <button type="button"
                                            class="method-card group relative overflow-hidden rounded-[30px] border {{ $errors->has('metode_konsultasi') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-white' }} p-6 text-left transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                                            data-method="{{ $metode['value'] }}">

                                        <div class="absolute inset-0 opacity-0 transition duration-300 group-hover:opacity-100 bg-gradient-to-br {{ $metode['color'] }}"></div>

                                        <div class="relative z-10">
                                            <div class="method-icon flex h-20 w-20 items-center justify-center rounded-[24px] bg-slate-100 transition duration-300">
                                                <i class="{{ $metode['icon'] }} text-4xl text-[#01588E]"></i>
                                            </div>

                                            <h3 class="method-title mt-6 text-lg font-black text-slate-800 transition duration-300">
                                                {{ $metode['nama'] }}
                                            </h3>

                                            <p class="method-desc mt-3 text-sm font-semibold leading-7 text-slate-500 transition duration-300">
                                                {{ $metode['deskripsi'] }}
                                            </p>

                                            <div class="method-action mt-6 inline-flex items-center text-sm font-black text-[#41AD01] transition duration-300">
                                                Pilih Metode
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-10 rounded-[32px] bg-slate-50 p-6">
                            <div class="mb-6">
                                <h3 class="text-2xl font-black text-slate-800">
                                    Pilih Tanggal Konsultasi
                                </h3>

                                <p class="mt-2 text-sm font-semibold text-slate-500">
                                    Pilih metode konsultasi terlebih dahulu sebelum memilih tanggal.
                                </p>

                                @error('tanggal_konsultasi')
                                    <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="availableDateGrid" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3"></div>
                        </div>

                        <div class="mt-10">
                            <label class="mb-3 block text-sm font-black text-slate-700">Jam Konsultasi</label>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                                    <i class="fas fa-clock"></i>
                                </div>

                                <input type="time"
                                       name="jam_konsultasi"
                                       id="jam_konsultasi"
                                       value="{{ old('jam_konsultasi', $konsultasi->jam_konsultasi ? \Carbon\Carbon::parse($konsultasi->jam_konsultasi)->format('H:i') : '') }}"
                                       class="w-full rounded-[24px] border {{ $errors->has('jam_konsultasi') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-white' }} py-4 pl-14 pr-20 text-sm font-semibold text-slate-700 outline-none transition focus:border-[#01588E] focus:ring-4 focus:ring-[#01588E]/10">

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-5 text-sm font-black text-[#01588E]">WIB</div>
                            </div>

                            <p id="jamHelperText" class="mt-2 text-xs font-semibold text-slate-400 hidden">
                                <i class="fas fa-circle-info mr-1"></i> Jam praktik hari ini: <span id="rangeJamText"></span>
                            </p>

                            @error('jam_konsultasi')
                                <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- INPUT KELUHARAN UTAMA PASIEN (SINKRONISASI DATA HASIL TES ASESMEN MANDIRI PARAH) --}}
                        <div class="mt-10">
                            <label class="mb-3 block text-sm font-black text-slate-700">Keluhan</label>
                            
                            <textarea name="keluhan"
                                      rows="6"
                                      class="w-full rounded-[28px] border {{ $errors->has('keluhan') ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-white' }} px-5 py-5 text-sm font-semibold leading-7 text-slate-700 outline-none transition focus:border-[#01588E] focus:ring-4 focus:ring-[#01588E]/10"
                                      placeholder="Ceritakan kondisi atau keluhan Anda...">{{ old('keluhan', $konsultasi->keluhan === 'Keluhan akan diisi setelah pembayaran berhasil.' ? $defaultKeluhanValue : $konsultasi->keluhan) }}</textarea>

                            @error('keluhan')
                                <p class="mt-3 text-sm font-black text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                            <button type="submit" class="inline-flex items-center justify-center rounded-[22px] bg-[#41AD01] px-8 py-4 text-sm font-black text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:bg-[#329000]"><i class="fas fa-floppy-disk mr-3"></i> Simpan Jadwal Konsultasi</button>
                            <a href="{{ route('pasien.konsultasi.show', ['konsultasi' => $konsultasi->id_konsultasi]) }}" class="inline-flex items-center justify-center rounded-[22px] bg-[#01588E] px-8 py-4 text-sm font-black text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:bg-[#01446e]"><i class="fas fa-arrow-left mr-3"></i> Kembali</a>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        <div class="xl:col-span-1">
            <div class="space-y-6 sticky top-24">
                <div class="rounded-[34px] bg-white p-6 shadow-soft">
                    <div class="flex items-start gap-5">
                        @if($foto)
                            <img src="{{ $foto }}" alt="{{ $konsultasi->psikolog->nama_lengkap }}" class="h-28 w-24 rounded-[24px] object-cover shadow-lg">
                        @else
                            <div class="flex h-28 w-24 items-center justify-center rounded-[24px] bg-[#01588E]/10 text-4xl font-black text-[#01588E] shadow-lg">{{ strtoupper(substr($konsultasi->psikolog->nama_lengkap, 0, 1)) }}</div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="inline-flex items-center rounded-2xl bg-green-100 px-4 py-2 text-xs font-black text-green-700"><i class="fas fa-badge-check mr-2"></i> Pembayaran Berhasil</div>
                            <h3 class="mt-4 text-xl font-black leading-tight text-slate-800">{{ $konsultasi->psikolog->nama_lengkap }}</h3>
                            <p class="mt-2 text-sm font-bold text-slate-500">{{ $konsultasi->psikolog->spesialisasi }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[34px] bg-white p-6 shadow-soft">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#01588E]/10 text-[#01588E]"><i class="fas fa-wallet text-xl"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Ringkasan Pembayaran</h3>
                            <p class="mt-1 text-sm font-semibold text-slate-500">Detail pembayaran konsultasi.</p>
                        </div>
                    </div>
                    <div class="mt-6 space-y-5">
                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-5 py-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-wide text-slate-400">Biaya Konsultasi</p>
                                <p class="mt-1 text-sm font-semibold text-slate-500">Sesi Konsultasi Psikolog</p>
                            </div>
                            <h4 class="text-lg font-black text-slate-800">Rp {{ number_format($konsultasi->harga ?? 0, 0, ',', '.') }}</h4>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl border border-green-100 bg-green-50 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700"><i class="fas fa-circle-check"></i></div>
                                <div>
                                    <p class="text-sm font-black text-slate-800">Status Pembayaran</p>
                                    <p class="text-xs font-semibold text-slate-500">Pembayaran telah diverifikasi</p>
                                </div>
                            </div>
                            <span class="rounded-xl bg-green-600 px-4 py-2 text-xs font-black text-white">LUNAS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .method-card.active, .date-card.active { border-color:#01588E !important; background:linear-gradient(135deg,#01588E 0%,#0878b8 100%) !important; box-shadow:0 25px 50px rgba(1,88,142,.25) !important; transform:translateY(-6px); }
    .method-card.active .method-title, .method-card.active .method-desc, .method-card.active .method-action { color:#ffffff !important; }
    .method-card.active .method-icon { background:#ffffff !important; }
    .method-card.active .method-icon i { color:#01588E !important; }
    .date-card.active h3, .date-card.active p { color:#ffffff !important; }
    .date-card.active { color:white !important; }
    .date-card.disabled { opacity: 0.6; cursor: not-allowed; background-color: #F1F5F9 !important; border-color: #E2E8F0 !important; }
    .date-card.disabled:hover { transform: none !important; box-shadow: none !important; border-color: #E2E8F0 !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const methodCards = document.querySelectorAll('.method-card');
        const metodeInput = document.getElementById('metodeInput');
        const tanggalInput = document.getElementById('tanggalInput');
        const availableDateGrid = document.getElementById('availableDateGrid');
        const metodeForm = document.getElementById('metodeForm');
        const jamInput = document.getElementById('jam_konsultasi');
        const keluhanInput = document.querySelector('textarea[name="keluhan"]');
        const jamHelperText = document.getElementById('jamHelperText');
        const rangeJamText = document.getElementById('rangeJamText');

        const bookingClosedFromServer = @json($bookingClosed);
        const psikologId = {{ $konsultasi->id_psikolog }};
        const jadwalUrl = "{{ route('pasien.konsultasi.jadwal-psikolog', ':id') }}";

        function showMindHavenAlert(title, text) {
            Swal.fire({ icon: 'warning', title: title, text: text, confirmButtonText: 'Mengerti', confirmButtonColor: '#01588E', background: '#FFFFFF', color: '#1E293B' });
        }

        function getJamWib() {
            const formatter = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false });
            const waktu = formatter.format(new Date());
            const parts = waktu.replace('.', ':').split(':');
            return parseInt(parts[0], 10);
        }

        function isBookingClosedClient() {
            const jamWib = getJamWib();
            return jamWib < 6 || jamWib >= 21;
        }

        function showBookingClosedAlert() {
            Swal.fire({ icon: 'warning', title: 'Booking Konsultasi Ditutup', text: 'Akses booking konsultasi hanya tersedia dari pukul 06.00 sampai 21.00 WIB.', confirmButtonText: 'Kembali', confirmButtonColor: '#01588E', allowOutsideClick: false, allowEscapeKey: false }).then(() => {
                window.location.href = "{{ route('pasien.konsultasi.show', ['konsultasi' => $konsultasi->id_konsultasi]) }}";
            });
        }

        function formatTanggal(date) {
            return date.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
        }

        function toDateInputValue(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        async function generateAvailableDates() {
            availableDateGrid.innerHTML = '';
            const response = await fetch(jadwalUrl.replace(':id', psikologId));
            const data = await response.json();
            const jadwals = data.jadwal;
            const bookedDates = data.booked_dates;

            const hariMap = { 'Minggu': 0, 'Senin': 1, 'Selasa': 2, 'Rabu': 3, 'Kamis': 4, 'Jumat': 5, 'Sabtu': 6 };
            const today = new Date();

            for (let i = 1; i <= 30; i++) {
                const date = new Date();
                date.setDate(today.getDate() + i);
                const dayNumber = date.getDay();

                const cocok = jadwals.find(j => hariMap[j.hari] === dayNumber);
                if (!cocok) continue;
                
                const dateValue = toDateInputValue(date);
                const totalBooked = bookedDates[dateValue] || 0;
                const isFull = totalBooked >= 1;

                const button = document.createElement('button');
                button.type = 'button';
                button.className = isFull ? 'date-card disabled rounded-[28px] border border-slate-200 p-5 text-left' : 'date-card rounded-[28px] border border-slate-200 bg-white p-5 text-left transition-all duration-300 hover:-translate-y-1 hover:border-[#01588E]/40 hover:shadow-xl';
                
                button.dataset.date = dateValue;
                button.dataset.start = cocok.jam_mulai.substring(0,5);
                button.dataset.end = cocok.jam_selesai.substring(0,5);
                
                const badgeColor = isFull ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                const statusText = isFull ? 'Penuh' : 'Tersedia';

                button.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center rounded-xl bg-[#01588E]/10 px-3 py-2 text-xs font-black text-[#01588E]">${cocok.jam_mulai.substring(0,5)} - ${cocok.jam_selesai.substring(0,5)}</span>
                    <span class="inline-flex items-center rounded-xl ${badgeColor} px-3 py-1 text-xs font-bold">${statusText}</span>
                </div>
                <h3 class="mt-4 text-base font-black leading-7 text-slate-800">${formatTanggal(date)}</h3>
                <p class="mt-3 text-sm font-semibold text-slate-500">${isFull ? 'Jadwal tidak dapat dipilih' : 'Pilih jadwal ini'}</p>`;
                
                if (!isFull) {
                    button.addEventListener('click', function() {
                        document.querySelectorAll('.date-card').forEach(item => item.classList.remove('active'));
                        button.classList.add('active');
                        tanggalInput.value = dateValue;
                        jamInput.min = cocok.jam_mulai.substring(0,5);
                        jamInput.max = cocok.jam_selesai.substring(0,5);
                        rangeJamText.textContent = `${cocok.jam_mulai.substring(0,5)} - ${cocok.jam_selesai.substring(0,5)} WIB`;
                        jamHelperText.classList.remove('hidden');
                        if (jamInput.value && (jamInput.value < jamInput.min || jamInput.value > jamInput.max)) jamInput.value = '';
                    });
                } else {
                    button.addEventListener('click', function() { showMindHavenAlert('Jadwal Penuh', 'Maaf, kuota konsultasi pada tanggal tersebut sudah penuh. Silakan pilih hari lain.'); });
                }
                availableDateGrid.appendChild(button);
            }
        }

        jamInput.addEventListener('change', function() {
            if (jamInput.min && jamInput.max && (jamInput.value < jamInput.min || jamInput.value > jamInput.max)) {
                showMindHavenAlert('Jam Tidak Sesuai', `Silakan pilih jam konsultasi antara ${jamInput.min} sampai ${jamInput.max} WIB sesuai jadwal praktik psikolog.`);
                jamInput.value = '';
            }
        });

        methodCards.forEach(function (card) {
            if (metodeInput.value && metodeInput.value === card.dataset.method) card.classList.add('active');
            card.addEventListener('click', function () {
                if (bookingClosedFromServer || isBookingClosedClient()) { showBookingClosedAlert(); return; }
                methodCards.forEach(item => item.classList.remove('active'));
                card.classList.add('active');
                metodeInput.value = card.dataset.method;
                tanggalInput.value = '';
                jamInput.value = '';
                jamInput.removeAttribute('min');
                jamInput.removeAttribute('max');
                jamHelperText.classList.add('hidden');
                document.querySelectorAll('.date-card').forEach(item => item.classList.remove('active'));
            });
        });

        generateAvailableDates();

        if (bookingClosedFromServer || isBookingClosedClient()) showBookingClosedAlert();

        if (metodeForm) {
            metodeForm.addEventListener('submit', function (event) {
                if (bookingClosedFromServer || isBookingClosedClient()) { event.preventDefault(); showBookingClosedAlert(); return; }
                if (!metodeInput.value) { event.preventDefault(); showMindHavenAlert('Metode Belum Dipilih', 'Silakan pilih metode konsultasi terlebih dahulu.'); return; }
                if (!tanggalInput.value) { event.preventDefault(); showMindHavenAlert('Tanggal Belum Dipilih', 'Silakan pilih tanggal konsultasi terlebih dahulu.'); return; }
                if (!jamInput.value) { event.preventDefault(); showMindHavenAlert('Jam Belum Diisi', 'Silakan isi jam konsultasi terlebih dahulu.'); return; }
                if (jamInput.min && jamInput.max && (jamInput.value < jamInput.min || jamInput.value > jamInput.max)) {
                    event.preventDefault(); showMindHavenAlert('Jam Luar Batas Praktik', `Jam konsultasi yang Anda masukkan (${jamInput.value}) di luar jam praktik psikolog pada hari tersebut (${jamInput.min} - ${jamInput.max} WIB).`); return;
                }
                if (!keluhanInput.value.trim()) { event.preventDefault(); showMindHavenAlert('Keluhan Belum Diisi', 'Silakan isi keluhan konsultasi terlebih dahulu.'); return; }
            });
        }
    });
</script>

@endsection