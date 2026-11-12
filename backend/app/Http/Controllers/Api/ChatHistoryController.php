<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use Illuminate\Http\Request;

class ChatHistoryController extends Controller
{
    public function adminIndex(Request $request)
    {
        $query = ChatHistory::with('user:id,name,email')->latest();
        if ($search = trim((string) $request->query('search'))) {
            $query->where(fn ($q) => $q->where('query', 'like', "%{$search}%")
                ->orWhere('intent', 'like', "%{$search}%"));
        }
        return $query->paginate(30);
    }

    public function index(Request $request)
    {
        return ChatHistory::with([
            'scheme:id,title,title_hinglish,official_source_url',
            'faq:id,question,question_hinglish,official_source_url',
        ])->where('user_id', $request->user()->id)->latest()->paginate(25);
    }

    public function destroy(Request $request, ChatHistory $chatHistory)
    {
        abort_unless($chatHistory->user_id === $request->user()->id, 404);
        $chatHistory->delete();
        return response()->noContent();
    }
}
