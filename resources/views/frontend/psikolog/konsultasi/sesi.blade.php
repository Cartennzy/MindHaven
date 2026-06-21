@extends('frontend.layouts.psikolog')

@section('title', 'Sesi Konsultasi')
@section('page-title', 'Sesi Konsultasi')

@section('content')

@php
    $pasien = $konsultasi->pasien;
    $userPasien = $pasien->user ?? null;
    $psikolog = $konsultasi->psikolog ?? auth()->user()->psikolog ?? null;
    $detail = $konsultasi->detailKonsultasi;

    $namaPasien = $pasien->nama_lengkap ?? $userPasien->name ?? 'Pasien';
    $emailPasien = $userPasien->email ?? '-';
    $namaPsikolog = $psikolog->nama_lengkap ?? auth()->user()->name ?? 'Psikolog';

    $metode = $konsultasi->metode_konsultasi ?? '-';
    $topik = $konsultasi->topik_konsultasi ?? $konsultasi->keluhan ?? '';

    $metodeText = match ($metode) {
        'chat' => 'Konseling via Chat',
        'video_call' => 'Konsultasi Video Call',
        'temu_janji' => 'Tatap Muka',
        'online' => 'Konseling via Chat',
        'offline' => 'Tatap Muka',
        'video' => 'Konsultasi Video Call',
        'tatap_muka' => 'Tatap Muka',
        default => '-',
    };
@endphp

