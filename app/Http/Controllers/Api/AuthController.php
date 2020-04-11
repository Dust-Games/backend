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
use App\Jobs\RemoveOldTokens;
use App\Models\User;

class AuthController extends Controller
{


    public function register(RegisterRequest $req)
    {
        $data = $req->validated();

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        $resp = $this->requestAccessToken(
            $data[$this->username()],
            $data['password'],
        );

        return response([
            'access_token' => $resp['access_token'],
            'refresh_token' => $resp['refresh_token'],
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $req)
    {
        $data = $req->validated();

        $user = User::where($this->username(), $data[$this->username()])->first();

        if ($this->checkPassword($data['password'], $user->getPassword())) {
            
            if ($user->hasTooManyTokens()) {
                RemoveOldTokens::dispatch($user->getKey(), (string) now());
            }

            $resp = $this->requestAccessToken(
                $data[$this->username()],
                $data['password']
            );

            return response([
                'access_token' => $resp['access_token'],
                'refresh_token' => $resp['refresh_token'],
                'user' => new UserResource($user),
            ], 200);
        }

        return response([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => [__('validation.password')],
            ],
        ], 422);
    }

    public function refreshToken(RefreshTokenRequest $req)
    {
        $refresh_token = $req->validated()['refresh_token'];

        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
        ];

        $resp = $this->makePostRequest($params);

        
        if ($resp->ok()) {
            
            $fields = $resp->json();

            return response(
                [
                    'access_token' => $fields['access_token'],
                    'refresh_token' => $fields['refresh_token'],
                ], 
                $resp->status()
            );

        } else {
            
            return response([
                $resp->json()
            ], $resp->status());
        }
    }

    public function logout(Request $req)
    {
        if ($req->user()->token()) {
            $revoked = $req->user()->token()->revoke();

            return response([
                'message' => __('auth.logout'),
            ], 200);
        }

        return response(['message' => 'User is not authenticated.'], 401);
    }

    public function username()
    {
        return 'email';
    }

    protected function requestAccessToken($username, $password)
    {
        $params = [
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
        ];                

        return $this->makePostRequest($params);
    }

    protected function makePostRequest(array $params)
    {
        $all_params = array_merge(
            [
                'client_id' => config('services.passport.frontend_client_id'),
                'client_secret' => config('services.passport.frontend_client_secret'),
                'scope' => '*',
            ],
            $params
        );

        $response = Http::post(
            config('app.url') . '/oauth/token',
            $all_params,
        );
    
        return $response;
    }

    protected function checkPassword(string $password, string $hash)
    {
        return Hash::check($password, $hash);
    }
}