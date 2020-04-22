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
        AccountConverter $converter
    )
    {
        try {
        	$user = Socialite::driver($provider)
        		->stateless()
        		->redirectUrl(config('services.'.$provider.'.register_redirect'))
        		->user();
            
        } catch (\Exception $e) {
            
            return response()->json([
                'error' => 'Error while fetching user.'
            ], 409);   
        }

        $acc = $converter->{$provider}($user);

        $db_ac = OAuthAccount::where('account_id', $acc->account_id)->first();


        if (is_null($db_ac)) {
            
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

        } elseif (is_null($user = $db_ac->user)) {

            return response()->json([
                'message' => 'OAuth account already exists.',
                'id' => $db_ac->getKey(),
            ], 201);
        }
            
        $tokens = (new UserService)->createTokens($user->getKey());

        return response()->json([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'user' => new UserResource($user),
        ], 200);
    }
}
