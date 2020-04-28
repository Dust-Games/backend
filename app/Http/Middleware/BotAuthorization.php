<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\JWT;
use Exception;
use App\Exceptions\Api\AuthenticationException;

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

        if ($bearer = $req->bearerToken()) {

            try {
                $token = $jwt->parse($bearer);
                
            } catch (Exception $e) {
                throw new AuthenticationException;
            }

            if ($token && $jwt->verify($token) && $jwt->validate($token)) {
                return $next($req);
            }
        }

        throw new AuthenticationException;

    }
}
