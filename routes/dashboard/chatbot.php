<?php

use App\Http\Controllers\Dashboard\ChatbotConfigController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'active', 'permission:manage-chatbot'])
    ->group(function () {
        Route::get('/chatbot', [ChatbotConfigController::class, 'index'])->name('chatbot.index');

        // API Keys CRUD
        Route::post('/chatbot/apikeys', [ChatbotConfigController::class, 'storeApiKey'])->name('chatbot.apikeys.store');
        Route::put('/chatbot/apikeys/{apiKey}', [ChatbotConfigController::class, 'updateApiKey'])->name('chatbot.apikeys.update');
        Route::put('/chatbot/apikeys/{apiKey}/toggle', [ChatbotConfigController::class, 'toggleApiKey'])->name('chatbot.apikeys.toggle');
        Route::delete('/chatbot/apikeys/{apiKey}', [ChatbotConfigController::class, 'destroyApiKey'])->name('chatbot.apikeys.destroy');

        // Knowledge Base CRUD
        Route::post('/chatbot/knowledge', [ChatbotConfigController::class, 'storeKnowledge'])->name('chatbot.knowledge.store');
        Route::put('/chatbot/knowledge/{knowledge}', [ChatbotConfigController::class, 'updateKnowledge'])->name('chatbot.knowledge.update');
        Route::delete('/chatbot/knowledge/{knowledge}', [ChatbotConfigController::class, 'destroyKnowledge'])->name('chatbot.knowledge.destroy');

        // FAQs CRUD
        Route::post('/chatbot/faqs', [ChatbotConfigController::class, 'storeFaq'])->name('chatbot.faqs.store');
        Route::put('/chatbot/faqs/{faq}', [ChatbotConfigController::class, 'updateFaq'])->name('chatbot.faqs.update');
        Route::delete('/chatbot/faqs/{faq}', [ChatbotConfigController::class, 'destroyFaq'])->name('chatbot.faqs.destroy');

        // Session transcript view
        Route::get('/chatbot/sessions/{session}', [ChatbotConfigController::class, 'showSession'])->name('chatbot.sessions.show');

        // Panduan penggunaan
        Route::get('/chatbot/guide', [ChatbotConfigController::class, 'guide'])->name('chatbot.guide');
    });
