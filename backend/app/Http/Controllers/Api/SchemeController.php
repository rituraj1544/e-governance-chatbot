<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use Illuminate\Http\Request;

class SchemeController extends Controller
{
    private function filtered(Request $request)
    {
        $query = Scheme::query();
        if (! $request->user() || $request->user()->role !== 'admin') {
            $query->where('is_active', true);
        }
        if ($search = trim((string) $request->query('search'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('title_hinglish', 'like', "%{$search}%")
                ->orWhere('description_hinglish', 'like', "%{$search}%"));
        }
        if ($category = $request->query('category')) $query->where('category', $category);
        if ($language = $request->query('language')) {
            if ($language === 'hinglish') {
                $query->where('language', 'en')->whereNotNull('title_hinglish')->whereNotNull('description_hinglish');
            } else {
                $query->where('language', $language);
            }
        }
        return $query->orderBy('title');
    }

    public function index(Request $request)
    {
        $schemes = $this->filtered($request)->paginate(12);
        if ($request->query('language') === 'hinglish') {
            $schemes->getCollection()->transform(fn (Scheme $scheme) => $this->presentHinglish($scheme));
        }
        return $schemes;
    }

    public function show(Request $request, Scheme $scheme)
    {
        abort_if(! $scheme->is_active && $request->user()?->role !== 'admin', 404);
        return $request->query('language') === 'hinglish' ? $this->presentHinglish($scheme) : $scheme;
    }

    public function store(Request $request)
    {
        $data = $this->validateScheme($request);
        return response()->json(Scheme::create($data), 201);
    }

    public function update(Request $request, Scheme $scheme)
    {
        $scheme->update($this->validateScheme($request));
        return $scheme->fresh();
    }

    public function destroy(Scheme $scheme)
    {
        $scheme->delete();
        return response()->noContent();
    }

    private function validateScheme(Request $request): array
    {
        return $request->validate([
            'translation_key' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:10000'],
            'title_hinglish' => ['nullable', 'string', 'max:200'],
            'description_hinglish' => ['nullable', 'string', 'max:10000'],
            'eligibility' => ['nullable', 'string', 'max:5000'],
            'eligibility_hinglish' => ['nullable', 'string', 'max:5000'],
            'required_documents' => ['nullable', 'array'],
            'required_documents.*' => ['string', 'max:200'],
            'required_documents_hinglish' => ['nullable', 'array'],
            'required_documents_hinglish.*' => ['string', 'max:200'],
            'application_process' => ['nullable', 'string', 'max:5000'],
            'application_process_hinglish' => ['nullable', 'string', 'max:5000'],
            'official_source_url' => ['nullable', 'url', 'max:2048'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:100'],
            'language' => ['required', 'in:en,hi'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function presentHinglish(Scheme $scheme): Scheme
    {
        return $scheme
            ->setAttribute('title', $scheme->title_hinglish)
            ->setAttribute('description', $scheme->description_hinglish)
            ->setAttribute('eligibility', $scheme->eligibility_hinglish)
            ->setAttribute('required_documents', $scheme->required_documents_hinglish)
            ->setAttribute('application_process', $scheme->application_process_hinglish)
            ->setAttribute('language', 'hinglish');
    }
}
