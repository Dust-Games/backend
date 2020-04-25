<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Bot\LoginRequest;
use App\Services\BotService;
use App\Http\Requests\Api\RefreshTokenRequest;
use App\Exceptions\Api\ValidationException;
use App\Services\JWT;

class AuthController extends Controller
{
    public function login(LoginRequest $req, BotService $service)
    {
    	$data = $req->validated();

    	if ($this->validateBot($data)) {
    		
    		$token = $service->createAccessToken($data['id']);

    		return [
    			'access_token' => (string) $token,
    		];
    	} 

        throw new ValidationException('Invalid bot credentials.');
    }

    protected function validateBot(array $data)
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
