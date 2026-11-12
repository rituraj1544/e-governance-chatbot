<?php

namespace Tests\Feature;

use App\Models\ChatHistory;
use App\Models\Faq;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminAndChatApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders(['Origin' => 'http://localhost:5173', 'Referer' => 'http://localhost:5173/']);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    public function test_admin_apis_reject_guests_and_citizens(): void
    {
        $this->getJson('/api/admin/analytics')->assertUnauthorized();
        $citizen = User::factory()->create(['role' => 'citizen']);
        $this->actingAs($citizen)->getJson('/api/admin/analytics')->assertForbidden();
    }

    public function test_admin_can_manage_schemes_and_faqs(): void
    {
        $this->actingAs($this->admin());
        $scheme = $this->postJson('/api/admin/schemes', [
            'title' => 'PAN Card demo', 'category' => 'pan_card', 'description' => 'Verified details are pending review.',
            'title_hinglish' => 'PAN Card guide', 'description_hinglish' => 'Yeh verified Hinglish guide hai.',
            'language' => 'en', 'is_active' => true, 'keywords' => ['pan'],
        ])->assertCreated()->assertJsonPath('category', 'pan_card')->assertJsonPath('description_hinglish', 'Yeh verified Hinglish guide hai.')->json();
        $this->putJson('/api/admin/schemes/'.$scheme['id'], [
            'title' => 'PAN Card information', 'category' => 'pan_card', 'description' => 'Updated reviewed record.',
            'title_hinglish' => 'PAN Card jaankari', 'description_hinglish' => 'Updated reviewed Hinglish guide.',
            'language' => 'en', 'is_active' => true,
        ])->assertOk()->assertJsonPath('title', 'PAN Card information')->assertJsonPath('description_hinglish', 'Updated reviewed Hinglish guide.');

        $faq = $this->postJson('/api/admin/faqs', [
            'question' => 'Is this official?', 'answer' => 'No, this is an informational project.',
            'question_hinglish' => 'Kya yeh official hai?', 'answer_hinglish' => 'Nahi, yeh informational project hai.',
            'category' => 'about', 'official_source_url' => 'https://www.india.gov.in/',
            'language' => 'en', 'is_active' => true,
        ])->assertCreated()->assertJsonPath('official_source_url', 'https://www.india.gov.in/')->assertJsonPath('answer_hinglish', 'Nahi, yeh informational project hai.')->json();
        $this->putJson('/api/admin/faqs/'.$faq['id'], [
            'question' => 'Is this service official?', 'answer' => 'No.', 'category' => 'about',
            'question_hinglish' => 'Kya yeh service official hai?', 'answer_hinglish' => 'Nahi.',
            'language' => 'en', 'is_active' => true,
        ])->assertOk()->assertJsonPath('question', 'Is this service official?')->assertJsonPath('answer_hinglish', 'Nahi.');

        $this->deleteJson('/api/admin/schemes/'.$scheme['id'])->assertNoContent();
        $this->deleteJson('/api/admin/faqs/'.$faq['id'])->assertNoContent();
    }

    public function test_public_chat_uses_catalogue_and_history_is_private(): void
    {
        $scheme = Scheme::create([
            'title' => 'Aadhaar test guide', 'category' => 'aadhaar', 'description' => 'This is a reviewed test catalogue entry.',
            'official_source_url' => 'https://uidai.gov.in/en/enrolment-and-updates',
            'language' => 'en', 'keywords' => ['aadhaar'], 'is_active' => true,
        ]);
        $this->postJson('/api/chatbot/query', ['query' => 'Tell me about Aadhaar', 'language' => 'en'])
            ->assertOk()->assertJsonPath('answer.source.id', $scheme->id);
        $this->assertDatabaseHas('chat_histories', ['intent' => 'aadhaar', 'matched_scheme_id' => $scheme->id, 'user_id' => null]);

        $citizen = User::factory()->create();
        $own = ChatHistory::create([
            'user_id' => $citizen->id, 'matched_scheme_id' => $scheme->id,
            'query' => 'My question', 'response' => 'My response', 'intent' => 'aadhaar', 'language' => 'en',
        ]);
        $other = User::factory()->create();
        $private = ChatHistory::create(['user_id' => $other->id, 'query' => 'Private', 'response' => 'Hidden', 'intent' => 'unknown', 'language' => 'en']);

        $this->actingAs($citizen)->getJson('/api/chat-history')->assertOk()
            ->assertJsonFragment(['query' => 'My question'])
            ->assertJsonPath('data.0.scheme.official_source_url', 'https://uidai.gov.in/en/enrolment-and-updates')
            ->assertJsonMissing(['query' => 'Private']);
        $this->deleteJson('/api/chat-history/'.$private->id)->assertNotFound();
        $this->deleteJson('/api/chat-history/'.$own->id)->assertNoContent();
    }

    public function test_chatbot_falls_back_when_python_service_is_unavailable(): void
    {
        Http::fake(['http://127.0.0.1:8001/predict' => Http::response([], 503)]);
        $this->postJson('/api/chatbot/query', ['query' => 'What is the weather in Delhi?', 'language' => 'en'])
            ->assertOk()->assertJsonPath('answer.intent', 'unknown')
            ->assertJsonPath('answer.reply', 'I could not find verified information for that question yet. Try naming a service, or check the relevant official government portal.');
    }

    public function test_profile_update_password_change_and_analytics_are_authorized(): void
    {
        $citizen = User::factory()->create(['password' => 'OriginalPass123']);
        $this->actingAs($citizen);
        $this->patchJson('/api/profile', ['name' => 'Updated Citizen', 'email' => $citizen->email])
            ->assertOk()->assertJsonPath('user.name', 'Updated Citizen');
        $this->putJson('/api/profile/password', [
            'current_password' => 'wrong-password', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertUnprocessable();
        $this->putJson('/api/profile/password', [
            'current_password' => 'OriginalPass123', 'password' => 'NewPassword123', 'password_confirmation' => 'NewPassword123',
        ])->assertOk();
        $this->getJson('/api/admin/analytics')->assertForbidden();
    }

    public function test_admin_can_view_database_backed_analytics(): void
    {
        $this->actingAs($this->admin())->getJson('/api/admin/analytics')->assertOk()
            ->assertJsonStructure(['stats' => ['citizens', 'schemes', 'faqs', 'queries', 'queries_today'], 'daily_queries', 'intents', 'languages', 'recent']);
    }
}
