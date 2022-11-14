<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangeLang
{

    public function handle(Request $request, Closure $next)
    {
        app()->setLocale('ar');

        $lang = $request->header('lang');

        if(isset($lang) && $request->header('lang') != 'ar')
            app()->setLocale('en');

        return $next($request);
    }
}
