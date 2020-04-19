<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use App\Services\AccountConverter;
use Socialite;
use App\Services\UserService;

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

    public function handleProviderCallback(
        $provider,
        AccountConverter $converter,
        UserService $service
    )
    {
    	$user = Socialite::driver($provider)
    		->stateless()
    		->redirectUrl(config('services.'.$provider.'.register_redirect'))
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
        }

        $saved = $acc->save();

        if ($saved) {
            return response()->json([
                'message' => 'OAuth account successfully created.',
                'id' => $acc->getKey(),
            ], 201);
        } else {
            return response()->json([
                'message' => 'Error >:('
            ], 500);
        }
        
    }
}