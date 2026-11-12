<?php

namespace App\Services;

use App\Models\Faq;
use App\Models\Scheme;

class ChatbotService
{
    public function __construct(private RuleBasedNlpService $rules, private MlIntentClassifier $ml) {}

    public function answer(string $query, ?string $language = null): array
    {
        $language = $this->rules->language($query)
            ?? (in_array($language, ['en', 'hi', 'hinglish'], true) ? $language : 'en');

        $intent = $this->rules->detect($query);
        $confidence = null;
        if (! $intent) {
            $prediction = $this->ml->classify($query);
            if ($prediction && $prediction['is_confident'] && $prediction['intent'] !== 'unknown') {
                $intent = $prediction['intent'];
                $confidence = (float) $prediction['confidence'];
            }
        }

        $faq = $this->findFaq($query, $language, $intent ?? 'about');
        if ($faq) {
            return [
                'reply' => $language === 'hinglish' ? $faq->answer_hinglish : $faq->answer,
                'intent' => $intent ?? 'faq',
                'language' => $language === 'hinglish' ? 'hinglish' : $faq->language,
                'confidence' => $confidence,
                'source' => [
                    'type' => 'faq', 'id' => $faq->id,
                    'title' => $language === 'hinglish' ? $faq->question_hinglish : $faq->question,
                    'url' => $faq->official_source_url,
                ],
                'matched_faq_id' => $faq->id,
                'matched_scheme_id' => null,
                'suggestions' => [],
            ];
        }

        $scheme = $intent ? $this->findScheme($query, $intent, $language) : null;
        if ($scheme) {
            $isHinglish = $language === 'hinglish';
            $reply = $isHinglish ? $scheme->description_hinglish : $scheme->description;
            $eligibility = $isHinglish ? $scheme->eligibility_hinglish : $scheme->eligibility;
            $documents = $isHinglish ? $scheme->required_documents_hinglish : $scheme->required_documents;
            $applicationProcess = $isHinglish ? $scheme->application_process_hinglish : $scheme->application_process;
            if ($eligibility) $reply .= $isHinglish ? "\n\nEligibility: {$eligibility}" : "\n\nEligibility information: {$eligibility}";
            if ($documents) $reply .= $isHinglish ? "\n\nListed documents: ".implode(', ', $documents) : "\n\nDocuments listed: ".implode(', ', $documents);
            if ($applicationProcess) $reply .= $isHinglish ? "\n\nApplication process: {$applicationProcess}" : "\n\nApplication information: {$applicationProcess}";
            $reply .= $scheme->official_source_url
                ? ($isHinglish
                    ? "\n\nAction lene se pehle current details official source par confirm karein."
                    : "\n\nPlease confirm current details with the official source before taking action.")
                : ($isHinglish
                    ? "\n\nIs catalogue entry ke saath official source link nahi hai. Action lene se pehle responsible department se details verify karein."
                    : "\n\nNo official source is linked to this catalogue entry. Verify details directly with the responsible department before taking action.");

            return [
                'reply' => $reply,
                'intent' => $intent,
                'language' => $isHinglish ? 'hinglish' : $scheme->language,
                'confidence' => $confidence,
                'source' => [
                    'type' => 'scheme', 'id' => $scheme->id,
                    'title' => $isHinglish ? $scheme->title_hinglish : $scheme->title,
                    'url' => $scheme->official_source_url,
                ],
                'matched_faq_id' => null,
                'matched_scheme_id' => $scheme->id,
                'suggestions' => [],
            ];
        }

        $reply = match ($language) {
            'hi' => 'माफ़ कीजिए, इस प्रश्न के लिए सत्यापित जानकारी अभी उपलब्ध नहीं है। कृपया सेवा का नाम बताएं या संबंधित सरकारी पोर्टल देखें।',
            'hinglish' => 'Sorry, is question ke liye verified information abhi nahi mili. Service ka naam batao ya relevant official government portal check karo.',
            default => 'I could not find verified information for that question yet. Try naming a service, or check the relevant official government portal.',
        };

        return [
            'reply' => $reply,
            'intent' => $intent ?? 'unknown',
            'language' => $language,
            'confidence' => $confidence,
            'source' => null,
            'matched_faq_id' => null,
            'matched_scheme_id' => null,
            'suggestions' => ['aadhaar', 'pan_card', 'ration_card', 'pm_kisan', 'scholarship', 'general_scheme'],
        ];
    }

    private function findFaq(string $query, string $language, string $category): ?Faq
    {
        $normalized = mb_strtolower($query);
        $faqs = Faq::where('is_active', true)->where('category', $category);
        if ($language === 'hinglish') {
            $faqs->whereIn('language', ['en', 'hi'])
                ->whereNotNull('question_hinglish')->whereNotNull('answer_hinglish')
                ->orderByRaw("CASE WHEN language = 'en' THEN 0 ELSE 1 END");
        } else {
            $faqs->whereIn('language', [$language, 'en'])
                ->orderByRaw('CASE WHEN language = ? THEN 0 ELSE 1 END', [$language]);
        }
        $faqs = $faqs->get();
        foreach ($faqs as $faq) {
            $keywords = $faq->keywords ?? [];
            if ($language === 'hinglish' && $faq->question_hinglish) {
                $keywords[] = rtrim($faq->question_hinglish, '?!.,:; ');
            }
            foreach ($keywords as $keyword) {
                if (mb_strlen($keyword) > 2 && mb_strpos($normalized, mb_strtolower($keyword)) !== false) return $faq;
            }
        }
        return null;
    }

    private function findScheme(string $query, string $intent, string $language): ?Scheme
    {
        $schemes = Scheme::where('is_active', true)->where('category', $intent);
        if ($language === 'hinglish') {
            $schemes->whereIn('language', ['en', 'hi'])->whereNotNull('description_hinglish')
                ->orderByRaw("CASE WHEN language = 'en' THEN 0 ELSE 1 END");
        } else {
            $schemes->whereIn('language', [$language, 'en'])
                ->orderByRaw('CASE WHEN language = ? THEN 0 ELSE 1 END', [$language]);
        }
        $schemes = $schemes->get();
        $normalized = mb_strtolower($query);
        foreach ($schemes as $scheme) {
            if (mb_strpos($normalized, mb_strtolower($scheme->title)) !== false) return $scheme;
            foreach ($scheme->keywords ?? [] as $keyword) {
                if (mb_strlen($keyword) > 2 && mb_strpos($normalized, mb_strtolower($keyword)) !== false) return $scheme;
            }
        }
        return $schemes->first();
    }
}
