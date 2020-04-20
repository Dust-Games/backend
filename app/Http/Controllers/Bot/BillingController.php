<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OAuthAccount;
use App\Models\Billing;
use App\Models\UnregisteredBilling;
use App\Rules\Uuid4;

class BillingController extends Controller
{
    public function show(Request $req)
    {
    	$data = $req->validate([
    		'id' => ['required'],
    		'platform' => ['required'],

    	]);
    	
    	$acc = OAuthAccount::where([
    		['account_id', $data['id']], 
    		['oauth_provider_id', $data['platform']]
    	])->first();

    	$billing = $acc->hasUser() ?
    		Billing::whereUser($acc->getUserKey())->first()
    		:
    		UnregisteredBilling::whereAccount($acc->getKey())->first();

    	return response()->json([
    		'billing' => $billing,		
    		'is_registered' => $billing instanceof Billing
    	], 200);	
    }
}
