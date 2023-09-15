<?php

use App\Http\Controllers\Api\GiveawayController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GameCornerController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\Web\AchievementController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\SWFController;
use App\Models\GameCornerPokemon;
use App\Models\Giveaway;
use App\Models\Pokemon;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::get('/forgot-password', function() {return view('resetPasswordForm');})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function () {return view('resetPassword');})->name('password.reset');

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
})->name('password.update');

Route::get('/games/ptd/reset_password_form.php', function () {return redirect()->route('password.request');});

Route::get('/games/ptd/password.php', function () {return redirect()->route('password.request');});


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
    $cacheString = 'email-change-verification-email:' . $id;
    if(($email = Cache::get($cacheString)) !== null) {
        $user = User::whereId($id);

        if (!User::whereEmail($email)->exists()) {
            $user->update(['email' => $email]);
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        Cache::delete($cacheString);
    }

    return redirect()->name('account');
})->middleware(['auth', 'signed'])->name('verification.verify');


// Root

Route::get('/', function () {return view('index');})->name('home');
Route::get('/home', function () {return redirect()->route('home');});


// Ruffle Flash Games

Route::get('/flash', function (Request $request) {
    $game = $request->input('game', 'PTD1.swf');

    if(str_contains($game, '/') || str_contains($game, "\\")) {
        return response('SWF Path contains "/" or "\", illegal character');
    }

    return view('flash', ['game' => $game]);
});


// Login

Route::get('/login', function () {
    if(Auth::check()) return redirect()->name('account');

    return view('login');
})->name('login');

/*Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');*/

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('loginPost')->middleware('throttle:60,1');


// Logout

Route::get('/logout', function () {return view('logout');})->name('logout')->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logoutPost')->middleware('auth');


// API Keys

Route::get('/apiKeys', function () {
    $tokens = Auth::user()->tokens()->where('name', '!=', 'loginApiKey')->get(['id', 'last_used_at', 'created_at', 'updated_at']);

    return view('tokens', ['tokens' => $tokens, 'newToken' => false]);
})->middleware('auth')->name('apiKeys');

Route::post('/apiKeys', function () {
    $newToken = Auth::user()->createToken('token')->plainTextToken;

    $tokens = Auth::user()->tokens()->where('name', '!=', 'loginApiKey')->get(['id', 'last_used_at', 'created_at', 'updated_at']);

    return view('tokens', ['tokens' => $tokens, 'newToken' => $newToken]);
})->middleware('auth');

Route::get('/apiKeys/{apiKeyId}', function (int $apiKeyId) {
    return view('tokensDelete', ['token' => Auth::user()->tokens()->get(['id', 'last_used_at', 'created_at', 'updated_at'])->where('id', $apiKeyId)->first()]);
})->middleware('auth');

Route::delete('/apiKeys/{apiKeyId}', function (Request $request, int $apiKeyId) {
    $tokenBuilder = Auth::user()->tokens()->where('id', '=', $apiKeyId);
    $token = $tokenBuilder->get('token')->first();

    if(hash('sha256', $request->bearerToken()) === $token) {
        return response()->setStatusCode(405, 'The login api key is used for the duration of the log in and deleted on log out or login expiration and required for all actions on the pokecenter Cannot delete login api key as it is being currently used to perform this action. If you wish to delete it, simply log out.');
    }

    $tokenBuilder->delete();

    return redirect()->route('apiKeys');
})->middleware('auth');


// PokeCenter Pages

Route::post('/profile/', function (Request $request) {
    $request->validate(['save' => 'required|numeric|integer']);

    $save = $request->input('save', 0);

    if($save < 0 || $save > 2)
        $save = 0;

    $request->session()->put('save', $save);

    return redirect(url()->previous());
})->middleware('auth');

Route::get('/games/ptd/trading.php', function () {return redirect()->route('createTrade');})->middleware('auth');

Route::get('/games/ptd/makeAnOffer.php', function (Request $request) {
    return view('makeAnOffer', ['id' => $request->input('id'), 'ids' => Auth::user()->selectedSave()->pokemon()->get('id')]);
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
        return redirect()->route('offers');
    } else {
        return redirect($request->fullUrlWithQuery(['error' => $result['error']]));
    }
})->middleware('auth');

Route::get('/games/ptd/account.php', function () {
    return view('account');
})->middleware('auth')->name('account');

Route::get('/games/ptd/searchTrades.php', function () {return view('searchTrades');})->middleware('auth');

Route::get('/offers/{id}/confirm', function(int $id) {
    if(Auth::user()->isParticipatingInOffer($id)) return response('The offer does not exist or you do not have permission to view the offer.');

    return view('offers.confirm', ['id' => $id]);
})->middleware('auth');


Route::get('/games/ptd/offers.php', function () {
    return view('offers', ['pokemon' => Auth::user()->selectedSave()->pokemon()->with('offers')]);
})->middleware('auth')->name('offers');

