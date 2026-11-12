<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Scheme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotCatalogueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SchemeFaqSeeder::class);
        $this->withHeaders(['Origin' => 'http://localhost:5173', 'Referer' => 'http://localhost:5173/']);
        Http::fake();
    }

    public function test_seeded_catalogue_has_ten_bilingual_services_and_thirty_faqs(): void
    {
        $this->assertDatabaseCount('schemes', 20);
        $this->assertDatabaseCount('faqs', 30);
        $this->assertDatabaseHas('schemes', ['translation_key' => 'ration_card', 'language' => 'hi', 'official_source_url' => 'https://nfsa.gov.in/portal/apply']);
        $this->assertDatabaseMissing('schemes', ['official_source_url' => null]);
    }

    public function test_public_faq_api_returns_the_full_bilingual_catalogue_with_source_links(): void
    {
        $english = $this->getJson('/api/faqs?language=en')->assertOk()->json('data');
        $hindi = $this->getJson('/api/faqs?language=hi')->assertOk()->json('data');

        $this->assertCount(15, $english);
        $this->assertCount(15, $hindi);
        $this->assertCount(14, array_filter($english, fn ($faq) => filled($faq['official_source_url'])));
        $this->assertCount(14, array_filter($hindi, fn ($faq) => filled($faq['official_source_url'])));
    }

    public function test_public_catalogue_exposes_hinglish_variants_without_creating_translation_rows(): void
    {
        $faqs = $this->getJson('/api/faqs?language=hinglish')->assertOk()->json('data');
        $schemes = $this->getJson('/api/schemes?language=hinglish')->assertOk()->json('data');

        $this->assertCount(15, $faqs);
        $this->assertCount(10, $schemes);
        $this->assertSame('hinglish', $faqs[0]['language']);
        $this->assertSame('hinglish', $schemes[0]['language']);
        $this->assertDatabaseCount('faqs', 30);
        $this->assertDatabaseCount('schemes', 20);
    }

    public function test_english_query_uses_the_english_service_guide_even_when_ui_language_is_hindi(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Aadhaar enrolment and update details',
            'language' => 'hi',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'aadhaar')
            ->assertJsonPath('answer.language', 'en')
            ->assertJsonPath('answer.source.type', 'scheme')
            ->assertJsonPath('answer.source.url', 'https://uidai.gov.in/en/enrolment-and-updates');

        $guide = Scheme::findOrFail($response->json('answer.matched_scheme_id'));
        $this->assertSame('en', $guide->language);
        $this->assertStringContainsString($guide->description, $response->json('answer.reply'));
    }

    public function test_hindi_query_uses_the_hindi_faq_even_when_ui_language_is_english(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'आधार अपडेट दस्तावेज़ चाहिए',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'aadhaar')
            ->assertJsonPath('answer.language', 'hi')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', 'https://uidai.gov.in/en/enrolment-and-updates');

        $faq = Faq::findOrFail($response->json('answer.matched_faq_id'));
        $this->assertSame('hi', $faq->language);
        $this->assertSame($faq->answer, $response->json('answer.reply'));
    }

    public function test_romanized_hinglish_is_classified_separately_from_hindi(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'PM Kisan ka status kaise check karun?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'pm_kisan')
            ->assertJsonPath('answer.language', 'hinglish')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', 'https://pmkisan.gov.in/');

        $faq = Faq::findOrFail($response->json('answer.matched_faq_id'));
        $this->assertSame($faq->answer_hinglish, $response->json('answer.reply'));
        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
    }

    public function test_english_pan_query_returns_pan_specific_answer_and_official_source(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Do I need a new PAN because of PAN 2.0?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'pan_card')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', 'https://static.pib.gov.in/WriteReadData/specificdocs/documents/2024/nov/doc20241127454301.pdf');

        $this->assertStringContainsString('existing PAN holders do not need', $response->json('answer.reply'));
        Http::assertNothingSent();
    }

    public function test_bare_pan_question_matches_the_pan_catalogue(): void
    {
        $this->postJson('/api/chatbot/query', ['query' => 'What is PAN?', 'language' => 'en'])
            ->assertOk()
            ->assertJsonPath('answer.intent', 'pan_card')
            ->assertJsonPath('answer.source.type', 'scheme')
            ->assertJsonPath('answer.source.url', 'https://www.incometax.gov.in/iec/foportal/help/all-topics/e-filing-services/income-tax-forms');

        Http::assertNothingSent();
    }

    public function test_hindi_ration_card_answer_explains_state_specific_rules(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'राशन कार्ड के लिए आवेदन कहाँ करें?',
            'language' => 'hi',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'ration_card')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', 'https://nfsa.gov.in/portal/apply');

        $this->assertStringContainsString('राज्य/केंद्रशासित प्रदेश', $response->json('answer.reply'));
        $this->assertStringContainsString('एक जैसी नहीं', $response->json('answer.reply'));
        Http::assertNothingSent();
    }

    public function test_hinglish_pm_kisan_query_returns_status_and_ekyc_guidance(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'PM Kisan ka paisa status kaise check karun?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'pm_kisan')
            ->assertJsonPath('answer.language', 'hinglish')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', 'https://pmkisan.gov.in/');

        $faq = Faq::findOrFail($response->json('answer.matched_faq_id'));
        $this->assertSame($faq->answer_hinglish, $response->json('answer.reply'));
        $this->assertStringContainsString('Know Your Status', $response->json('answer.reply'));
        $this->assertStringContainsString('e-KYC', $response->json('answer.reply'));
        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
        $this->assertDatabaseHas('chat_histories', [
            'matched_faq_id' => $faq->id,
            'intent' => 'pm_kisan',
            'language' => 'hinglish',
        ]);
        Http::assertNothingSent();
    }

    public function test_mixed_script_query_returns_roman_script_hinglish(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'पीएम किसान ka status kaise check karun?',
            'language' => 'hi',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'pm_kisan')
            ->assertJsonPath('answer.language', 'hinglish');

        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
        Http::assertNothingSent();
    }

    public function test_romanized_service_alias_with_english_verb_is_hinglish(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Rashan card apply',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'ration_card')
            ->assertJsonPath('answer.language', 'hinglish')
            ->assertJsonPath('answer.source.type', 'faq');

        $this->assertStringContainsString('State/UT', $response->json('answer.reply'));
        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
    }

    public function test_hinglish_query_uses_a_hinglish_service_guide(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Aadhaar ke baare mein batao',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'aadhaar')
            ->assertJsonPath('answer.language', 'hinglish')
            ->assertJsonPath('answer.source.type', 'scheme')
            ->assertJsonPath('answer.source.url', 'https://uidai.gov.in/en/enrolment-and-updates');

        $guide = Scheme::findOrFail($response->json('answer.matched_scheme_id'));
        $this->assertStringContainsString($guide->description_hinglish, $response->json('answer.reply'));
        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
        Http::assertNothingSent();
    }

    public function test_hinglish_about_question_uses_the_catalogue_variant(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Kya yeh official government website hai?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'faq')
            ->assertJsonPath('answer.language', 'hinglish')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', null);

        $this->assertStringContainsString('Nahi. SevaSetu', $response->json('answer.reply'));
        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
    }

    public function test_hindi_aadhaar_query_uses_bilingual_scheme_record(): void
    {
        $this->postJson('/api/chatbot/query', [
            'query' => 'आधार अपडेट की जानकारी चाहिए',
            'language' => 'hi',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'aadhaar')
            ->assertJsonPath('answer.source.type', 'scheme')
            ->assertJsonPath('answer.source.url', 'https://uidai.gov.in/en/enrolment-and-updates')
            ->assertJsonPath('answer.language', 'hi');

        Http::assertNothingSent();
    }

    public function test_scholarship_question_returns_a_scheme_specific_faq(): void
    {
        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Where can I check scholarship eligibility?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'scholarship')
            ->assertJsonPath('answer.source.url', 'https://scholarships.gov.in/All-Scholarships');

        $this->assertStringContainsString('not the same for every scholarship', $response->json('answer.reply'));
        Http::assertNothingSent();
    }

    public function test_passport_query_selects_the_matching_general_service(): void
    {
        $this->postJson('/api/chatbot/query', [
            'query' => 'How can I book a passport appointment?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'general_scheme')
            ->assertJsonPath('answer.source.type', 'faq')
            ->assertJsonPath('answer.source.url', 'https://passportindia.gov.in/psp/apply');

        Http::assertNothingSent();
    }

    public function test_digilocker_and_umang_queries_select_their_own_faqs(): void
    {
        $this->postJson('/api/chatbot/query', ['query' => 'What can I do with DigiLocker?', 'language' => 'en'])
            ->assertOk()
            ->assertJsonPath('answer.source.url', 'https://www.digilocker.gov.in/');
        $this->postJson('/api/chatbot/query', ['query' => 'What is UMANG and where can I find its services?', 'language' => 'en'])
            ->assertOk()
            ->assertJsonPath('answer.source.url', 'https://www.umang.gov.in/landing/aboutus');

        Http::assertNothingSent();
    }

    public function test_unknown_intent_keeps_safe_fallback_without_a_catalogue_answer(): void
    {
        Http::fake(['http://127.0.0.1:8001/predict' => Http::response([
            'intent' => 'unknown', 'confidence' => 0.91, 'is_confident' => false,
        ])]);

        $this->postJson('/api/chatbot/query', [
            'query' => 'What is the weather in Delhi?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'unknown')
            ->assertJsonPath('answer.source', null)
            ->assertJsonPath('answer.matched_scheme_id', null)
            ->assertJsonPath('answer.reply', 'I could not find verified information for that question yet. Try naming a service, or check the relevant official government portal.');

        Http::assertSent(fn ($request) => $request->url() === 'http://127.0.0.1:8001/predict');
    }

    public function test_unknown_hinglish_query_keeps_the_fallback_in_roman_script(): void
    {
        Http::fake(['http://127.0.0.1:8001/predict' => Http::response([
            'intent' => 'unknown', 'confidence' => 0.91, 'is_confident' => false,
        ])]);

        $response = $this->postJson('/api/chatbot/query', [
            'query' => 'Mausam kaisa hoga?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'unknown')
            ->assertJsonPath('answer.language', 'hinglish')
            ->assertJsonPath('answer.source', null);

        $this->assertDoesNotMatchRegularExpression('/[\x{0900}-\x{097F}]/u', $response->json('answer.reply'));
    }

    public function test_low_confidence_classifier_result_is_not_used_as_an_answer(): void
    {
        Http::fake(['http://127.0.0.1:8001/predict' => Http::response([
            'intent' => 'scholarship', 'confidence' => 0.20, 'is_confident' => false,
        ])]);

        $this->postJson('/api/chatbot/query', [
            'query' => 'Can you tell me something useful?',
            'language' => 'en',
        ])->assertOk()
            ->assertJsonPath('answer.intent', 'unknown')
            ->assertJsonPath('answer.source', null);
    }
}
