<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\SWFController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
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


// Reset Password

Route::get('/forgot-password', function() {
    return view('resetPasswordForm');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function () {
    return view('resetPassword');
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::get('/games/ptd/reset_password_form.html', function () {
    return redirect('/forgot-password');
})->middleware('guest');


// Verify Email

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/games/ptd/resendVerificationEmail.php', function () {
    return view('resendVerificationEmail');
})->middleware('auth');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/games/ptd/account.html');
})->middleware(['auth', 'signed'])->name('verification.verify');


// Root

Route::get('/', function () {
    return view('index');
});


// Login

Route::get('/login', function () {
    if(Auth::check()) {
        return redirect('/games/ptd/account.html');
    }

    return view('login');
})->name('login');

Route::get('/games/ptd/login.html', function () {
    return redirect('/login');
});

/*Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');*/

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('loginPost');


// Logout

Route::get('/logout', function () {
    return view('logout');
})->name('logout')->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logoutPost')->middleware('auth');


// API Keys

Route::get('/apiKeys', function () {
    return view('tokens');
})->middleware('auth');

Route::get('/apiKeys/{apiKeyId}', function () {
    return view('tokensDelete');
})->middleware('auth');


// PokeCenter Pages

Route::get('/games/ptd/account.html', function() {
    return view('account');
})->middleware('auth');

Route::get('/games/ptd/changeNickname.html', function() {
    return view('changeNickname');
})->middleware('auth');

Route::get('/games/ptd/changeAvatar.html', function() {
    return view('changeAvatar');
})->middleware('auth');

Route::get('/games/ptd/createTrade.html', function() {
    return view('createTrade');
})->middleware('auth');

Route::get('/games/ptd/myTrades.html', function() {
    return view('myTrades');
})->middleware('auth');

Route::get('/games/ptd/latestTrades.html', function() {
    return view('latestTrades');
})->middleware('auth');

// Mystery Gift
Route::get('/p/mystery-gift.html', function () {
    return view('mysteryGift');
});

Route::get('/games/ptd/dailyCode.html', function () {
    return view('mysteryGift');
});
Route::get('/games/ptd/dailyCode.php', function () {
    return redirect('/games/ptd/dailyCode.html');
});



// SWF Routes

Route::post('/php/newPoke8.php', [SWFController::class, 'post']);
