<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();
        if (! $request->user() || $request->user()->role !== 'admin') $query->where('is_active', true);
        if ($search = trim((string) $request->query('search'))) {
            $query->where(fn ($q) => $q->where('question', 'like', "%{$search}%")
                ->orWhere('answer', 'like', "%{$search}%")
                ->orWhere('question_hinglish', 'like', "%{$search}%")
                ->orWhere('answer_hinglish', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%"));
        }
        if ($category = $request->query('category')) $query->where('category', $category);
        $language = $request->query('language');
        if ($language === 'hinglish') {
            $query->where('language', 'en')->whereNotNull('question_hinglish')->whereNotNull('answer_hinglish');
        } elseif ($language) {
            $query->where('language', $language);
        }

        $faqs = $query->orderBy('question')->paginate(30);
        if ($language === 'hinglish') {
            $faqs->getCollection()->transform(fn (Faq $faq) => $faq
                ->setAttribute('question', $faq->question_hinglish)
                ->setAttribute('answer', $faq->answer_hinglish)
                ->setAttribute('language', 'hinglish'));
        }
        return $faqs;
    }

    public function store(Request $request)
    {
        return response()->json(Faq::create($this->validated($request)), 201);
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));
        return $faq->fresh();
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->noContent();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:2000'],
            'answer' => ['required', 'string', 'max:10000'],
            'question_hinglish' => ['nullable', 'string', 'max:2000'],
            'answer_hinglish' => ['nullable', 'string', 'max:10000'],
            'category' => ['required', 'string', 'max:80'],
            'official_source_url' => ['nullable', 'url', 'max:255'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:100'],
            'language' => ['required', 'in:en,hi'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
