<?php

namespace App\Http\Middleware\Api;

use Illuminate\Support\Facades\App;
use Closure;

class Localization
{
    public const HEADER = 'x-localization';
    public const SUPPORTED_LANGS = ['ru', 'en'];
    public const DEFAULT_LANG = 'ru';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $req
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($req, Closure $next)
    {
        if (
            $req->hasHeader(static::HEADER) && 
            in_array($lang = $req->header(static::HEADER), static::SUPPORTED_LANGS)
        ) {
            
            App::setLocale($lang);
            return $next($req);
        }

        App::setLocale(static::DEFAULT_LANG);
        return $next($req);
    }
}
