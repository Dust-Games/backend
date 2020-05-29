<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminUsdTokenCahnge;
use App\Modules\Admin\Http\Requests\AdminUsdTokenChangeStoreRequest;
use App\Modules\Admin\Http\Requests\AdminUsdTokenChangeUpdateRequest;
use App\Modules\Admin\Http\Requests\AdminUsdTokenDestroyRequest;
use App\Services\UsdTokenService;
use Error;

class AdminUsdTokenChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        throw new Error('Not implemented');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminUsdTokenChangeStoreRequest $request
     * @param UsdTokenService $service
     * @return array
     */
    public function store(AdminUsdTokenChangeStoreRequest $request, UsdTokenService $service)
    {
        return $service->adminChangeStore($request->input(), $request->user()->id);
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show()
    {
        throw new Error('Not implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUsdTokenChangeUpdateRequest $request
     * @param AdminUsdTokenCahnge $change
     * @param UsdTokenService $service
     * @return array|\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function update(
        AdminUsdTokenChangeUpdateRequest $request, AdminUsdTokenCahnge $change, UsdTokenService $service
    )
    {
        return $service->adminChangeUpdate($request->validated(), $change, $request->user()->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdminUsdTokenCahnge $change
     * @param AdminUsdTokenDestroyRequest $request
     * @param UsdTokenService $service
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function destroy(AdminUsdTokenCahnge $change, AdminUsdTokenDestroyRequest $request, UsdTokenService $service)
    {
        return $service->adminChangeDestroy($change);
    }
}
