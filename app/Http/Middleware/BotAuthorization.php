<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\JWT;
use Illuminate\Auth\AuthenticationException;

class BotAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($req, Closure $next)
    {
        $jwt = new JWT;

        $token = $jwt->parse($req->bearerToken());

        if ($token && $jwt->verify($token) && $jwt->validate($token)) {
            return $next($req);
        }

        throw new AuthenticationException;

    }
}
