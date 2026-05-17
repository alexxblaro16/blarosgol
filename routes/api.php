<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PredictionController;
use App\Http\Controllers\Api\RankingController;
use Illuminate\Support\Facades\Route;

// Auth público
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Sesión
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Comunidades
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{community}', [CommunityController::class, 'show']);
    Route::post('/communities/{code}/join', [CommunityController::class, 'join']);
    Route::get('/communities/{community}/requests', [CommunityController::class, 'requests']);
    Route::post('/communities/{community}/requests/{userId}/accept', [CommunityController::class, 'acceptRequest']);
    Route::delete('/communities/{community}/members/{userId}', [CommunityController::class, 'removeMember']);
    Route::get('/communities/{community}/ranking', [RankingController::class, 'community']);

    // Predicciones
    Route::get('/predictions', [PredictionController::class, 'index']);
    Route::post('/predictions', [PredictionController::class, 'store']);
    Route::patch('/predictions/{prediction}', [PredictionController::class, 'update']);
    Route::delete('/predictions/{prediction}', [PredictionController::class, 'destroy']);

    // Partidos (lectura)
    Route::get('/matches', [MatchController::class, 'index']);
    Route::get('/matches/{match}', [MatchController::class, 'show']);

    // Fases (lectura pública para todos los autenticados)
    Route::get('/phases', [AdminController::class, 'publicPhases']);

    // Admin
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/phases', [AdminController::class, 'phases']);
        Route::post('/phases/{code}/activate', [AdminController::class, 'activatePhase']);
        Route::post('/matches/import', [AdminController::class, 'importMatches']);
        Route::patch('/matches/{match}/result', [AdminController::class, 'updateMatchResult']);
    });
});
