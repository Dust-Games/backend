<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use App\Services\AccountConverter;
use Socialite;

class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return response()->json([
            'redirect_url' => Socialite::driver($provider)
                ->stateless()
                ->redirectUrl(config('services.'.$provider.'.login_redirect'))
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    public function handleProviderCallback($provider, AccountConverter $converter)
    {
    	$user = Socialite::driver($provider)
    		->stateless()
    		->redirectUrl(config('services.'.$provider.'.login_redirect'))
    		->user();

        $account = $converter->{$provider}($user);
    	dd($account);
    }
}
