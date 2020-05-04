<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Resources\SessionCollection;

class UserController extends Controller
{
    public function me()
    {
        return response()->json([
            'user' => new UserResource(Auth::user()),
            'billing' => Auth::user()->billing,
        ]);
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
