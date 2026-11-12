<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class MlIntentClassifier
{
    public function classify(string $query): ?array
    {
        try {
            $response = Http::acceptJson()
                ->timeout((float) config('services.ml.timeout', 2.5))
                ->post(rtrim(config('services.ml.url'), '/').'/predict', ['query' => $query]);

            if (! $response->successful()) return null;
            $data = $response->json();
            if (! is_array($data) || ! isset($data['intent'], $data['confidence'], $data['is_confident'])) return null;

            return $data;
        } catch (Throwable) {
            return null;
        }
    }
}
