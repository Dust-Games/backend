<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
    	return Socialite::driver('steam')->stateless()->redirect();

    	dd(Socialite::driver('steam')->stateless()->redirect());
    }

    public function handleProviderCallback($provider)
    {
    	$user = Socialite::driver($provider)->user();

    	info([$user]);
    	dd($user);
    }

}
