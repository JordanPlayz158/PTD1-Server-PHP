<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;

class TokensController extends Controller {
    public function get(): Collection {
        return Auth::user()->tokens()->where('name', '!=', 'loginApiKey')->get(['id', 'last_used_at', 'created_at', 'updated_at']);
    }

    public function create(): NewAccessToken {
        return Auth::user()->createToken('token');
    }
}
