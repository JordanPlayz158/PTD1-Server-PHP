<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Laravel\Sanctum\NewAccessToken;

class TokensController extends Controller {
    public function get(): Collection {
        return Auth::user()->tokens()->get(['id', 'last_used_at', 'created_at', 'updated_at']);
    }

    public function create(): NewAccessToken {
        return Auth::user()->createToken('token');
    }
}