Route::get('/games/ptd/requests.php', function () {
    return view('requests', ['pokemon' => Auth::user()->selectedSave()->tradePokemon()->with('requests')]);
})->middleware('auth')->name('requests');

Route::get('/games/ptd/latestTrades.php', function () {
    return view('latestTrades', ['ids' => Trade::latest()->paginate(20, 'poke_id')]);
})->middleware('auth');

Route::get('/games/ptd/createTrade.php', function (Request $request) {
    $sorts = $request->input('sort');
    $orderBys = $request->input('orderBy');

    $pokemon = Auth::user()->selectedSave()->pokemon()->select('id');

    if($sorts !== null && $orderBys !== null) {
        for($i = 0; $i < sizeof($sorts) && $i < sizeof($orderBys); $i++) {
            $sortColumn = $sorts[$i] ?? 'id';
            $sortOrder = $orderBys[$i] ?? 'ASC';

            $pokemon->orderBy($sortColumn, $sortOrder);
        }
    }

    return view('createTrade', ['pokemon' => $pokemon, 'sorts' => $sorts ?? [], 'orderBys' => $orderBys ?? []]);
})->middleware('auth')->name('createTrade');

Route::get('/games/ptd/trade/{id}', function (int $id) {
    return view('trade', ['id' => $id]);
})->middleware('auth');

Route::post('/games/ptd/trade/{id}', function (int $id) {
    $pokemon = Auth::user()->findPokemon($id);
    if($pokemon) $pokemon->listTrade();

    return redirect()->route('myTrades');
})->middleware('auth');

Route::get('/games/ptd/recall/{id}', function (int $id) {
    return view('recall', ['id' => $id]);
})->middleware('auth');

Route::post('/games/ptd/recall/{id}', function (int $id) {
    $pokemon = Auth::user()->findPokemon($id);
    if($pokemon) $pokemon->recall();

    return redirect()->route('createTrade');
})->middleware('auth');

Route::get('/games/ptd/changePokemonNickname/{id}', function (int $id) {
    return view('changePokemonNickname', ['id' => $id]);
})->middleware('auth');

Route::post('/games/ptd/changePokemonNickname/{id}', function (int $id, Request $request) {
    $name = $request->input('name');

    $pokemon = Auth::user()->findPokemon($id);
    if($pokemon && !empty($name)) $pokemon->changeName($name);

    return redirect()->route('createTrade');
})->middleware('auth');

Route::get('/games/ptd/abandon/{id}', function (int $id) {
    return view('abandon', ['id' => $id]);
})->middleware('auth');

Route::post('/games/ptd/abandon/{id}', function (int $id) {
    $pokemon = Auth::user()->findPokemon($id);
    if($pokemon) $pokemon->delete();

    return redirect()->route('createTrade');
})->middleware('auth');

// Non-original pages
Route::get('/games/ptd/changeAvatar.php', function () {
    return view('changeAvatar', ['avatar' => Auth::user()->selectedSave()->avatar]);
})->middleware('auth');

Route::post('/games/ptd/changeAvatar.php', function (Request $request) {
    $save = Auth::user()->selectedSave();
    $save->avatar = $request->input('avatar', 'none');
    $save->save();

    return redirect(url()->previous());
})->middleware('auth');

Route::get('/games/ptd/changeNickname.php', function () {
    return view('changeNickname', ['name' => Auth::user()->name]);
})->middleware('auth');

Route::post('/games/ptd/changeNickname.php', function (Request $request) {
    $user = Auth::user();
    $user->name = $request->input('name', $user->email);
    $user->save();

    return redirect(url()->previous());
})->middleware('auth');

Route::get('/games/ptd/myTrades.php', function () {
    return view('myTrades', ['pokemon' => Auth::user()->selectedSave()->tradePokemon()->paginate(20)]);
})->middleware('auth')->name('myTrades');

Route::post('/games/ptd/myTrades.php', function (Request $request) {
    $user = Auth::user();
    $user->name = $request->input('name', $user->email);
    $user->save();

    return redirect(url()->previous());
})->middleware('auth');

Route::get('/games/ptd/changeEmail.php', function () {
    return view('changeEmail', ['email' => Auth::user()->email]);
})->middleware('auth');

Route::get('/games/ptd/debug.php', function () {
    return view('debug', ['user' => Auth::user()]);
})->middleware('auth');

Route::get('/games/ptd/admin.php', function (Request $request) {
    return view('admin', ['user' => $request->user()]);
})->middleware('auth', 'admin');

Route::post('/games/ptd/admin.php', [AdminController::class, 'post'])->middleware('auth', 'admin');

Route::get('/games/ptd/giveaways.php', function () {
    return view('giveaways', ['giveaways' => Giveaway::where('complete_at', '>', Carbon::now())->orderBy('complete_at')->paginate(20, 'id')]);
})->middleware('auth');

Route::get('/games/ptd/myGiveaways.php', function () {
    return view('myGiveaways', ['giveaways' => Giveaway::whereOwnerSaveId(Auth::user()->selectedSave()->id)->orderBy('created_at')->paginate(20, 'id')]);
})->middleware('auth');

