<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function query(Request $request, ChatbotService $chatbot)
    {
        $data = $request->validate([
            'query' => ['required', 'string', 'min:2', 'max:2000'],
            'language' => ['nullable', 'in:en,hi,hinglish'],
        ]);
        $answer = $chatbot->answer($data['query'], $data['language'] ?? null);
        $sessionId = $request->hasSession(true) ? $request->session()->getId() : null;
        $savedUser = $request->user()?->status === 'active' ? $request->user() : null;
        $history = ChatHistory::create([
            'user_id' => $savedUser?->id,
            'matched_scheme_id' => $answer['matched_scheme_id'],
            'matched_faq_id' => $answer['matched_faq_id'],
            'session_id' => $sessionId,
            'query' => $data['query'],
            'response' => $answer['reply'],
            'intent' => $answer['intent'],
            'language' => $answer['language'],
            'confidence_score' => $answer['confidence'],
        ]);

        return response()->json(['answer' => $answer, 'history_id' => $savedUser ? $history->id : null]);
    }
}