<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-2xl bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.06)]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-semibold text-[#0A2540]">
                    Sesi Konsultasi: {{ $namaPasien }}
                </h2>
                <p class="mt-2 text-sm font-medium text-slate-500">
                    Sesi aktif bersama pasien menggunakan metode {{ $metodeText }}.
                </p>
            </div>
            
            <div class="flex items-center gap-3 bg-[#F8FAFC] p-3 rounded-2xl border border-slate-100">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF4FF] text-sm font-bold text-[#155EEF]">
                    {{ strtoupper(substr($namaPasien, 0, 1)) }}
                </div>
                <div class="text-left">
                    <p class="text-sm font-semibold text-[#0A2540]">{{ $namaPasien }}</p>
                    <p class="text-xs font-medium text-slate-400">{{ $emailPasien }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">

        <div class="rounded-[2rem] bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.06)] w-full">

            @if($metode === 'chat' || $metode === 'online')

                <div class="rounded-3xl bg-[#EEF4FF] p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl text-[#155EEF]">
                            <i class="fas fa-comments"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold text-[#0A2540]">
                                Ruang Konseling Chat
                            </h3>
                            <p class="mt-1 text-sm font-medium text-slate-600">
                                Ruang percakapan langsung antara psikolog dan pasien selama konsultasi berlangsung.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-100 bg-[#F8FAFC] p-5">
                    <div id="chatBox" class="max-h-[500px] min-h-[400px] space-y-4 overflow-y-auto pr-2"></div>

                    @if($konsultasi->status === 'diproses')
                        <div class="mt-6 flex gap-3">
                            <input type="text"
                                   id="chatInput"
                                   placeholder="Ketik pesan di sini..."
                                   class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">

                            <button type="button"
                                    id="sendChatBtn"
                                    class="rounded-2xl bg-[#41AD01] px-8 py-4 text-sm font-semibold text-white transition hover:bg-[#329000]">
                                Kirim
                            </button>
                        </div>
                    @else
                        <div class="mt-6 rounded-2xl bg-slate-100 p-4 text-sm font-semibold text-slate-500">
                            Konsultasi sudah tidak dalam status diproses, sehingga pesan baru tidak dapat dikirim.
                        </div>
                    @endif
                </div>

            @elseif($metode === 'video_call' || $metode === 'video')

                <div class="rounded-3xl bg-[#EEF4FF] p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl text-[#155EEF]">
                            <i class="fas fa-video"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold text-[#0A2540]">
                                Konsultasi Video Call
                            </h3>
                            <p class="mt-1 text-sm font-medium text-slate-600">
                                Sesi dilakukan secara online melalui video call sesuai jadwal yang sudah dipilih pasien.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-100 bg-[#F8FAFC] p-6">
                    <p class="text-xs font-semibold uppercase text-slate-400">Link Sesi Video</p>
                    <p class="mt-2 text-sm font-medium leading-7 text-slate-600">
                        Link video call dapat dikirimkan psikolog kepada pasien sesuai jadwal konsultasi.
                    </p>
                </div>

            @elseif($metode === 'temu_janji' || $metode === 'offline' || $metode === 'tatap_muka')

                <div class="rounded-3xl bg-[#EEF4FF] p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl text-[#155EEF]">
                            <i class="fas fa-location-dot"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold text-[#0A2540]">
                                Konsultasi Tatap Muka
                            </h3>
                            <p class="mt-1 text-sm font-medium text-slate-600">
                                Sesi dilakukan secara langsung sesuai jadwal yang sudah dipilih pasien.
                            </p>
                        </div>
                    </div>
                </div>

            @else

                <div class="rounded-3xl bg-yellow-50 p-6 text-sm font-medium text-yellow-700">
                    Metode konsultasi belum tersedia.
                </div>

            @endif

            <form action="{{ route('psikolog.konsultasi.update', $konsultasi->id_konsultasi) }}"
                  method="POST"
                  class="mt-8 rounded-[2rem] border border-slate-100 bg-white p-6 shadow-[0_18px_55px_rgba(15,23,42,0.06)]"
                  novalidate>
                @csrf
                @method('PUT')

                <h3 class="text-2xl font-semibold text-[#0A2540]">
                    Form Hasil Konsultasi
                </h3>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Isi hasil konsultasi pasien. Data ini akan tersimpan ke tabel detail konsultasi.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-5">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Status Konsultasi
                        </label>
                        <select name="status"
                                class="w-full rounded-2xl border {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">
                            <option value="diproses" {{ old('status', $konsultasi->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status', $konsultasi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $konsultasi->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>

                        @error('status')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Keluhan Utama
                        </label>
                        <textarea name="keluhan_utama" rows="4"
                                  class="w-full rounded-2xl border {{ $errors->has('keluhan_utama') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('keluhan_utama', $detail->keluhan_utama ?? $konsultasi->keluhan) }}</textarea>

                        @error('keluhan_utama')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Riwayat Hidup
                        </label>
                        <textarea name="riwayat_hidup" rows="4"
                                  class="w-full rounded-2xl border {{ $errors->has('riwayat_hidup') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('riwayat_hidup', $detail->riwayat_hidup ?? '') }}</textarea>

                        @error('riwayat_hidup')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Hasil Observasi
                        </label>
                        <textarea name="hasil_observasi" rows="4"
                                  class="w-full rounded-2xl border {{ $errors->has('hasil_observasi') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('hasil_observasi', $detail->hasil_observasi ?? '') }}</textarea>

                        @error('hasil_observasi')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Diagnosis Awal
                        </label>
                        <textarea name="diagnosis_awal" rows="4"
                                  class="w-full rounded-2xl border {{ $errors->has('diagnosis_awal') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('diagnosis_awal', $detail->diagnosis_awal ?? '') }}</textarea>

                        @error('diagnosis_awal')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Rencana Penanganan
                        </label>
                        <textarea name="rencana_penanganan" rows="4"
                                  class="w-full rounded-2xl border {{ $errors->has('rencana_penanganan') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('rencana_penanganan', $detail->rencana_penanganan ?? '') }}</textarea>

                        @error('rencana_penanganan')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-600">
                            Laporan Asesmen Psikologis
                        </label>
                        <textarea name="laporan_asesmen_psikologis" rows="4"
                                  class="w-full rounded-2xl border {{ $errors->has('laporan_asesmen_psikologis') ? 'border-red-400 bg-red-50' : 'border-slate-200' }} px-5 py-4 text-sm font-medium outline-none focus:border-[#155EEF] focus:ring-4 focus:ring-[#155EEF]/10">{{ old('laporan_asesmen_psikologis', $detail->laporan_asesmen_psikologis ?? '') }}</textarea>

                        @error('laporan_asesmen_psikologis')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl bg-[#F8FAFC] p-4 text-sm font-semibold text-slate-600">
                        <input type="checkbox"
                               name="perlu_rujukan"
                               value="1"
                               {{ old('perlu_rujukan', $detail->perlu_rujukan ?? false) ? 'checked' : '' }}>
                        Perlu rujukan ke psikiater
                    </label>

                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('psikolog.konsultasi.index') }}"
                       class="rounded-2xl bg-slate-100 px-6 py-4 text-center text-sm font-semibold text-slate-600">
                        Kembali
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-[#41AD01] px-7 py-4 text-sm font-semibold text-white transition hover:bg-[#329000]">
                        Simpan Hasil Konsultasi
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chatBox');
    const chatInput = document.getElementById('chatInput');
    const sendChatBtn = document.getElementById('sendChatBtn');

    const fetchUrl = "{{ route('psikolog.konsultasi.messages.fetch', $konsultasi->id_konsultasi) }}";
    const storeUrl = "{{ route('psikolog.konsultasi.messages.store', $konsultasi->id_konsultasi) }}";
    const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');
    const currentRole = "psikolog";

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    function renderMessages(messages) {
        if (!chatBox) {
            return;
        }

        chatBox.innerHTML = '';

        if (!messages.length) {
            chatBox.innerHTML = `
                <div class="rounded-2xl bg-white p-5 text-center text-sm font-semibold text-slate-400">
                    Belum ada pesan. Silakan mulai percakapan dengan pasien.
                </div>
            `;
            return;
        }

        messages.forEach(function (message) {
            const isMine = message.sender_role === currentRole;

            const bubble = document.createElement('div');
            bubble.className = isMine
                ? 'ml-auto max-w-[80%] rounded-2xl bg-[#0A2540] p-4 text-white shadow-sm'
                : 'max-w-[80%] rounded-2xl bg-white p-4 text-slate-700 shadow-sm';

            bubble.innerHTML = `
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xs font-semibold ${isMine ? 'text-white/60' : 'text-slate-400'}">${escapeHtml(message.sender_name)}</p>
                    <p class="text-[11px] font-semibold ${isMine ? 'text-white/60' : 'text-slate-400'}">${escapeHtml(message.time)}</p>
                </div>
                <p class="mt-2 text-sm font-medium leading-6">${escapeHtml(message.pesan)}</p>
            `;

            chatBox.appendChild(bubble);
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function fetchMessages() {
        if (!chatBox) {
            return;
        }

        fetch(fetchUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMessages(data.messages);
            }
        });
    }

    function sendMessage() {
        const pesan = chatInput.value.trim();

        if (!pesan) {
            Swal.fire({
                icon: 'warning',
                title: 'Pesan Kosong',
                text: 'Pesan tidak boleh kosong.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E'
            });
            return;
        }

        sendChatBtn.disabled = true;
        sendChatBtn.textContent = 'Mengirim...';

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                pesan: pesan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                fetchMessages();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message ?? 'Pesan gagal dikirim.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#01588E'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat mengirim pesan.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E'
            });
        })
        .finally(() => {
            sendChatBtn.disabled = false;
            sendChatBtn.textContent = 'Kirim';
        });
    }

    if (sendChatBtn && chatInput && chatBox) {
        sendChatBtn.addEventListener('click', sendMessage);

        chatInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });

        fetchMessages();
        setInterval(fetchMessages, 3000);

        if (window.Echo) {
            window.Echo
            .channel('konsultasi.{{ $konsultasi->id_konsultasi }}')
            .listen('.KonsultasiMessageSent', (e) => {
                fetchMessages();
            });
        }
    }
});
</script>

@endsection