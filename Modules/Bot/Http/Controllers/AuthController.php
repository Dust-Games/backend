<?php

namespace App\Modules\Bot\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Bot\Http\Requests\LoginRequest;
use App\Modules\Bot\Services\BotService;
use App\Http\Requests\RefreshTokenRequest;
use App\Exceptions\ValidationException;
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

    		if ($bot['id'] === $data['id'] && $bot['secret'] === $data['secret']) {
    			
    			return true;
    		}
    	}

    	return false;
    }
}
