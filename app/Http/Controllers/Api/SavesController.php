<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Laravel\Sanctum\NewAccessToken;

class SavesController extends Controller {
    public function get(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return Auth::user()->saves()->get();
    }
}
