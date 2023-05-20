<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\PokemonController;
use App\Http\Controllers\Api\SavesController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\TokensController;
use App\Http\Controllers\Api\TradeController;
use App\Models\User;
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


// API Keys

Route::middleware('auth:sanctum')->get('/tokens', [TokensController::class, 'get']);
Route::middleware('auth:sanctum')->post('/tokens', [TokensController::class, 'create']);
Route::middleware('auth:sanctum')->get('/tokens/{token}', [TokenController::class, 'get']);
Route::middleware('auth:sanctum')->delete('/tokens/{token}', [TokenController::class, 'remove']);


// Account

Route::middleware('auth:sanctum')->get('/account', [AccountController::class, 'get']);
Route::middleware('auth:sanctum')->post('/account', [AccountController::class, 'update']);


// Saves

Route::middleware('auth:sanctum')->get('/saves', [SavesController::class, 'getSaves']);
Route::middleware('auth:sanctum')->get('/saves/trades', [TradeController::class, 'get']);

// This seems unnecessary and more complex than the post/update with the num included
//Route::middleware('auth:sanctum')->post('/saves', [SavesController::class, 'post']);

Route::middleware('auth:sanctum')->get('/saves/{num}', [SavesController::class, 'getSave']);
Route::middleware('auth:sanctum')->get('/saves/{num}/trades', [TradeController::class, 'getSaveTrades']);


Route::middleware('auth:sanctum')->post('/saves/{num}', [SavesController::class, 'updateSave']);


// Pokemon

Route::middleware('auth:sanctum')->get('/saves/{num}/pokemon', [PokemonController::class, 'get']);
Route::middleware('auth:sanctum')->get('/saves/{num}/pokemon/all', [PokemonController::class, 'all']);
Route::middleware('auth:sanctum')->get('/saves/{num}/pokemon/{id}', [PokemonController::class, 'getPokemon']);
Route::middleware('auth:sanctum')->delete('/saves/{num}/pokemon/{id}', [PokemonController::class, 'remove']);
Route::middleware('auth:sanctum')->get('/pokemon/{id}', [PokemonController::class, 'anyPokemon']);


// Trade

Route::middleware('auth:sanctum')->get('/trades', [TradeController::class, 'all']);
Route::middleware('auth:sanctum')->post('/trade', [TradeController::class, 'create']);
Route::middleware('auth:sanctum')->delete('/trade', [TradeController::class, 'remove']);
Route::middleware('auth:sanctum')->get('/trade/pokemon/{id}', [PokemonController::class, 'tradePokemon']);


// Offers

Route::middleware('auth:sanctum')->delete('/offers/{id}', [OfferController::class, 'remove']);
Route::middleware('auth:sanctum')->post('/pokemon/{id}/offer', [OfferController::class, 'create']);
Route::middleware('auth:sanctum')->post('/offers/{id}/accept', [OfferController::class, 'accept']);


// Admin Routes
Route::middleware(['auth:sanctum', 'admin'])->any('/admin/user/{userId}', function (Request $request, int $userId) {
    $route = $request->input('route');

    if(!$route) {
        return response("The route parameter is required", 400);
    }

    $realUser = Auth::user();

    Auth::setUser(User::find($userId));

    $response = app()->handle(Request::create('/api' . $route, $request->method()));

    Auth::setUser($realUser);

    return $response;
});
