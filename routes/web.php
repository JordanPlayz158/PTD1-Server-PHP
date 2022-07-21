<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    if(Auth::check()) {
        return redirect('/games/ptd/account.html');
    }

    return view('login');
})->name('login');

Route::get('/games/ptd/login.html', function () {
    return redirect('/login');
});

Route::get('/games/ptd/account.html', function() {
    return view('account');
})->middleware('auth');

/*Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');*/

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('loginPost');

Route::get('/logout', function () {
    return view('logout');
})->name('logout')->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logoutPost')->middleware('auth');

Route::get('/apiKeys', function () {
    return view('tokens');
})->middleware('auth');

Route::get('/apiKeys/{apiKeyId}', function () {
    return view('tokensDelete');
})->middleware('auth');

Route::get('/session', function (\Illuminate\Http\Request $request, Session $session) {
    if($request->user()->id !== 4733) {
        return response()->setStatusCode(403);
    }

    return $request->session()->all();
})->middleware('auth');
