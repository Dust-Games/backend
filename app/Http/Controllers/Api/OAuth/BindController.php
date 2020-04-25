<?php

namespace App\Http\Controllers\Api\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OAuthAccount;
use App\Helpers\OAuthProviders;
use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Socialite;
use App\Http\Requests\Api\OAuth\BindRequest;

class BindController extends Controller
{
    public function redirectToProvider($provider)
    {
        return response()->json([
            'redirect_url' => Socialite::driver($provider)
                ->stateless()
                ->redirectUrl(config("services.$provider.bind_redirect"))
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    public function handleProviderCallback($provider, Request $req)
    {
        #try {
        	$soc_user = Socialite::driver($provider)
        		->stateless()
        		->redirectUrl(config("services.$provider.bind_redirect"))
        		->user();
            
        #} catch (\Exception $e) {
        #    
        #    report($e);
        #    
        #    return response()->json([
        #        'error' => 'Error while fetching user.'
        #    ], 409);   
        #}
    	
    	$user_id = $req->input('user_for_bind');



    	if (User::whereKey($user_id)->exists()) {
    		
	     	$acc = OAuthAccount::firstOrNew(
	    		[
	    			'account_id' => $soc_user->getId(),
	    			'oauth_provider_id' => OAuthProviders::{$provider}('id'),
	    		],
	    		[
	    			'username' => $soc_user->getNickname(),
	    			'user_id' => $user_id,
	    		]
	    	);   	

    		if ($acc->exists) {
	    		
	    		if ($acc->hasUser()) {
	   				return response()->json([
	   					'message' => 'OAuth account already binded to another user.',
	   				], 409);
	    		}

	    		$acc->setAttribute('user_id', $user_id);
	    		$acc->save();

	    		return response()->json([
	    			'message' => 'OAuth account successfully binded to user.',
	    		], 200);
	    	}

	    	$acc->save();

	    	return response()->json([
	    		'message' => 'OAuth account successfully created and binded to user.'
	    	], 201);
    	}

	   	return response()->json([
	   		'message' => 'This user does not exists.',
	   	], 404);
    }
}
