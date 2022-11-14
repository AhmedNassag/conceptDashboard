<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Traits\Message;
use Closure;
use Illuminate\Http\Request;

class CloseApp
{
    use Message;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $close = Setting::find(1)->close;

        if($close == 0){
            return $next($request);
        }

        return response()->json(['success'=>false,'message'=>trans('message.close app'),'close'=>true],403);
    }
}
