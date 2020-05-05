<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OAuthAccountCollection;
use App\Http\Resources\OAuthAccountResource;
use App\Models\OAuthAccount;

class OAuthAccountController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accs = OAuthAccount::paginate(static::PER_PAGE);

        return new OAuthAccountCollection($accs);
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
     * @param  \App\Models\OAuthAccount  $oAuthAccount
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        $acc = OAuthAccount::find($key);

        return new OAuthAccountResource($acc);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OAuthAccount  $oAuthAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OAuthAccount $oAuthAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OAuthAccount  $oAuthAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(OAuthAccount $oAuthAccount)
    {
        //
    }
}
