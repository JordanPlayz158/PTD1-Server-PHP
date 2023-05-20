<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Response;
use Request;

class TokenController extends Controller {
    public function get($tokenId): array {
        return [Auth::user()->tokens()->get(['id', 'last_used_at', 'created_at', 'updated_at'])->where('id', $tokenId)->first()];
    }

    public function remove($tokenId): Response {
        $tokenBuilder = Auth::user()->tokens()->where('id', '=', $tokenId);
        $token = $tokenBuilder->get('token')->first();

        if(hash('sha256', Request::bearerToken()) === $token) {
            return response()->setStatusCode(405, 'The login api key is used for the duration of the log in and deleted on log out or login expiration and required for all actions on the pokecenter Cannot delete login api key as it is being currently used to perform this action. If you wish to delete it, simply log out.');
        }

        $tokenBuilder->delete();

        return response()->noContent();
    }
}
