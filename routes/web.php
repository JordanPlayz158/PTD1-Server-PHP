<?php

use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\AchievementController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\SWFController;
use App\Models\Save;
use App\Models\Trade;
use App\Models\User;
use App\View\Components\Profile;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function () {return view('resetPassword');})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
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

Route::post('/email/verification-notification', function (Request $request) {
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

Route::post('/profile/', function (Request $request) {
    $request->validate(['save' => 'required|numeric|integer']);

    $request->session()->put('save', $request->input('save'));

    return redirect(url()->previous());
})->middleware('auth');

Route::get('/games/ptd/trading.php', function () {return redirect('/games/ptd/trading.html');})->middleware('auth');

Route::get('/games/ptd/latestTrades.php', function () {return view('latestTrades', ['ids' => Trade::latest()->paginate(20, 'poke_id')]);})->middleware('auth');

Route::get('/games/ptd/makeAnOffer.php', function (Request $request) {
    $saves = Auth::user()->saves()->get()->collect();

    while (sizeof($saves) < 3) {
        $save = Save::factory()->make();

        $save->num = Profile::nextAvailableSaveNumber($saves);

        $saves->add((object) $save);
    }

    $saveNum = $request->session()->get('save', 0);

    $pokemonIds = $saves->get($saveNum)->pokemon()->get('id')->all();

    $ids = [];

    foreach($pokemonIds as $pokemonId) {
        $ids[] = $pokemonId->id;
    }

    return view('makeAnOffer', ['ids' => $ids]);
})->middleware('auth');

Route::post('/games/ptd/makeAnOffer.php', function (Request $request) {
    $request->validate(['id' => 'required|numeric|integer']);

    $offerIds = [];

    foreach($request->all() as $key => $value) {
        if(str_starts_with($key, 'pokemon')) {
            $offerIds[] = $value;
        }
    }

    $result = (new OfferController())->create($request->replace(['offerIds' => $offerIds]), $request->input('id'));

    if($result['success'] === true) {
        return redirect('/games/ptd/myOffers.html');
    } else {
        return redirect($request->fullUrlWithQuery(['error' => $result['error']]));
    }
})->middleware('auth');

Route::get('/games/ptd/searchTrades.php', function () {return view('searchTrades');})->middleware('auth');
Route::get('/games/ptd/latestTrades.php', function () {return view('latestTrades', ['ids' => Trade::paginate(20, 'poke_id')]);});

// Non-original pages
Route::get('/games/ptd/changeEmail.php', function (Request $request) {
    return view('changeEmail', ['email' => $request->user()->email]);
})->middleware('auth');

Route::get('/games/ptd/debug.php', function (Request $request) {
    return view('debug', ['user' => $request->user()]);
})->middleware('auth');
  // Non-original pages
  Route::get('/games/ptd/changeEmail.php', function () {return view('changeEmail');});

Route::get('/games/ptd/admin.php', function (Request $request) {
    return view('admin', ['user' => $request->user()]);
})->middleware('auth', 'admin');

Route::post('/games/ptd/admin.php', [AdminController::class, 'post'])->middleware('auth', 'admin');

// Mystery Gift
Route::get('/games/ptd/dailyCode.php', function () {return redirect('/p/mystery-gift.html');});


// SWF Routes

Route::post('/php/newPoke8.php', [SWFController::class, 'post']);
Route::post('/php/newAchieve.php', [AchievementController::class, 'post']);
