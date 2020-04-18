<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
    	return Socialite::driver($provider)
            ->stateless()
            ->redirectUrl(config('services.'.$provider.'.login_redirect'))
            ->redirect();
    }

    public function handleProviderCallback($provider)
    {
    	$user = Socialite::driver($provider)
    		->stateless()
    		->redirectUrl(config('services.'.$provider.'.login_redirect'))
    		->user();

    	dd($user);
    }
}
