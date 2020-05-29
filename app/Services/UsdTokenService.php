<?php


namespace App\Services;


use App\Models\AdminUsdTokenCahnge;
use Illuminate\Support\Facades\DB;

class UsdTokenService
{
    /**
     * @param array $request
     * @param string $adminId
     * @return array
     */
    public function adminChangeStore(array $request, string $adminId)
    {
        $adminUsdTokenChange = null;
        DB::transaction(function() use ($request, $adminId, &$adminUsdTokenChange) {
            $adminUsdTokenChange = AdminUsdTokenCahnge::query()->create([
                'user_id' => $adminId,
                'amount' => $request['amount'],
            ]);
            $adminUsdTokenChange->usdTokenTransaction()->create([
                'billing_id' => $request['billing']->id,
                'debt' => $request['debt'],
            ]);
            // $adminUsdTokenChange->load('usdTokenTransaction');
            $request['billing']->usd_tokens_num = $request['debt']
                ? $request['billing']->usd_tokens_num - $request['amount']
                : $request['billing']->usd_tokens_num + $request['amount'];
            $request['billing']->save();

        });
        return [
            'billing' => $request['billing'],
            'admin_usd_token_change' => $adminUsdTokenChange,
        ];
    }

    /**
     * @param array $request
     * @param AdminUsdTokenCahnge $id
     * @param string $adminId
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function adminChangeUpdate(array $request, AdminUsdTokenCahnge $change, string $adminId)
    {
        DB::transaction(function() use ($request, $adminId, $change) {
            $previousAmount = $change->amount;
            $previousDebt = $change->usdTokenTransaction->debt;
            $change->update([
                'amount' => $request['amount'],
                'user_id' => $adminId,
            ]);
            $change->usdTokenTransaction->update(['debt' => $request['debt']]);
            $change->usdTokenTransaction->billing->usd_tokens_num = $previousDebt
                ? $change->usdTokenTransaction->billing->usd_tokens_num + $previousAmount
                : $change->usdTokenTransaction->billing->usd_tokens_num - $previousAmount;
            $change->usdTokenTransaction->billing->usd_tokens_num = $request['debt']
                ? $change->usdTokenTransaction->billing->usd_tokens_num - $request['amount']
                : $change->usdTokenTransaction->billing->usd_tokens_num + $request['amount'];
            $change->usdTokenTransaction->billing->save();
        });
        return [
            'billing' => $change->usdTokenTransaction->billing,
            'admin_usd_token_change' => $change->attributesToArray(),
        ];
    }

    /**
     * @param AdminUsdTokenCahnge $change
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function adminChangeDestroy(AdminUsdTokenCahnge $change)
    {
        $billing = $change->usdTokenTransaction->billing;
        DB::transaction(function() use ($change, $billing) {
            $previousAmount = $change->amount;
            $previousDebt = $change->usdTokenTransaction->debt;
            $billing->usd_tokens_num = $previousDebt
                ? $change->usdTokenTransaction->billing->usd_tokens_num + $previousAmount
                : $change->usdTokenTransaction->billing->usd_tokens_num - $previousAmount;
            $billing->save();
            $change->usdTokenTransaction->delete();
            $change->delete();
        });
        return compact('billing');
    }
}
