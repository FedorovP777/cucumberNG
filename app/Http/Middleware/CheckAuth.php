<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {



        if (!$request->session()->has('key')) {

            return response('', 403);
        }

        if ($request->session()->get('key') !== config('app.key')) {

            return response('', 403);
        }
        return $next($request);
    }
}
