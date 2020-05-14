<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\UnregisteredBilling;
use App\Modules\Admin\Http\Requests\IndexUnregBillingRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\UnregisteredBillingCollection;
use App\Http\Resources\UnregisteredBillingResource;
use App\Http\Requests\UpdateUnregBillingRequest;

class UnregisteredBillingController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * Callbacks for filtering UnregBills
     *
     * @return \Closure[]
     */
    private function filterQueryCallbacks()
    {
        return [
            'q' => function(Builder $query, $value) {
                $query->when(!$query->isJoined('oauth_account as account'), function ($q) {
                    $q->join('oauth_account as account','account.id', '=', 'oauth_account_id');
                })
                    ->where('account.username', 'LIKE', '%' . $value . '%')
                    ->orWhere('account_id', $value);
            },
            'order_by' => function(Builder $query, $value) {
                $query->when(!$query->isJoined('oauth_account as account'), function ($q) {
                    $q->join('oauth_account as account', 'account.id', '=', 'oauth_account_id');
                })
                    ->orderBy($value);
            },
            'order_desc' => function(Builder $query, $value) {
                $query->when(!$query->isJoined('oauth_account as account'), function ($q) {
                    $q->join('oauth_account as account', 'account.id', '=', 'oauth_account_id');
                })
                    ->orderByDesc($value);
            },
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexUnregBillingRequest $request)
    {
        $bills = UnregisteredBilling::with('account')
            ->filterQuery($request, $this->filterQueryCallbacks())
            ->paginate(static::PER_PAGE);
        return new UnregisteredBillingCollection($bills);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        $billing = UnregisteredBilling::find($key);

        return new UnregisteredBillingResource($billing);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUnregBillingRequest $request, $key)
    {
        $data = $request->validated();
        $billing = UnregisteredBilling::find($key);

        $billing->update($data);

        return new UnregisteredBillingResource($billing);
    }

    public function add(UpdateUnregBillingRequest $req, $key)
    {
        $data = $req->validated();
        $billing = UnregisteredBilling::find($key);

        $billing->increment('dust_coins_num', $data['dust_coins_num']);

        return new UnregisteredBillingResource($billing);
    }

    public function reduce(UpdateUnregBillingRequest $req, $key)
    {
        $data = $req->validated();
        $billing = UnregisteredBilling::find($key);

        $billing->decrement('dust_coins_num', $data['dust_coins_num']);

        return new UnregisteredBillingResource($billing);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }
}
