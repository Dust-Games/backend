<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\UnregisteredBilling;
use Illuminate\Http\Request;
use App\Http\Resources\UnregisteredBillingCollection;
use App\Http\Resources\UnregisteredBillingResource;
use App\Http\Requests\UpdateUnregBillingRequest;

class UnregisteredBillingController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = UnregisteredBilling::with('account')->paginate(static::PER_PAGE);

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
