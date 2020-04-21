<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OAuthAccount;
use App\Models\Billing;
use App\Models\UnregisteredBilling;
use App\Rules\Uuid4;
use App\Services\BillingService;
use App\Services\TransactionService;
use App\Http\Requests\Bot\UpdateBillingRequest;

class BillingController extends Controller
{
    protected const ACTION_CODES = [
        'set' => 0,
        'add' => 1,
        'reduce' => 2,
    ];

    public function show(Request $req, BillingService $service)
    {
    	$data = $req->validate([
    		'account_id' => ['required'],
    		'platform' => ['required', 'numeric'],

    	]);
    	
    	$acc = OAuthAccount::where([
    		['account_id', $data['account_id']], 
    		['oauth_provider_id', $data['platform']]
    	])->first();

    	$billing = $service->getByAccount($acc);

    	return response()->json([
    		'billing' => $billing,		
    		'is_registered' => $billing instanceof Billing
    	], 200);	
    }

    public function setTokens(
        UpdateBillingRequest $req,
        BillingService $service,
        TransactionService $t_service
    )
    {
    	$data = $req->validated();

    	$acc = OAuthAccount::where([
    		['account_id', $data['account_id']], 
    		['oauth_provider_id', $data['platform']]
    	])->first();

		$billing = $service->getByAccount($acc);

    	$billing->setTokens($data['dust_tokens_num']);

        $t_service->createForDustTokens(
            $data['dust_tokens_num'],
            $acc->getKey(),
            static::ACTION_CODES['set'],
            false
        );

        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'is_registered' => $billing instanceof Billing
        ]);   
    }

    public function addTokens(
        UpdateBillingRequest $req, 
        BillingService $service,
        TransactionService $t_service
    )
    {
        $data = $req->validated();

        $acc = OAuthAccount::where([
            ['account_id', $data['account_id']], 
            ['oauth_provider_id', $data['platform']]
        ])->first();

        $billing = $service->getByAccount($acc);

        $count = $billing->addTokens($data['dust_tokens_num']);

        $t_service->createForDustTokens(
            $data['dust_tokens_num'],
            $acc->getKey(),
            static::ACTION_CODES['add'],
            false
        );

        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'is_registered' => $billing instanceof Billing
        ]);    	
    }

    public function reduceTokens(
        UpdateBillingRequest $req, 
        BillingService $service,
        TransactionService $t_service
    )
    {
        $data = $req->validated();

        $acc = OAuthAccount::where([
            ['account_id', $data['account_id']], 
            ['oauth_provider_id', $data['platform']]
        ])->first();

        $billing = $service->getByAccount($acc);

        $count = $billing->reduceTokens($data['dust_tokens_num']);

        $t_service->createForDustTokens(
            $data['dust_tokens_num'],
            $acc->getKey(),
            static::ACTION_CODES['reduce'],
            false
        );

        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'is_registered' => $billing instanceof Billing
        ]);    	
    }
}
