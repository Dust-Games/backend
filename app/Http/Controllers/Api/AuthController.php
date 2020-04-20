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
use App\Exceptions\Api\InvalidRefreshTokenException;
use App\Services\UserService;
use App\Services\OAuthAccountService;
use App\Jobs\RemoveOldSessions;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $req, UserService $service)
    {
    	$data = $req->validated();

    	$user = User::create([
    		'username' => $data['username'],
    		'email' => $data['email'],
    		'password' => Hash::make($data['password']),
    	]);

        if ($oauth_id = $data['oauth_account']) {
            (new OAuthAccountService)->SetUser($oauth_id, $user->getKey());
        }

    	event(new Registered($user));

    	$tokens = $service->createTokens($user->getKey());

    	return response([
    		'access_token' => $tokens['access_token'],
    		'refresh_token' => $tokens['refresh_token'],
            'user' => new UserResource($user),
    	], 201);
    }

    public function login(LoginRequest $req, UserService $service)
    {
        $data = $req->validated();

        $user = User::where($this->username(), $data[$this->username()])->first();

        if ($this->checkPassword($data['password'], $user->getPassword())) {
            
            if ($user->hasTooManySessions()) {
                RemoveOldSessions::dispatch($user->getKey(), (string) now());
            }

            $tokens = $service->createTokens($user->getKey());

            return response([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'user' => new UserResource($user),
            ], 200);
        }

        return response([
            'message' => 'The given data was invalid.',
            'error' => __('auth.failed'),
        ], 422);
    }

    public function refreshToken(RefreshTokenRequest $req, UserService $service)
    {
        $refresh_token = $req->validated()['refresh_token'];

        $session = $service->getSessionByToken($refresh_token);

        $session->delete();

        if ($session->tokenExpired()) {
            return response([
                'message' => 'Refresh token is expired.'
            ], 401);
        }

        $tokens = $service->createTokens($session->user_id);

        return response([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
        ], 200);
    }

    public function logout(RefreshTokenRequest $req, UserService $service)
    {
        $refresh_token = $req->validated()['refresh_token'];

        $session = $service->getSessionByToken($refresh_token);

        $session->delete();

        return response([
            'message' => __('auth.logout'),
        ], 200);

    }

    protected function checkPassword(string $password, string  $hash_password)
    {
        return Hash::check($password, $hash_password);
    }

    protected function username()
    {
        return 'email';
    }
}
