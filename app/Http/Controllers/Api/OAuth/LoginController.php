<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Socialite;
use App\Exceptions\Api\ValidationException;
use App\Helpers\OAuthProviders;

class LoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return response()->json([
            'redirect_url' => Socialite::driver($provider)
                ->stateless()
                ->redirectUrl(config("services.$provider.login_redirect"))
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    public function handleProviderCallback($provider, UserService $service)
    {
        #try {
        	$soc_user = Socialite::driver($provider)
        		->stateless()
        		->redirectUrl(config('services.'.$provider.'.login_redirect'))
        		->user();
            
        #} catch (\Exception $e) {
        #
        #    report($e);
        #    
        #    return response()->json([
        #        'error' => 'Error while fetching user.'
        #    ], 409);   
        #}

        $acc = OAuthAccount::firstOrCreate(
            [
                'account_id' => $soc_user->getId(),
                'oauth_provider_id' => OAuthProviders::{$provider}('id'),
            ],
            [
                'account_id' => $soc_user->getId(),
                'username' => $soc_user->getNickname(),
            ]
        );

        if ($acc->wasRecentlyCreated) {

            return repsonse()->json([
                'message' => 'OAuth account successfully created.',
                'id' => $soc_user->getId(),
                'username' => $soc_user->getNickname(),
                'email' => $soc_user->getEmail(),
            ], 203);       
        }

        if (is_null($user = $acc->user)) {
            
            return repsonse()->json([
                'message' => 'OAuth account does not binded to any user.',
                'id' => $soc_user->getId(),
                'username' => $soc_user->getNickname(),
                'email' => $soc_user->getEmail(),
            ], 201);
        }

        $tokens = $service->createTokens($user->getKey());

        return repsonse()->json([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'user' => $user,
            'billing' => $user->billing,
        ], 200);
    }

}
