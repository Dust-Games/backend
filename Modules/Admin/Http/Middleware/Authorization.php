<?php

namespace App\Modules\Admin\Http\Middleware;

use Closure;
use App\Exceptions\ForbiddenException;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        throw new ForbiddenException;
    }
}
