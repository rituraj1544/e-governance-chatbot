<?php

namespace App\Services;

class RuleBasedNlpService
{
    /**
     * These rules handle the catalogue's supported topics in English, Hindi,
     * and common Romanized Hindi. The ML classifier remains the fallback.
     */
    private array $terms = [
        'aadhaar' => ['aadhaar', 'aadhar', 'uidai', 'आधार', 'adhar card', 'aadhaar card', 'aadhaar update'],
        'pan_card' => ['pan card', 'pan-card', 'permanent account number', 'pan 2.0', 'पैन', 'pan banwana', 'pan apply'],
        'ration_card' => ['ration card', 'ration', 'rashan card', 'rashan', 'nfsa', 'राशन', 'ration card banwana', 'rashan card banwana'],
        'pm_kisan' => ['pm kisan', 'pm-kisan', 'pmkisan', 'pm kisaan', 'kisan samman nidhi', 'pradhan mantri kisan', 'पीएम किसान', 'पीएम-किसान', 'किसान सम्मान निधि'],
        'scholarship' => ['scholarship', 'scholarships', 'national scholarship portal', 'nsp scholarship', 'chhatravritti', 'chhatravratti', 'छात्रवृत्ति', 'स्कॉलरशिप'],
        'general_scheme' => [
            'passport seva', 'passport', 'पासपोर्ट',
            'voter registration', 'voter id', 'voter card', 'electoral roll', 'matdata', 'मतदाता', 'वोटर आईडी',
            'digilocker', 'digi locker', 'digital locker', 'डिजिलॉकर',
            'umang', 'उमंग',
            'government service', 'government services', 'govt service', 'govt services', 'national portal', 'india.gov.in',
            'सरकारी सेवा', 'सरकारी सेवाएँ', 'सरकारी सेवाएं', 'yojana', 'scheme', 'सरकारी योजना', 'योजना',
        ],
    ];

    public function detect(string $query): ?string
    {
        $normalized = mb_strtolower(trim(preg_replace('/\s+/u', ' ', $query) ?? $query));
        if (preg_match('/(?<![\pL\pN])pan(?![\pL\pN])/u', $normalized) === 1) return 'pan_card';

        foreach ($this->terms as $intent => $terms) {
            foreach ($terms as $term) {
                if (mb_strpos($normalized, mb_strtolower($term)) !== false) return $intent;
            }
        }

        return null;
    }

    public function language(string $query): ?string
    {
        $hasHindiScript = preg_match('/[\x{0900}-\x{097F}]/u', $query) === 1;
        $words = preg_split('/[^\pL\pN]+/u', mb_strtolower($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $hinglishMarkers = [
            'aap', 'apna', 'apne', 'bata', 'batao', 'bataiye', 'btao', 'chahiye', 'ka', 'kab', 'kaise', 'kahan', 'kaha',
            'kar', 'kare', 'karen', 'karna', 'karne', 'karu', 'karun', 'karo', 'karwa', 'karwana', 'ke', 'kijiye', 'kis',
            'kiska', 'kitna', 'kitne', 'kitni', 'ko', 'kyon', 'kyun', 'kya', 'liye', 'mai', 'mein', 'mera',
            'meri', 'mere', 'mujhe', 'par', 'se', 'tak', 'wala', 'wali', 'wale', 'hai', 'hain', 'h',
            'hoga', 'hogi', 'hon', 'milega', 'milegi', 'dikhao', 'k', 'ki', 'kr', 'kru', 'krna', 'krun',
            'aadhar', 'rashan', 'kisaan', 'sarkari', 'yojana', 'seva', 'matdata', 'chhatravritti',
            'chhatravratti', 'banwana', 'banwaun', 'banwao',
        ];
        $englishMarkers = [
            'can', 'could', 'do', 'does', 'explain', 'find', 'how', 'is', 'need', 'please', 'tell', 'what',
            'which', 'where', 'who', 'why', 'when', 'would', 'should', 'show', 'check', 'apply', 'status',
            'update', 'document', 'documents', 'form', 'forms', 'application', 'eligibility', 'eligible',
            'online', 'website', 'process', 'details', 'benefit', 'benefits', 'registration', 'list',
        ];

        $hasHinglishMarker = array_intersect($words, $hinglishMarkers) !== [];
        $hasEnglishMarker = array_intersect($words, $englishMarkers) !== [];

        if ($hasHinglishMarker || ($hasHindiScript && $hasEnglishMarker)) return 'hinglish';
        if ($hasHindiScript) return 'hi';
        if ($hasEnglishMarker) return 'en';

        return count($words) > 1 ? 'en' : null;
    }
}
