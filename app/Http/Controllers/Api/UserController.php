<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Resources\SessionCollection;

class UserController extends Controller
{
    public function me()
    {
        return new UserResource(Auth::user());
    }

    public function sessions()
    {
    	return new SessionCollection(Auth::user()->sessions);
    }

    public function billing()
    {
    	return Auth::user()->billing;
    }

    public function accounts()
    {
        return Auth::user()->accounts;
    }
}
