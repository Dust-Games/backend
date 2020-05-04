<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exceptions\AuthenticationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerifyEmailController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {	
        if (! hash_equals((string) $request->query('id'), (string) $request->user()->getKey())) {
            throw new AuthenticationException;
        }

        if (! hash_equals((string) $request->query('hash'), sha1($request->user()->getEmailForVerification()))) {
            throw new AuthenticationException;
        }

        if ($request->user()->hasVerifiedEmail()) {

            throw new \App\Exceptions\ForbiddenException(trans('email.verify.already'));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
        	'message' => 'Email successfully verified.'
        ], 200);    	
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
            	'message' => 'Current user already has verified email.'
            ], 403);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
        	'message' => 'Verification notification resended.'
        ], 202);
    }
}
