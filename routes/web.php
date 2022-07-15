<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\User;
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

Route::get('/tokens', function (User $user) {
    return ['tokens' => Auth::user()->tokens()->get(['id', 'last_used_at', 'created_at', 'updated_at'])];
})->middleware('auth');

Route::post('/tokens', function () {
    return ['token' => Auth::user()->createToken('token')->plainTextToken];
})->middleware('auth');
