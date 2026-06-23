<?php

use App\Models\ChatbotApiKey;
use App\Models\ChatbotFaq;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotSession;
use App\Models\User;
use Database\Seeders\Auth\PermissionSeeder;
use Database\Seeders\Auth\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

// ─── ACCESS CONTROL ──────────────────────────────────────────────────────────

test('unauthenticated guest cannot access chatbot config dashboard', function () {
    $this->get(route('admin.chatbot.index'))->assertRedirect(route('login'));
});

test('unauthorized user without manage-chatbot permission is blocked from chatbot dashboard', function () {
    $user = User::factory()->create(); // No roles assigned

    $this->actingAs($user)
        ->get(route('admin.chatbot.index'))
        ->assertStatus(403);
});

test('authorized admin can view chatbot config dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin'); // Has manage-chatbot permission from RoleSeeder

    $response = $this->actingAs($admin)->get(route('admin.chatbot.analytics'));

    $response->assertStatus(200)
        ->assertViewIs('dashboard.admin.chatbot.analytics')
        ->assertSee('Analitik');
});

// ─── API KEYS CRUD ───────────────────────────────────────────────────────────

test('authorized admin can store a new api key', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.chatbot.apikeys.store'), [
        'provider' => 'gemini',
        'model_name' => 'gemini-1.5-flash',
        'api_key' => 'AIzaSyTestKeyContent12345',
    ]);

    $response->assertRedirect(route('admin.chatbot.apikeys'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('chatbot_api_keys', [
        'provider' => 'gemini',
        'model_name' => 'gemini-1.5-flash',
    ]);

    // Verify key was encrypted automatically
    $key = ChatbotApiKey::first();
    expect($key->api_key)->toBe('AIzaSyTestKeyContent12345'); // Cast decrypts it automatically
    expect(DB::table('chatbot_api_keys')->first()->api_key)->not->toBe('AIzaSyTestKeyContent12345'); // DB holds cipher
});

test('authorized admin can update an api key without changing the secret key', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $key = ChatbotApiKey::factory()->create([
        'provider' => 'gemini',
        'model_name' => 'gemini-1.5-flash',
        'api_key' => 'OriginalAPIKeySecretContent',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.chatbot.apikeys.update', $key), [
        'provider' => 'gemini',
        'model_name' => 'gemini-1.5-pro',
        'api_key' => '', // Empty means do not update
    ]);

    $response->assertRedirect(route('admin.chatbot.apikeys'));

    $key->refresh();
    expect($key->model_name)->toBe('gemini-1.5-pro');
    expect($key->api_key)->toBe('OriginalAPIKeySecretContent'); // Kept intact
});

test('authorized admin can toggle api key status', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $key = ChatbotApiKey::factory()->create(['is_active' => true]);

    $response = $this->actingAs($admin)->put(route('admin.chatbot.apikeys.toggle', $key));

    $response->assertRedirect(route('admin.chatbot.apikeys'));
    expect($key->fresh()->is_active)->toBeFalse();
});

test('authorized admin can delete an api key', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $key = ChatbotApiKey::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.chatbot.apikeys.destroy', $key));

    $response->assertRedirect(route('admin.chatbot.apikeys'));
    $this->assertDatabaseMissing('chatbot_api_keys', ['id' => $key->id]);
});

// ─── KNOWLEDGE BASE CRUD ─────────────────────────────────────────────────────

test('authorized admin can store a new knowledge entry', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.chatbot.knowledge.store'), [
        'title' => 'Jadwal Pendaftaran',
        'content' => 'Pendaftaran gelombang satu dibuka Januari.',
    ]);

    $response->assertRedirect(route('admin.chatbot.knowledge'));
    $this->assertDatabaseHas('chatbot_knowledge_bases', [
        'title' => 'Jadwal Pendaftaran',
        'content' => 'Pendaftaran gelombang satu dibuka Januari.',
    ]);
});

test('authorized admin can delete a knowledge entry', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $kb = ChatbotKnowledgeBase::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.chatbot.knowledge.destroy', $kb));

    $response->assertRedirect(route('admin.chatbot.knowledge'));
    $this->assertDatabaseMissing('chatbot_knowledge_bases', ['id' => $kb->id]);
});

// ─── FAQ CRUD ────────────────────────────────────────────────────────────────

test('authorized admin can manage faq entries', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Create
    $responseStore = $this->actingAs($admin)->post(route('admin.chatbot.faqs.store'), [
        'question' => 'Kapan pramuka diadakan?',
        'answer' => 'Pramuka diadakan setiap Jumat sore.',
        'order' => 1,
    ]);
    $responseStore->assertRedirect(route('admin.chatbot.faqs'));
    $this->assertDatabaseHas('chatbot_faqs', ['question' => 'Kapan pramuka diadakan?']);

    // Update
    $faq = ChatbotFaq::first();
    $responseUpdate = $this->actingAs($admin)->put(route('admin.chatbot.faqs.update', $faq), [
        'question' => 'Kapan pramuka diadakan reguler?',
        'answer' => 'Setiap hari Jumat jam 14.00.',
        'order' => 2,
    ]);
    $responseUpdate->assertRedirect(route('admin.chatbot.faqs'));
    expect($faq->fresh()->order)->toBe(2);

    // Delete
    $responseDelete = $this->actingAs($admin)->delete(route('admin.chatbot.faqs.destroy', $faq));
    $responseDelete->assertRedirect(route('admin.chatbot.faqs'));
    $this->assertDatabaseMissing('chatbot_faqs', ['id' => $faq->id]);
});

// ─── SESSION TRANSCRIPT VIEW ─────────────────────────────────────────────────

test('authorized admin can view session transcript logs', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $session = ChatbotSession::factory()->create();

    $response = $this->actingAs($admin)->getJson(route('admin.chatbot.sessions.show', $session->id));

    $response->assertStatus(200)
        ->assertJsonPath('id', $session->id)
        ->assertJsonStructure(['id', 'messages']);
});
