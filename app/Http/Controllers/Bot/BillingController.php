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
use Illuminate\Support\Facades\DB;

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

        if (is_null($acc)) {
            return response()->json([
                'message' => 'Needed account does not exist.'
            ], 404);
        }

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

        $action = static::ACTION_CODES['set'];

        $billing = DB::transaction(function () use ($service, $t_service, $data, $action) {

            $acc = OAuthAccount::firstOrCreate([
                'account_id' => $data['account_id'], 
                'oauth_provider_id' => $data['platform']
            ]);

            $billing = $acc->wasRecentlyCreated ? 
                $service->createForAccount($acc)
                :
                $service->getByAccount($acc);

        	$billing->setTokens($data['dust_tokens_num']);

            $t_service->createForDustTokens(
                $data['dust_tokens_num'],
                $acc->getKey(),
                $action,
                false
            );

            return $billing;
        });

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

        $action = static::ACTION_CODES['add'];

        $billing = DB::transaction(function () use ($service, $t_service, $data, $action) {

            $acc = OAuthAccount::firstOrCreate([
                'account_id' => $data['account_id'], 
                'oauth_provider_id' => $data['platform']
            ]);

            $billing = $acc->wasRecentlyCreated ? 
                $service->createForAccount($acc)
                :
                $service->getByAccount($acc);


            $billing->addTokens($data['dust_tokens_num']);

            $t_service->createForDustTokens(
                $data['dust_tokens_num'],
                $acc->getKey(),
                $action,
                false
            );

            return $billing;
        });

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

        $action = static::ACTION_CODES['reduce'];

        $billing = DB::transaction(function () use ($service, $t_service, $data, $action) {

            $acc = OAuthAccount::firstOrCreate([
                'account_id' => $data['account_id'], 
                'oauth_provider_id' => $data['platform']
            ]);

            $billing = $acc->wasRecentlyCreated ? 
                $service->createForAccount($acc)
                :
                $service->getByAccount($acc);

            $billing->reduceTokens($data['dust_tokens_num']);

            $t_service->createForDustTokens(
                $data['dust_tokens_num'],
                $acc->getKey(),
                $action,
                false
            );

            return $billing;
        });
        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'is_registered' => $billing instanceof Billing
        ]);    	
    }
}