Route::get('/games/ptd/completedGiveaways.php', function () {
    return view('giveaways', ['giveaways' => Giveaway::where('complete_at', '<', Carbon::now())->orderBy('complete_at', 'desc')->paginate(20, 'id')]);
})->middleware('auth');

Route::get('/giveaways/{id}/join', [GiveawayController::class, 'join'])->middleware('auth');
Route::get('/giveaways/{id}/leave', [GiveawayController::class, 'leave'])->middleware('auth');
Route::get('/giveaways/{id}/cancel', [GiveawayController::class, 'cancel'])->middleware('auth');
Route::get('/giveaways/{id}/participants', function(int $id) {
    $relations = Collection::make(['participants', 'participants.entrySave']);

    $giveaway = Giveaway::whereId($id)->with($relations->undot()->toArray())->get()->first();

    return view('giveawayParticipants', ['giveaway' => $giveaway, 'participants' => $giveaway->participants]);
})->middleware('auth');

Route::get('/giveaways/{id}/pokemon', function(int $id) {
    $relations = Collection::make(['pokemon']);

    $giveaway = Giveaway::whereId($id)->with($relations->undot()->toArray())->get()->first();

    return view('giveawayPokemon', ['giveaway' => $giveaway, 'pokemon' => $giveaway->pokemon]);
})->middleware('auth');


Route::get('/games/ptd/createGiveaway.php', function (Request $request) {
    return view('createGiveaway', ['ids' => Auth::user()->selectedSave()->pokemon()->get('id')]);
})->middleware('auth');

Route::post('/games/ptd/createGiveaway.php', [GiveawayController::class, 'create'])->middleware('auth');

Route::get('/offers/{id}/retract', function (int $id) {
    return view('retractOffer', ['id' => $id]);
})->middleware('auth');

Route::post('/offers/{id}/retract', function (int $id) {
    Auth::user()->deleteOffer($id);

    return redirect()->route('offers');
})->middleware('auth');

Route::get('/requests/{id}/accept', function (int $id) {
    return view('acceptRequest', ['id' => $id]);
})->middleware('auth');

Route::post('/requests/{id}/accept', function (int $id, Request $request) {
    if(Auth::user()->madeRequest($id))
        (new OfferController())->accept($request, $id);

    return redirect()->route('requests');
})->middleware('auth');

Route::get('/requests/{id}/deny', function (int $id) {
    return view('denyRequest', ['id' => $id]);
})->middleware('auth');

Route::post('/requests/{id}/deny', function (int $id) {
    Auth::user()->deleteRequest($id);

    return redirect()->route('requests');
})->middleware('auth');

// Daily Gift

Route::get('/games/ptd/dailyGift.php', function () {
    $user = Auth::user();
    if ($user->last_used_dg == null){
        $user->last_used_dg = Carbon::now('UTC')->subDays(2);
        $user->save();
    }
    $dateCheck = Carbon::parse($user->last_used_dg)->addDay()->isBefore(Carbon::now('UTC'));
    return view('dailyGift', ['save' => Auth::user()->selectedSave(), 'user' => Auth::user(), 'dateCheck' => $dateCheck]);
})->name('dailygift')->middleware('auth');

Route::get('/get-gift/{button}', [GiftController::class, 'GetGift'])->name('get-gift')->middleware('auth');

// Game Corner

Route::get('/games/ptd/gameCorner.php', function () {
    $user = Auth::user();
    if ($user->last_used_gc == null){
        $user->last_used_gc = Carbon::now('UTC')->subDays(2);
        $user->save();
    }
    $dateCheck = Carbon::parse($user->last_used_gc)->addDay()->isBefore(Carbon::now('UTC'));

    return view('gameCorner', ['save' => Auth::user()->selectedSave(), 'user' => Auth::user(), 'dateCheck' => $dateCheck, 'pokemons' => GameCornerPokemon::all()]);
})->name('gamecorner')->middleware('auth');

Route::get('/play-slots', [GameCornerController::class, 'playSlots'])->name('play-slots')->middleware('auth');

Route::get('/buy-pokemon/{id}', [GameCornerController::class, 'buyPokemon'])->name('buy-pokemon')->middleware('auth');

Route::get('/buy-shadow-pokemon', [GameCornerController::class, 'buyRandomShadowPokemon'])->name('buy-shadow-pokemon')->middleware('auth');

// SWF Routes

Route::post('/php/newPoke8.php', [SWFController::class, 'post']);
Route::post('/php/newAchieve.php', [AchievementController::class, 'post']);

Route::post('/php/newPoke6.php', [SWFController::class, 'post']);

Route::get('/php/ptd1_version.php', function (Request $request) {
    return $request->getSchemeAndHttpHost() . "/PTD1.swf";
});

// Routes that exist in SWF, may be disabled
// /php/trading.php
// /php/poke.php
