<?php

use App\Http\Controllers\Dashboard\ChatbotConfigController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/chatbot')
    ->name('admin.chatbot.')
    ->middleware(['auth', 'active', 'permission:manage-chatbot'])
    ->group(function () {

        // ── Section Pages ──────────────────────────────────────────────────
        Route::get('/', [ChatbotConfigController::class, 'analytics'])->name('analytics');
        Route::get('/apikeys', [ChatbotConfigController::class, 'apikeyPage'])->name('apikeys');
        Route::get('/knowledge', [ChatbotConfigController::class, 'knowledgePage'])->name('knowledge');
        Route::get('/faqs', [ChatbotConfigController::class, 'faqsPage'])->name('faqs');
        Route::get('/history', [ChatbotConfigController::class, 'historyPage'])->name('history');
        Route::get('/logs', [ChatbotConfigController::class, 'logsPage'])->name('logs');
        Route::get('/guide', [ChatbotConfigController::class, 'guide'])->name('guide');

        // ── Redirect old /chatbot index ────────────────────────────────────
        Route::redirect('/index', '/admin/chatbot')->name('index');

        // ── API Keys CRUD ──────────────────────────────────────────────────
        Route::post('/apikeys', [ChatbotConfigController::class, 'storeApiKey'])->name('apikeys.store');
        Route::put('/apikeys/{apiKey}', [ChatbotConfigController::class, 'updateApiKey'])->name('apikeys.update');
        Route::put('/apikeys/{apiKey}/toggle', [ChatbotConfigController::class, 'toggleApiKey'])->name('apikeys.toggle');
        Route::delete('/apikeys/{apiKey}', [ChatbotConfigController::class, 'destroyApiKey'])->name('apikeys.destroy');

        // ── Knowledge Base CRUD ────────────────────────────────────────────
        Route::post('/knowledge', [ChatbotConfigController::class, 'storeKnowledge'])->name('knowledge.store');
        Route::put('/knowledge/{knowledge}', [ChatbotConfigController::class, 'updateKnowledge'])->name('knowledge.update');
        Route::delete('/knowledge/{knowledge}', [ChatbotConfigController::class, 'destroyKnowledge'])->name('knowledge.destroy');

        // ── FAQs CRUD ──────────────────────────────────────────────────────
        Route::post('/faqs', [ChatbotConfigController::class, 'storeFaq'])->name('faqs.store');
        Route::put('/faqs/{faq}', [ChatbotConfigController::class, 'updateFaq'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [ChatbotConfigController::class, 'destroyFaq'])->name('faqs.destroy');

        // ── Session transcript (JSON) ──────────────────────────────────────
        Route::get('/sessions/{session}', [ChatbotConfigController::class, 'showSession'])->name('sessions.show');

        // ── Chatbot Global Switch ──────────────────────────────────────────
        Route::put('/toggle', [ChatbotConfigController::class, 'toggleChatbot'])->name('toggle');

        // ── Log management ─────────────────────────────────────────────────
        Route::get('/logs/{log}', [ChatbotConfigController::class, 'showLog'])->name('logs.show');
        Route::delete('/logs/clear', [ChatbotConfigController::class, 'clearLogs'])->name('logs.clear');
    });
