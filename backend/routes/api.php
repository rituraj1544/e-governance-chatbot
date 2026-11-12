<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\ChatHistoryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\SchemeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:8,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:8,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');
});

Route::post('/admin/auth/login', [AuthController::class, 'adminLogin'])->middleware('throttle:5,1');
Route::get('/schemes/search', [SchemeController::class, 'index']);
Route::get('/schemes', [SchemeController::class, 'index']);
Route::get('/schemes/{scheme}', [SchemeController::class, 'show'])->whereNumber('scheme');
Route::get('/faqs/search', [FaqController::class, 'index']);
Route::get('/faqs', [FaqController::class, 'index']);
Route::post('/chatbot/query', [ChatbotController::class, 'query'])->middleware('throttle:30,1');

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::patch('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'changePassword']);
    Route::get('/chat-history', [ChatHistoryController::class, 'index']);
    Route::delete('/chat-history/{chatHistory}', [ChatHistoryController::class, 'destroy'])->whereNumber('chatHistory');
});

Route::prefix('admin')->middleware(['auth:sanctum', 'active', 'admin'])->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->whereNumber('user');
    Route::get('/chat-history', [ChatHistoryController::class, 'adminIndex']);
    Route::get('/schemes', [SchemeController::class, 'index']);
    Route::post('/schemes', [SchemeController::class, 'store']);
    Route::put('/schemes/{scheme}', [SchemeController::class, 'update'])->whereNumber('scheme');
    Route::delete('/schemes/{scheme}', [SchemeController::class, 'destroy'])->whereNumber('scheme');
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::post('/faqs', [FaqController::class, 'store']);
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->whereNumber('faq');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->whereNumber('faq');
});
