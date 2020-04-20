<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use App\Services\AccountConverter;
use App\Services\UserService;
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

    public function handleProviderCallback(
        $provider, 
        AccountConverter $converter,
        UserService $service
    )
    {
    	$user = Socialite::driver($provider)
    		->stateless()
    		->redirectUrl(config('services.'.$provider.'.login_redirect'))
    		->user();

        if ($provider === 'battlenet') {
            dd($user);
        }

        $acc = $converter->{$provider}($user);

        $db_ac = OAuthAccount::where('account_id', $acc->account_id)->first();

        if (!is_null($db_ac) && !is_null($user = $db_ac->user)) {
            
            $tokens = $service->createTokens($user->getKey());

            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'user' => new UserResource($user),
            ], 200);

        } else {            
            return response()->response([
                'message' => 'OAuth account does not exist.'
            ], 422);
        }
        
    }
}
