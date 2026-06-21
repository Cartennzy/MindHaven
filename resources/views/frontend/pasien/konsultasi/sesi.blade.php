@extends('frontend.layouts.app')

@section('title', 'Sesi Konsultasi - MindHaven')
@section('page_title', 'Sesi Konsultasi')
@section('page_subtitle', 'Ruang interaksi konsultasi antara pasien dan psikolog.')

@section('content')

@php
    $pasien = $konsultasi->pasien;
    $psikolog = $konsultasi->psikolog;
    $userPsikolog = $psikolog->user ?? null;

    $namaPasien = $pasien->nama_lengkap ?? auth()->user()->name ?? 'Pasien';
    $namaPsikolog = $psikolog->nama_lengkap ?? $userPsikolog->name ?? 'Psikolog';

    $metodeText = match ($konsultasi->metode_konsultasi) {
        'chat' => 'Chat',
        'video_call' => 'Video Call',
        'temu_janji' => 'Temu Janji',
        default => '-',
    };
@endphp

<div class="space-y-7">

    <div class="rounded-[34px] bg-white p-6 shadow-soft md:p-8">
        <h2 class="text-3xl font-black text-slate-800">
            Sesi Konsultasi
        </h2>
        <p class="mt-2 text-sm font-semibold text-slate-500">
            Anda dapat berinteraksi langsung dengan psikolog selama konsultasi berlangsung.
        </p>
    </div>
    
<div class="rounded-[34px] bg-white p-6 shadow-soft">

    <div class="rounded-3xl bg-[#EEF4FF] p-6">
        <h3 class="text-2xl font-black text-[#0A2540]">
            Ruang Chat Konsultasi
        </h3>

        <p class="mt-2 text-sm font-semibold text-slate-600">
            Pesan akan tersimpan dan diperbarui otomatis setiap 3 detik.
        </p>
    </div>

    <div class="mt-6 rounded-3xl border border-slate-100 bg-[#F8FAFC] p-5">

        <div id="chatBox"
             class="h-[70vh] space-y-4 overflow-y-auto pr-2">
        </div>

        @if($konsultasi->status === 'diproses')
            <div class="mt-6 flex gap-3">
                <input
                    type="text"
                    id="chatInput"
                    placeholder="Ketik pesan..."
                    class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-semibold outline-none focus:border-[#01588E] focus:ring-4 focus:ring-[#01588E]/10"
                >

                <button
                    type="button"
                    id="sendChatBtn"
                    class="rounded-2xl bg-[#41AD01] px-6 py-4 text-sm font-black text-white transition hover:bg-[#329000]"
                >
                    Kirim
                </button>
            </div>
        @else
            <div class="mt-6 rounded-2xl bg-slate-100 p-4 text-sm font-bold text-slate-500">
                Konsultasi sudah tidak dalam status diproses, sehingga pesan baru tidak dapat dikirim.
            </div>
        @endif

    </div>

    <div class="mt-6">
        <a href="{{ route('pasien.konsultasi.show', $konsultasi->id_konsultasi) }}"
           class="inline-flex items-center justify-center rounded-2xl bg-slate-100 px-6 py-4 text-sm font-black text-slate-700 hover:bg-slate-200 transition">
            Kembali ke Detail Konsultasi
        </a>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chatBox');
    const chatInput = document.getElementById('chatInput');
    const sendChatBtn = document.getElementById('sendChatBtn');

    const fetchUrl = "{{ route('pasien.konsultasi.messages.fetch', $konsultasi->id_konsultasi) }}";
    const storeUrl = "{{ route('pasien.konsultasi.messages.store', $konsultasi->id_konsultasi) }}";
    const csrfToken = "{{ csrf_token() }}";
    const currentRole = "pasien";

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    function renderMessages(messages) {
        chatBox.innerHTML = '';

        if (!messages.length) {
            chatBox.innerHTML = `
                <div class="rounded-2xl bg-white p-5 text-center text-sm font-bold text-slate-400">
                    Belum ada pesan. Silakan mulai percakapan dengan psikolog.
                </div>
            `;
            return;
        }

        messages.forEach(function (message) {
            const isMine = message.sender_role === currentRole;

            const bubble = document.createElement('div');
            bubble.className = isMine
                ? 'ml-auto max-w-[80%] rounded-2xl bg-[#01588E] p-4 text-white shadow-sm'
                : 'max-w-[80%] rounded-2xl bg-white p-4 text-slate-700 shadow-sm';

            bubble.innerHTML = `
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xs font-black ${isMine ? 'text-white/70' : 'text-slate-400'}">${escapeHtml(message.sender_name)}</p>
                    <p class="text-[11px] font-bold ${isMine ? 'text-white/60' : 'text-slate-400'}">${escapeHtml(message.time)}</p>
                </div>
                <p class="mt-2 text-sm font-semibold leading-6">${escapeHtml(message.pesan)}</p>
            `;

            chatBox.appendChild(bubble);
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function fetchMessages() {
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
                    confirmButtonColor: '#01588E'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat mengirim pesan.',
                confirmButtonColor: '#01588E'
            });
        })
        .finally(() => {
            sendChatBtn.disabled = false;
            sendChatBtn.textContent = 'Kirim';
        });
    }

    if (sendChatBtn && chatInput) {
        sendChatBtn.addEventListener('click', sendMessage);

        chatInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });
    }

    fetchMessages();
    setInterval(fetchMessages, 3000);

    if (window.Echo) {
        window.Echo
        .channel('konsultasi.{{ $konsultasi->id_konsultasi }}')
        .listen('.KonsultasiMessageSent', (e) => {
            fetchMessages();
        });
    }
});
</script>

@endsection