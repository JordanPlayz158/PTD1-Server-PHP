<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\AchievementController;
use App\Http\Controllers\Web\SWFController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
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

Route::get('/forgot-password', function() {return view('resetPasswordForm');})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function () {return view('resetPassword');})->middleware('guest')->name('password.reset');

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

Route::get('/games/ptd/reset_password_form.php', function () {return redirect('/forgot-password');})->middleware('guest');

Route::get('/games/ptd/password.php', function () {return redirect('/forgot-password');})->middleware('guest');


// Verify Email

Route::get('/email/verify', function () {return view('auth.verify-email');})->middleware('auth')->name('verification.notice');

Route::get('/games/ptd/resendVerificationEmail.php', function () {return view('resendVerificationEmail');})->middleware('auth');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// id is the primary key of the user in the table
// hash is the sha1 hash of the email being verified
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, int $id) {
    $cacheString = 'email-change:' . $id;
    if(($email = Cache::get($cacheString)) !== null) {
        $user = User::whereId($id);

        if(!User::whereEmail($email)->exists()) {
            $user->update(['email' => $email]);
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        Cache::delete($cacheString);

    } else {
        $request->fulfill();
    }

    return redirect('/games/ptd/account.html');
})->middleware(['auth', 'signed'])->name('verification.verify');


// Root

Route::get('/', function () {return view('index');});
Route::get('/home', function () {return redirect('/');});


// Login

Route::get('/login', function () {
    if(Auth::check()) {
        return redirect('/games/ptd/account.html');
    }

    return view('login');
})->name('login');

/*Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');*/

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('loginPost');


// Logout

Route::get('/logout', function () {return view('logout');})->name('logout')->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logoutPost')->middleware('auth');


// API Keys

Route::get('/apiKeys', function () {return view('tokens');})->middleware('auth');

Route::get('/apiKeys/{apiKeyId}', function () {return view('tokensDelete');})->middleware('auth');


// PokeCenter Pages

Route::get('/games/ptd/trading.php', function () {return redirect('/games/ptd/trading.html');});

  // Non-original pages
  Route::get('/games/ptd/changeEmail.php', function () {return view('changeEmail');});


// Mystery Gift
Route::get('/games/ptd/dailyCode.php', function () {return redirect('/p/mystery-gift.html');});


// SWF Routes

Route::post('/php/newPoke8.php', [SWFController::class, 'post']);
Route::post('/php/newAchieve.php', [AchievementController::class, 'post']);
