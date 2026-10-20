<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            $request->session()->put('intended_url', $request->fullUrl());

            return redirect()->route('admin.login')->with('status', 'Please sign in to continue.');
        }

        return $next($request);
    }
}
