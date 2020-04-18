<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Socialite;

class RegisterController extends Controller
{
    public function redirectToProvider($provider)
    {
    	return response()->json([
            'redirect_url' => Socialite::driver($provider)
                ->stateless()
                ->redirectUrl(config('services.'.$provider.'.register_redirect'))
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    public function handleProviderCallback($provider)
    {
    	$user = Socialite::driver($provider)
            ->stateless()
            ->redirectUrl(config('services.'.$provider.'.register_redirect'))
            ->user();

    	dd($user);
    }
}
