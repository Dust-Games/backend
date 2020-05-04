<?php

namespace App\Modules\Bot\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OAuthAccount;
use App\Models\Billing;
use App\Models\UnregisteredBilling;
use App\Rules\Uuid4;
use App\Services\BillingService;
use App\Services\TransactionService;
use App\Modules\Bot\Http\Requests\UpdateBillingRequest;
use App\Modules\Bot\Http\Requests\SetBillingRequest;
use App\Modules\Bot\Http\Requests\MultipleAddCoinsRequest;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotFoundException;
use App\Modules\Bot\Exceptions\TooFewDustCoinsException;
use App\Helpers\OAuthProviders;
use App\Rules\OAuthProvider;
use App\Services\OAuthAccountService;

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
    		'platform' => ['required', new OAuthProvider],

    	]);
    	
    	$acc = OAuthAccount::firstOrCreate([
    		'account_id' => $data['account_id'], 
    		'oauth_provider_id' => $data['platform'],
    	]);

        $billing = $acc->wasRecentlyCreated ? 
            $service->createForAccount($acc)
            :
            $service->getByAccount($acc);

    	return response()->json([
            'account_id' => $data['account_id'],
    		'billing' => $billing,
    		'is_registered' => $billing instanceof Billing
    	], 200);	
    }

    public function setCoins(
        SetBillingRequest $req,
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

        	$billing->setDustCoins($data['dust_coins_num']);

            $t_service->createForDustCoins(
                $data['dust_coins_num'],
                $acc->getKey(),
                $action,
                false
            );

            return $billing;
        });

        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'dust_coins_num' => $billing->getDustCoins(),
            'is_registered' => $billing instanceof Billing
        ]);   
    }

    public function addCoins(
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

            $billing->addDustCoins($data['dust_coins_num']);

            $t_service->createForDustCoins(
                $data['dust_coins_num'],
                $acc->getKey(),
                $action,
                false
            );

            return $billing;
        });

        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'dust_coins_num' => $billing->getDustCoins(),
            'is_registered' => $billing instanceof Billing,
        ]);    	
    }

    public function multipleAddCoins(
        MultipleAddCoinsRequest $req,
        OAuthAccountService $acc_service,
        BillingService $billing_service,
        TransactionService $t_service
    )
    {
        $data = $req->validated();
        $action = static::ACTION_CODES['add'];


        DB::transaction(function () use (
                $billing_service, 
                $t_service, 
                $acc_service,
                $data, 
                $action
            ) {

            $accs = $acc_service->getOrCreateMany($data['accounts'], $data['platform']);

            $billing_service->addDustCoinsToMany(
                $accs, 
                $data['dust_coins_num']
            );

            $t_service->createManyForDustCoins(
                $data['dust_coins_num'],
                $accs->where('user_id', null),
                $action,
                false
            );  

            $t_service->createManyForDustCoins(
                $data['dust_coins_num'],
                $accs->filter(function ($acc) { return $acc->getUserKey() !== null; }),
                $action,
                true
            );
        });

        return response()->json([   
            'message' => 'Users billings successfully updated.',
        ]);
    }

    public function reduceCoins(
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

            if ($billing->getDustCoins() < $data['dust_coins_num']) {
                throw new TooFewDustCoinsException($billing->getDustCoins());
            }

            $billing->reduceDustCoins($data['dust_coins_num']);

            $t_service->createForDustCoins(
                $data['dust_coins_num'],
                $acc->getKey(),
                $action,
                false
            );

            return $billing;
        });

        return response()->json([
            "message" => 'User`s billing successfully updated.',
            'dust_coins_num' => $billing->getDustCoins(),
            'is_registered' => $billing instanceof Billing
        ]);    	
    }
}
