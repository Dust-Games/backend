<?php


namespace App\Modules\Bot\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AdminCurrencyAccountChange;
use App\Models\OAuthAccount;
use App\Modules\Bot\Http\Requests\CurrencyAccountShowRequest;
use App\Modules\Bot\Http\Requests\MultipleAddCoinsRequest;
use App\Modules\Bot\Http\Requests\SetBillingRequest;
use App\Modules\Bot\Http\Requests\UpdateBillingRequest;
use App\Services\CurrencyAccountService;
use App\Services\OAuthAccountService;
use Illuminate\Foundation\Http\FormRequest;

class AdminCurrencyAccountChangeController extends Controller
{
    /**
     * @param CurrencyAccountShowRequest $req
     * @param CurrencyAccountService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(CurrencyAccountShowRequest $req, CurrencyAccountService $service)
    {
        $data = $req->validated();

        $acc = OAuthAccount::query()->firstOrCreate([
            'account_id' => $data['account_id'],
            'oauth_provider_id' => $data['platform'],
        ]);

        $billing = $service->getByOauthAccount($acc);

        return response()->json([
            'account_id' => $data['account_id'],
            'billing' => $billing,
        ], 200);
    }

    /**
     * @param FormRequest $req
     * @param CurrencyAccountService $service
     * @param array $operation
     * @return \Illuminate\Http\JsonResponse
     */
    private function operationWithCoins($req, CurrencyAccountService $service, array $operation)
    {
        $data = $req->validated();

        $oauthAccount = OAuthAccount::query()->firstOrCreate([
            'account_id' => $data['account_id'],
            'oauth_provider_id' => $data['platform']
        ]);

        $currencyAccount = $service->getByOauthAccount($oauthAccount);

        $billing = AdminCurrencyAccountChange::{$operation['action']}($currencyAccount, $data['dust_coins_num']);

        return response()->json([
            "message" => $operation['message'],
            'dust_coins_num' => $billing,
        ]);
    }

    /**
     * @param SetBillingRequest $req
     * @param CurrencyAccountService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCoins(SetBillingRequest $req, CurrencyAccountService $service)
    {
        return $this->operationWithCoins($req, $service, [
            'action' => 'createSetBalance',
            'message' => 'User`s billing successfully updated.'
        ]);
    }

    /**
     * @param UpdateBillingRequest $req
     * @param CurrencyAccountService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCoins(UpdateBillingRequest $req, CurrencyAccountService $service)
    {
        return $this->operationWithCoins($req, $service, [
            'action' => 'createIncBalance',
            'message' => 'User`s billing successfully updated.'
        ]);
    }

    /**
     * @param UpdateBillingRequest $req
     * @param CurrencyAccountService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function reduceCoins(UpdateBillingRequest $req, CurrencyAccountService $service)
    {
        return $this->operationWithCoins($req, $service, [
            'action' => 'createDecBalance',
            'message' => 'User`s billing successfully updated.'
        ]);
    }

    /**
     * @param MultipleAddCoinsRequest $req
     * @param OAuthAccountService $acc_service
     * @param CurrencyAccountService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleAddCoins(
        MultipleAddCoinsRequest $req,
        OAuthAccountService $acc_service,
        CurrencyAccountService $service
    ) {
        $data = $req->validated();
        $oauthAccountIds = $acc_service->getOrCreate($data['accounts'], $data['platform'])
            ->pluck('account_id')
            ->all();
        $currencyAccounts = $service->getByOauthAccounts($oauthAccountIds)->all();;
        AdminCurrencyAccountChange::createIncBalanceToSeveral($currencyAccounts, $data['dust_coins_num']);

        return response()->json([
            'message' => 'Users billings successfully updated.',
        ]);
    }
}
