@extends('frontend.layouts.guest')

@section('title', 'AI Chatbot - MindHaven')

@section('content')

@php
    $activeSessionId = $activeSession->id_ai_chat_session ?? '';
@endphp

<div class="min-h-screen bg-[#F4FBFF] text-[#172033]">
    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-[310px] -translate-x-full border-r border-[#D7EDF4] bg-white transition duration-300 lg:static lg:translate-x-0">
            <div class="flex h-full flex-col">

                <div class="border-b border-[#D7EDF4] p-5">
                    <a href="{{ route('home') }}" class="mb-4 inline-flex text-sm font-semibold text-[#159AC8]">
                        ← Beranda
                    </a>

                    <form action="{{ route('chatbot.new') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl bg-[#28AEDA] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#159AC8]">
                            + Chat Baru
                        </button>
                    </form>
                </div>

                <div class="flex-1 overflow-y-auto p-3">
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-[#8AA0AE]">
                        Riwayat Chat
                    </p>

                    <div class="space-y-2" id="historyList">
                        @forelse($sessions as $session)
                            <div
                                id="history-item-{{ $session->id_ai_chat_session }}"
                                class="history-item group flex items-center gap-2 rounded-2xl {{ $activeSession && $activeSession->id_ai_chat_session == $session->id_ai_chat_session ? 'bg-[#EAF8FD]' : 'hover:bg-[#F3FAFD]' }}"
                            >
                                <a href="{{ route('chatbot.index', ['session' => $session->id_ai_chat_session]) }}"
                                   class="min-w-0 flex-1 px-4 py-3 text-sm font-semibold text-[#425466]">
                                    <span class="history-title line-clamp-1">{{ $session->judul }}</span>
                                    <span class="history-time mt-1 block text-xs font-medium text-[#8AA0AE]">
                                        {{ $session->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                    </span>
                                </a>

                                <form action="{{ route('chatbot.session.delete', $session->id_ai_chat_session) }}" method="POST" class="pr-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hidden rounded-xl px-2 py-1 text-xs font-bold text-red-500 hover:bg-red-50 group-hover:block">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div id="emptyHistory" class="rounded-2xl bg-[#F7FCFF] p-4 text-sm leading-7 text-[#6B7D87]">
                                Belum ada riwayat chat.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-[#D7EDF4] p-5">
                    <p class="text-xs leading-6 text-[#7A8B96]">
                        AI Chatbot ini hanya memberikan informasi awal dan tidak menggantikan psikolog atau psikiater.
                    </p>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="flex min-h-screen flex-1 flex-col">

            <header class="sticky top-0 z-30 border-b border-[#D7EDF4] bg-[#F4FBFF]/90 px-4 py-4 backdrop-blur-xl md:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button type="button" id="toggleSidebar" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-[#159AC8] shadow-sm lg:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                            </svg>
                        </button>

                        <div>
                            <h1 class="text-xl font-semibold tracking-tight md:text-2xl">
                                AI Chatbot MindHaven
                            </h1>
                            <p id="activeChatTitle" class="mt-1 text-xs font-medium text-[#6B7D87] md:text-sm">
                                {{ $activeSession ? $activeSession->judul : 'Chat baru' }}
                            </p>
                        </div>
                    </div>

                    <div class="hidden rounded-full bg-white px-4 py-2 text-xs font-semibold text-[#159AC8] shadow-sm md:block">
                        AI Assistant
                    </div>
                </div>
            </header>

            <section id="chatBox" class="flex-1 overflow-y-auto px-4 py-6 md:px-8">
                <div class="mx-auto max-w-4xl space-y-6" id="messages">

                    @if($chats->count() == 0)
                        <div id="emptyState" class="flex min-h-[65vh] items-center justify-center">
                            <div class="max-w-2xl text-center">
                                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[2rem] bg-[#EAF8FD] text-[#159AC8]">
                                    <svg class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a8.5 8.5 0 0 1-12.7 7.4L4 20l.8-3.9A8.5 8.5 0 1 1 21 12Z"/>
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-3xl font-semibold tracking-tight text-[#172033] md:text-4xl">
                                    Apa yang bisa MindHaven bantu?
                                </h2>

                                <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-[#6B7D87]">
                                    Tanyakan seputar stres, kecemasan, sulit tidur, emosi, relaksasi, atau arahan awal sebelum konsultasi.
                                </p>

                                <div class="mt-8 grid gap-3 md:grid-cols-2">
                                    <button type="button" class="quickAsk rounded-2xl border border-[#D7EDF4] bg-white p-4 text-left text-sm font-semibold text-[#425466] transition hover:border-[#28AEDA] hover:bg-[#F7FCFF]">
                                        Apa saja pemicu gangguan kesehatan mental?
                                    </button>
                                    <button type="button" class="quickAsk rounded-2xl border border-[#D7EDF4] bg-white p-4 text-left text-sm font-semibold text-[#425466] transition hover:border-[#28AEDA] hover:bg-[#F7FCFF]">
                                        Saya sering cemas sebelum tidur, harus bagaimana?
                                    </button>
                                    <button type="button" class="quickAsk rounded-2xl border border-[#D7EDF4] bg-white p-4 text-left text-sm font-semibold text-[#425466] transition hover:border-[#28AEDA] hover:bg-[#F7FCFF]">
                                        Bagaimana cara menenangkan diri saat panik?
                                    </button>
                                    <button type="button" class="quickAsk rounded-2xl border border-[#D7EDF4] bg-white p-4 text-left text-sm font-semibold text-[#425466] transition hover:border-[#28AEDA] hover:bg-[#F7FCFF]">
                                        Kapan harus konsultasi ke psikolog?
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach($chats as $chat)
                        <div class="flex justify-end">
                            <div class="max-w-[85%] rounded-[1.4rem] rounded-br-sm bg-[#28AEDA] px-5 py-4 text-white shadow-sm">
                                <p class="whitespace-pre-line text-sm leading-7">{{ $chat->pertanyaan }}</p>
                            </div>
                        </div>

                        <div class="flex justify-start">
                            <div class="max-w-[85%] rounded-[1.4rem] rounded-bl-sm bg-white px-5 py-4 text-[#425466] shadow-sm ring-1 ring-[#D7EDF4]">
                                <div class="mb-2 text-xs font-semibold text-[#159AC8]">MindHaven AI</div>
                                <p class="whitespace-pre-line text-sm leading-7">{{ $chat->jawaban }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </section>

            <footer class="sticky bottom-0 border-t border-[#D7EDF4] bg-[#F4FBFF]/95 px-4 py-4 backdrop-blur-xl md:px-8">
                <form id="chatForm" action="{{ route('chatbot.send') }}" method="POST" class="mx-auto flex max-w-4xl items-end gap-3">
                    @csrf

                    <input type="hidden" name="id_ai_chat_session" id="activeSessionInput" value="{{ $activeSessionId }}">

                    <div class="flex-1 rounded-[1.6rem] border border-[#D7EDF4] bg-white px-4 py-3 shadow-[0_14px_35px_rgba(14,116,144,.08)] focus-within:border-[#28AEDA] focus-within:ring-4 focus-within:ring-[#28AEDA]/10">
                        <textarea
                            id="questionInput"
                            name="pertanyaan"
                            rows="1"
                            required
                            placeholder="Tulis pertanyaan kamu di sini..."
                            class="max-h-40 w-full resize-none bg-transparent text-sm text-[#172033] outline-none placeholder:text-[#9AACB8]"></textarea>
                    </div>

                    <button id="sendButton" type="submit" class="flex min-h-[52px] items-center justify-center rounded-[1.3rem] bg-[#28AEDA] px-6 text-sm font-semibold text-white shadow-[0_14px_32px_rgba(40,174,218,.24)] transition hover:bg-[#159AC8] disabled:cursor-not-allowed disabled:opacity-60">
                        Kirim
                    </button>
                </form>

                <p class="mx-auto mt-3 max-w-4xl text-xs leading-6 text-[#7A8B96]">
                    AI dapat membantu memberikan arahan awal, tetapi bukan pengganti diagnosis atau konsultasi profesional.
                </p>
            </footer>

        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const chatBox = document.getElementById('chatBox');
    const messages = document.getElementById('messages');
    const chatForm = document.getElementById('chatForm');
    const questionInput = document.getElementById('questionInput');
    const sendButton = document.getElementById('sendButton');
    const activeSessionInput = document.getElementById('activeSessionInput');
    const activeChatTitle = document.getElementById('activeChatTitle');
    const historyList = document.getElementById('historyList');

    function scrollToBottom() {
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function removeEmptyState() {
        const emptyState = document.getElementById('emptyState');
        if (emptyState) {
            emptyState.remove();
        }
    }

    function appendUserMessage(text) {
        removeEmptyState();

        messages.insertAdjacentHTML('beforeend', `
            <div class="flex justify-end">
                <div class="max-w-[85%] rounded-[1.4rem] rounded-br-sm bg-[#28AEDA] px-5 py-4 text-white shadow-sm">
                    <p class="whitespace-pre-line text-sm leading-7">${escapeHtml(text)}</p>
                </div>
            </div>
        `);

        scrollToBottom();
    }

    function appendLoadingMessage() {
        messages.insertAdjacentHTML('beforeend', `
            <div id="loadingMessage" class="flex justify-start">
                <div class="max-w-[85%] rounded-[1.4rem] rounded-bl-sm bg-white px-5 py-4 text-[#425466] shadow-sm ring-1 ring-[#D7EDF4]">
                    <div class="mb-2 text-xs font-semibold text-[#159AC8]">MindHaven AI</div>
                    <div class="flex items-center gap-2 text-sm text-[#6B7D87]">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-[#28AEDA]"></span>
                        <span class="h-2 w-2 animate-pulse rounded-full bg-[#28AEDA]" style="animation-delay:.15s"></span>
                        <span class="h-2 w-2 animate-pulse rounded-full bg-[#28AEDA]" style="animation-delay:.3s"></span>
                        <span class="ml-2">Sedang mengetik...</span>
                    </div>
                </div>
            </div>
        `);

        scrollToBottom();
    }

    function replaceLoadingWithAnswer(answer) {
        const loadingMessage = document.getElementById('loadingMessage');

        if (loadingMessage) {
            loadingMessage.outerHTML = `
                <div class="flex justify-start">
                    <div class="max-w-[85%] rounded-[1.4rem] rounded-bl-sm bg-white px-5 py-4 text-[#425466] shadow-sm ring-1 ring-[#D7EDF4]">
                        <div class="mb-2 text-xs font-semibold text-[#159AC8]">MindHaven AI</div>
                        <p class="whitespace-pre-line text-sm leading-7">${escapeHtml(answer)}</p>
                    </div>
                </div>
            `;
        }

        scrollToBottom();
    }

    function autoResizeTextarea() {
        if (!questionInput) return;

        questionInput.style.height = 'auto';
        questionInput.style.height = questionInput.scrollHeight + 'px';
    }

    function setActiveHistory(sessionId) {
        document.querySelectorAll('.history-item').forEach(function (item) {
            item.classList.remove('bg-[#EAF8FD]');
            item.classList.add('hover:bg-[#F3FAFD]');
        });

        const activeItem = document.getElementById('history-item-' + sessionId);

        if (activeItem) {
            activeItem.classList.add('bg-[#EAF8FD]');
            activeItem.classList.remove('hover:bg-[#F3FAFD]');
        }
    }

    function updateHistory(data) {
        if (!historyList || !data.session_id) return;

        const emptyHistory = document.getElementById('emptyHistory');

        if (emptyHistory) {
            emptyHistory.remove();
        }

        let historyItem = document.getElementById('history-item-' + data.session_id);
        const title = data.title || 'Chat baru';
        const time = data.time || new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }).format(new Date()).replace(',', '');

        if (historyItem) {
            const titleElement = historyItem.querySelector('.history-title');
            const timeElement = historyItem.querySelector('.history-time');

            if (titleElement) titleElement.textContent = title;
            if (timeElement) timeElement.textContent = time;

            historyList.prepend(historyItem);
        } else {
            const baseUrl = "{{ route('chatbot.index') }}";
            const deleteUrlTemplate = "{{ route('chatbot.session.delete', ['session' => '__SESSION_ID__']) }}";
            const deleteUrl = deleteUrlTemplate.replace('__SESSION_ID__', data.session_id);

            historyList.insertAdjacentHTML('afterbegin', `
                <div id="history-item-${data.session_id}" class="history-item group flex items-center gap-2 rounded-2xl bg-[#EAF8FD]">
                    <a href="${baseUrl}?session=${data.session_id}" class="min-w-0 flex-1 px-4 py-3 text-sm font-semibold text-[#425466]">
                        <span class="history-title line-clamp-1">${escapeHtml(title)}</span>
                        <span class="history-time mt-1 block text-xs font-medium text-[#8AA0AE]">${escapeHtml(time)}</span>
                    </a>

                    <form action="${deleteUrl}" method="POST" class="pr-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="hidden rounded-xl px-2 py-1 text-xs font-bold text-red-500 hover:bg-red-50 group-hover:block">
                            Hapus
                        </button>
                    </form>
                </div>
            `);
        }

        setActiveHistory(data.session_id);
    }

    if (toggleSidebar && sidebar) {
        toggleSidebar.addEventListener('click', function () {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    if (questionInput) {
        questionInput.addEventListener('input', autoResizeTextarea);

        questionInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                chatForm.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }

    document.querySelectorAll('.quickAsk').forEach(function (button) {
        button.addEventListener('click', function () {
            questionInput.value = button.textContent.trim();
            autoResizeTextarea();
            chatForm.dispatchEvent(new Event('submit', { cancelable: true }));
        });
    });

    if (chatForm) {
        chatForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const question = questionInput.value.trim();

            if (!question) {
                return;
            }

            appendUserMessage(question);
            appendLoadingMessage();

            questionInput.value = '';
            autoResizeTextarea();

            sendButton.disabled = true;
            sendButton.textContent = 'Mengirim...';

            try {
                const formData = new FormData(chatForm);
                formData.set('pertanyaan', question);

                const response = await fetch(chatForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    replaceLoadingWithAnswer('Maaf, terjadi kesalahan saat memproses pertanyaan.');
                    return;
                }

                activeSessionInput.value = data.session_id;

                if (activeChatTitle) {
                    activeChatTitle.textContent = data.title || 'Chat baru';
                }

                replaceLoadingWithAnswer(data.answer);
                updateHistory(data);

                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('session', data.session_id);
                window.history.replaceState({}, '', currentUrl.toString());

            } catch (error) {
                replaceLoadingWithAnswer('Maaf, koneksi bermasalah. Silakan coba lagi.');
            } finally {
                sendButton.disabled = false;
                sendButton.textContent = 'Kirim';
                questionInput.focus();
            }
        });
    }

    scrollToBottom();
</script>

@endsection