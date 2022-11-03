<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\SavesController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\TokensController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/tokens', [TokensController::class, 'get']);

Route::middleware('auth:sanctum')->post('/tokens', [TokensController::class, 'create']);

Route::middleware('auth:sanctum')->get('/tokens/{token}', [TokenController::class, 'get']);

Route::middleware('auth:sanctum')->delete('/tokens/{token}', [TokenController::class, 'remove']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/account', [AccountController::class, 'get']);
Route::middleware('auth:sanctum')->post('/account', [AccountController::class, 'post']);

Route::middleware('auth:sanctum')->get('/saves', [SavesController::class, 'get']);
// This seems unnecessary and more complex than the post/update with the num included
//Route::middleware('auth:sanctum')->post('/saves', [SavesController::class, 'post']);

Route::middleware('auth:sanctum')->get('/saves/{num}', [SavesController::class, 'getSpecificSave']);
Route::middleware('auth:sanctum')->post('/saves/{num}', [SavesController::class, 'postSpecificSave']);

require __DIR__.'/auth.php';
