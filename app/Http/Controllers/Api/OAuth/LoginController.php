<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Socialite;
use App\Exceptions\Api\ValidationException;

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

        $acc = OAuthAccount::firstOrNew(
            [
                'account_id' => $soc_user->getId(),
                'oauth_provider_id' => OAuthProviders::{$provider}('id'),
            ],
            [
                'account_id' => $soc_user->getId(),
                'username' => $soc_user->getNickname(),
            ]
        );

        if (!is_null($acc) && !is_null($user = $acc->user)) {
            
            $tokens = $service->createTokens($user->getKey());

            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'user' => new UserResource($user),
                'billing' => $user->billing
            ], 200);

        }

        throw new ValidationException(trans('oauth.account.not_found'));


        if ($acc->exists) {
            
            if (is_null($user = $acc->user)) {
                
                $tokens = $service->createTokens($user->getKey());

                return repsonse()->json([
                    'message' => 'OAuth account does not binded to any user.',
                    'id' => $soc->getId(),
                    'username' => $soc_user->getNickname(),
                    'email' => $soc_user->getEmail(),
                ], 203);
            }
        }
    }
}
