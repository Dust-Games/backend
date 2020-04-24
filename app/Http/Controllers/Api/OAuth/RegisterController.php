<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use Socialite;
use App\Services\UserService;
use App\Models\OAuthProvider;

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
        #try {
        	$soc_user = Socialite::driver($provider)
        		->stateless()
        		->redirectUrl(config('services.'.$provider.'.register_redirect'))
        		->user();
            
        #} catch (\Exception $e) {
        #    
        #    return response()->json([
        #        'error' => 'Error while fetching user.'
        #    ], 409);   
        #}

        $db_ac = OAuthAccount::where('account_id', $soc_user->getId())->first();

        # Create new oauth account if it does not exists
        if (is_null($db_ac)) {
            
            $new_ac = OAuthAccount::create([
                'account_id' => $soc_user->getId(),
                'username' => $soc_user->getNickname(),
                'oauth_provider_id' => OAuthProvider::{$provider}('id'),
            ]);

            if ($new_ac) {
                return response()->json([
                    'message' => 'OAuth account successfully created.',
                    'id' => $soc_user->getId(),
                    'username' => $soc_user->getNickname(),
                    'email' => $soc_user->getEmail(),
                ], 201);

            } else {
                return response()->json([
                    'message' => 'Error >:('
                ], 500);
            }

        # If account exists but user not binded, just send account data for bind it to user in registration
        } elseif (is_null($user = $db_ac->user)) {

            return response()->json([
                'message' => 'OAuth account already exists.',
                'id' => $soc_user->getId(),
                'username' => $soc_user->getNickname(),
                'email' => $soc_user->getEmail(),
            ], 200);
        }
            
        # If account exists and has user, just send tokens and user like in login
        $tokens = (new UserService)->createTokens($user->getKey());

        return response()->json([
            'message' => 'User with this OAuth account already exists.',
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'user' => new UserResource($user),
        ], 200);
    }
}
