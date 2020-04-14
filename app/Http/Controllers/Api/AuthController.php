<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\RefreshTokenRequest;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Services\UserService;

class AuthController extends Controller
{
    public function register(RegisterRequest $req)
    {
    	$data = $req->validated();

    	$user = User::create([
    		'id' =>  (string) Uuid::uuid4(),
    		'username' => $data['username'],
    		'email' => $data['email'],
    		'password' => Hash::make($data['password']),
    	]);

    	event(new Registered($user));

    	$tokens = (new UserService)->createTokens($user->getKey());

    	return response([
    		'access_token' => $tokens['access_token'],
    		'refresh_token' => $tokens['refresh_token'],
    	], 201);
    }
}
