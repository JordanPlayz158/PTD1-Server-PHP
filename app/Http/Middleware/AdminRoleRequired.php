<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class AdminRoleRequired {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if($request->user()->role()->value !== Role::ADMIN()->value) {
            return redirect('/games/ptd/login.html');
        }

        return $next($request);
    }
}
