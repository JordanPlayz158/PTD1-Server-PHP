<?php

namespace App\Http\Middleware;

use Closure;

class SessionAsAuth {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next) {
        if(!$request->headers->has('Authorization')) {
            $request->headers->add(['Authorization' => "Bearer {$request->session()->get('apiKey')}"]);
        }

        return $next($request);
    }
}
