<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;

Route::get('/lobby', [VoteController::class, 'lobby']);

// Aplica um rate limit para danadinhos nao roubar na nossa votação
Route::post('/vote/{option}', [VoteController::class, 'vote'])
    ->middleware('throttle:100,1');
