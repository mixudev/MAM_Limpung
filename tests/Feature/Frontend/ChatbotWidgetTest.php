<?php

use App\Models\ChatbotApiKey;
use App\Models\ChatbotFaq;
use App\Models\ChatbotSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed default settings or seeders if necessary, otherwise construct on demand
});

test('guest or user can retrieve active faqs', function () {
    ChatbotFaq::factory()->create([
        'question' => 'Kapan pendaftaran PPDB?',
        'answer' => 'Pendaftaran dibuka tanggal 1 Januari.',
        'topic' => 'ppdb',
        'is_active' => true,
    ]);

    ChatbotFaq::factory()->create([
        'question' => 'Lokasi sekolah dimana?',
        'answer' => 'Lokasi di Jalan Cokronegoro.',
        'topic' => 'umum',
        'is_active' => true,
    ]);

    // Query general faqs
    $response = $this->getJson(route('frontend.chatbot.faqs'));
    $response->assertStatus(200)
        ->assertJsonCount(2);

    // Filter by topic
    $responseFilter = $this->getJson(route('frontend.chatbot.faqs', ['topic' => 'ppdb']));
    $responseFilter->assertStatus(200)
        ->assertJsonCount(2); // ppdb and umum combined
});

test('guest can start new chat session', function () {
    $response = $this->postJson(route('frontend.chatbot.sessions.start'), [
        'topic' => 'ppdb',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['id', 'topic', 'user_ip']);

    $this->assertDatabaseHas('chatbot_sessions', [
        'id' => $response->json('id'),
        'topic' => 'ppdb',
    ]);
});

test('guest can retrieve history from localStorage list', function () {
    $session1 = ChatbotSession::factory()->create(['topic' => 'ppdb']);
    $session2 = ChatbotSession::factory()->create(['topic' => 'kegiatan']);

    $response = $this->postJson(route('frontend.chatbot.history'), [
        'session_ids' => [$session1->id, $session2->id],
    ]);

    $response->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment(['id' => $session1->id])
        ->assertJsonFragment(['id' => $session2->id]);
});

test('sending message returns FAQ answer instantly if matched', function () {
    $session = ChatbotSession::factory()->create(['topic' => 'ppdb']);

    ChatbotFaq::factory()->create([
        'question' => 'Berapa biaya pendaftaran?',
        'answer' => 'Biaya pendaftaran adalah gratis.',
        'is_active' => true,
    ]);

    // Send question exactly matching FAQ
    $response = $this->postJson(route('frontend.chatbot.sessions.send', $session->id), [
        'message' => 'Berapa biaya pendaftaran?',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('bot_message.message', 'Biaya pendaftaran adalah gratis.');

    $this->assertDatabaseHas('chatbot_messages', [
        'session_id' => $session->id,
        'sender' => 'user',
        'message' => 'Berapa biaya pendaftaran?',
    ]);

    $this->assertDatabaseHas('chatbot_messages', [
        'session_id' => $session->id,
        'sender' => 'bot',
        'message' => 'Biaya pendaftaran adalah gratis.',
    ]);
});

test('sending message calls Gemini AI API when no FAQ matches', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Ini adalah jawaban dari AI.'],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    ChatbotApiKey::factory()->create([
        'provider' => 'gemini',
        'model_name' => 'gemini-1.5-flash',
        'api_key' => 'fake_api_key_123',
        'is_active' => true,
    ]);

    $session = ChatbotSession::factory()->create(['topic' => 'umum']);

    $response = $this->postJson(route('frontend.chatbot.sessions.send', $session->id), [
        'message' => 'Tanya hal unik tentang sekolah?',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('bot_message.message', 'Ini adalah jawaban dari AI.');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'gemini-1.5-flash')
            && str_contains($request->url(), 'key=fake_api_key_123')
            && $request['generationConfig']['temperature'] === 0.4;
    });

    $this->assertDatabaseHas('chatbot_analytics', [
        'session_id' => $session->id,
        'query' => 'Tanya hal unik tentang sekolah?',
        'response' => 'Ini adalah jawaban dari AI.',
    ]);
});

test('API key rotates to key 2 if key 1 throws rate limit 429 error', function () {
    Http::fake([
        // First key request returns 429
        'generativelanguage.googleapis.com/*fake_key_one*' => Http::response(['error' => ['message' => 'Quota Exceeded']], 429),
        // Second key request returns 200
        'generativelanguage.googleapis.com/*fake_key_two*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Jawaban dari API Key Kedua.'],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $key1 = ChatbotApiKey::factory()->create([
        'api_key' => 'fake_key_one',
        'is_active' => true,
        'error_count' => 0,
    ]);

    $key2 = ChatbotApiKey::factory()->create([
        'api_key' => 'fake_key_two',
        'is_active' => true,
        'error_count' => 0,
    ]);

    $session = ChatbotSession::factory()->create(['topic' => 'umum']);

    $response = $this->postJson(route('frontend.chatbot.sessions.send', $session->id), [
        'message' => 'Halo AI',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('bot_message.message', 'Jawaban dari API Key Kedua.');

    // Key 1 should be marked with error count = 1 and limit_reached_at populated
    $key1->refresh();
    expect($key1->error_count)->toBe(1);
    expect($key1->limit_reached_at)->not->toBeNull();

    // Key 2 should remain clean
    $key2->refresh();
    expect($key2->error_count)->toBe(0);
});

test('sending message writes activity logs to the database', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*fake_key_one*' => Http::response(['error' => ['message' => 'Quota Exceeded']], 429),
        'generativelanguage.googleapis.com/*fake_key_two*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Jawaban dari API Key Kedua.'],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $key1 = ChatbotApiKey::factory()->create([
        'api_key' => 'fake_key_one',
        'is_active' => true,
        'error_count' => 0,
    ]);

    $key2 = ChatbotApiKey::factory()->create([
        'api_key' => 'fake_key_two',
        'is_active' => true,
        'error_count' => 0,
    ]);

    $session = ChatbotSession::factory()->create(['topic' => 'umum']);

    $this->postJson(route('frontend.chatbot.sessions.send', $session->id), [
        'message' => 'Halo AI',
    ]);

    // Check warning log for key 1 failure
    $this->assertDatabaseHas('chatbot_logs', [
        'api_key_id' => $key1->id,
        'level' => 'warning',
    ]);

    // Check success log for key 2 success
    $this->assertDatabaseHas('chatbot_logs', [
        'session_id' => $session->id,
        'api_key_id' => $key2->id,
        'level' => 'success',
    ]);
});
