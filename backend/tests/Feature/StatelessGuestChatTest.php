<?php

namespace Tests\Feature;

use App\Models\Scheme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatelessGuestChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_chat_query_works_without_a_session_cookie(): void
    {
        Scheme::create([
            'title' => 'Aadhaar demo', 'category' => 'aadhaar', 'description' => 'Test catalogue entry.',
            'language' => 'en', 'keywords' => ['aadhaar'], 'is_active' => true,
        ]);

        $response = $this->postJson('/api/chatbot/query', ['query' => 'Tell me about Aadhaar', 'language' => 'en'])
            ->assertOk()->assertJsonPath('answer.intent', 'aadhaar');

        $this->assertStringContainsString('No official source is linked', $response->json('answer.reply'));
        $this->assertStringNotContainsString('Demo catalogue entry', $response->json('answer.reply'));
    }
}
