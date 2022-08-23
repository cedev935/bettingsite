<?php

use App\Http\Controllers\Api\BetApiController;
use App\Http\Controllers\Api\PlayerApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// guest routes
Route::post('/register', [PlayerApiController::class, 'register'])
    ->name('register.api');

Route::post('/login', [PlayerApiController::class, 'login'])
    ->name('login.api');

// authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [PlayerApiController::class, 'logout'])
        ->name('logout.api');
    //

    Route::post('/bet', [BetApiController::class, 'placeBet'])
        ->name('bet.api');

});



