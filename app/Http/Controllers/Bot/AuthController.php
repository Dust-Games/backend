<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Bot\LoginRequest;
use App\Services\BotService;
use App\Http\Requests\Api\RefreshTokenRequest;
use App\Services\JWT;

class AuthController extends Controller
{
    public function login(LoginRequest $req, BotService $service)
    {
    	$data = $req->validated();

    	if ($this->validate($data)) {
    		
    		$tokens = $service->createTokens($data['id']);

    		return [
    			'access_token' => $tokens['access_token'],
    			'refresh_token' => $tokens['refresh_token'],
    		];
    	}
    }

    public function refreshToken(RefreshTokenRequest $req, JWT $jwt)
    {
    	$refresh_token = $req->validated()['refresh_token'];

    	$obj_token = $jwt->parse($refresh_token);

    	if ($jwt->verify($obj_token) && $jwt->validate($obj_token)) {
    		
    		$service = new BotService;

    		$tokens = $service->createTokens($jwt->getOwnerKey($obj_token));

	    	return response()->json([
	    		'access_token' => $tokens['access_token'],
	    		'refresh_token' => $tokens['refresh_token'],
	    	]);
    	}

    	return response()->json([
    		'message' => 'Invalid refresh token.',
    	], 403);
    }

    protected function validate(array $data)
    {
    	$config = config('bots');

    	if (array_key_exists($data['platform'], $config)) {
    		
    		$bot = $config[$data['platform']];

    		if ($bot['id'] === $data['id'] && $bot['secret'] === $bot['secret']) {
    			
    			return true;
    		}
    	}

    	return false;
    }
}
