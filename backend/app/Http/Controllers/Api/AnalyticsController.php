<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use App\Models\Faq;
use App\Models\Scheme;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $daily = ChatHistory::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')->pluck('total', 'day');

        $dailyQueries = collect(CarbonPeriod::create(now()->subDays(6)->startOfDay(), now()->startOfDay()))
            ->map(fn ($date) => ['date' => $date->toDateString(), 'count' => (int) ($daily[$date->toDateString()] ?? 0)])
            ->values();

        $popularSchemes = ChatHistory::query()
            ->join('schemes', 'chat_histories.matched_scheme_id', '=', 'schemes.id')
            ->select('schemes.id', 'schemes.title', DB::raw('COUNT(*) as total'))
            ->groupBy('schemes.id', 'schemes.title')->orderByDesc('total')->limit(5)->get();

        $popularFaqs = ChatHistory::query()
            ->join('faqs', 'chat_histories.matched_faq_id', '=', 'faqs.id')
            ->select('faqs.id', 'faqs.question', DB::raw('COUNT(*) as total'))
            ->groupBy('faqs.id', 'faqs.question')->orderByDesc('total')->limit(5)->get();

        $recent = ChatHistory::with('user:id,name,email')->latest()->limit(8)->get();

        return response()->json([
            'stats' => [
                'citizens' => User::where('role', 'citizen')->count(),
                'schemes' => Scheme::count(),
                'faqs' => Faq::count(),
                'queries' => ChatHistory::count(),
                'queries_today' => ChatHistory::whereDate('created_at', $today)->count(),
            ],
            'daily_queries' => $dailyQueries,
            'popular_schemes' => $popularSchemes,
            'popular_faqs' => $popularFaqs,
            'intents' => ChatHistory::select('intent', DB::raw('COUNT(*) as total'))
                ->whereNotNull('intent')->groupBy('intent')->orderByDesc('total')->get(),
            'languages' => ChatHistory::select('language', DB::raw('COUNT(*) as total'))
                ->groupBy('language')->orderBy('language')->get(),
            'recent' => $recent,
        ]);
    }
}
