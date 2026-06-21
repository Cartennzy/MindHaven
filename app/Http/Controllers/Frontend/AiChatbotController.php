<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
use App\Models\AiChatSession;
use App\Services\LLMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AiChatbotController extends Controller
{
    public function index(Request $request)
    {
        $browserSessionId = Session::getId();

        $sessions = $this->baseSessionQuery($browserSessionId)
            ->latest('updated_at')
            ->get();

        $activeSession = null;

        if ($request->filled('session')) {
            $activeSession = $this->baseSessionQuery($browserSessionId)
                ->where('id_ai_chat_session', $request->session)
                ->first();
        }

        if (!$activeSession && $sessions->count() > 0) {
            $activeSession = $sessions->first();
        }

        $chats = collect();

        if ($activeSession) {
            $chats = AiChat::where('id_ai_chat_session', $activeSession->id_ai_chat_session)
                ->orderBy('id_ai_chat', 'asc')
                ->get();
        }

        return view('frontend.chatbot.index', compact('sessions', 'activeSession', 'chats'));
    }

    public function newChat()
    {
        $session = AiChatSession::create([
            'user_id' => Auth::id(),
            'session_id' => Session::getId(),
            'judul' => 'Chat baru',
        ]);

        return redirect()->route('chatbot.index', [
            'session' => $session->id_ai_chat_session,
        ]);
    }

    public function send(Request $request, LLMService $llmService)
    {
        $request->validate([
            'pertanyaan' => ['required', 'string', 'min:3', 'max:1000'],
            'id_ai_chat_session' => ['nullable', 'integer'],
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.min' => 'Pertanyaan minimal 3 karakter.',
            'pertanyaan.max' => 'Pertanyaan maksimal 1000 karakter.',
        ]);

        $browserSessionId = Session::getId();

        $chatSession = null;
        $isNewSession = false;

        if ($request->filled('id_ai_chat_session')) {
            $chatSession = $this->baseSessionQuery($browserSessionId)
                ->where('id_ai_chat_session', $request->id_ai_chat_session)
                ->first();
        }

        if (!$chatSession) {
            $chatSession = AiChatSession::create([
                'user_id' => Auth::id(),
                'session_id' => $browserSessionId,
                'judul' => $this->makeTitle($request->pertanyaan),
            ]);

            $isNewSession = true;
        }

        if ($chatSession->judul === 'Chat baru') {
            $chatSession->update([
                'judul' => $this->makeTitle($request->pertanyaan),
            ]);

            $isNewSession = true;
        }

        $history = AiChat::where('id_ai_chat_session', $chatSession->id_ai_chat_session)
            ->orderBy('id_ai_chat', 'asc')
            ->limit(12)
            ->get(['pertanyaan', 'jawaban'])
            ->toArray();

        $result = $llmService->ask($request->pertanyaan, $history);

        $chat = AiChat::create([
            'id_ai_chat_session' => $chatSession->id_ai_chat_session,
            'user_id' => Auth::id(),
            'session_id' => $browserSessionId,
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $result['answer'],
            'provider' => $result['provider'],
            'model' => $result['model'],
        ]);

        $chatSession->touch();
        $chatSession->refresh();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'is_new_session' => $isNewSession,
                'session_id' => $chatSession->id_ai_chat_session,
                'title' => $chatSession->judul,
                'question' => $chat->pertanyaan,
                'answer' => $chat->jawaban,
                'time' => $chatSession->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i'),
                'session_url' => route('chatbot.index', ['session' => $chatSession->id_ai_chat_session]),
                'delete_url' => route('chatbot.session.delete', $chatSession->id_ai_chat_session),
            ]);
        }

        return redirect()->route('chatbot.index', [
            'session' => $chatSession->id_ai_chat_session,
        ]);
    }

    public function clearSession(AiChatSession $session)
    {
        $browserSessionId = Session::getId();

        $owned = $this->baseSessionQuery($browserSessionId)
            ->where('id_ai_chat_session', $session->id_ai_chat_session)
            ->exists();

        abort_if(!$owned, 403);

        $session->delete();

        return redirect()->route('chatbot.index');
    }

    private function baseSessionQuery(string $browserSessionId)
    {
        return AiChatSession::query()
            ->where(function ($query) use ($browserSessionId) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', $browserSessionId);
                }
            });
    }

    private function makeTitle(string $question): string
    {
        return Str::limit(trim($question), 45, '...');
    }
}