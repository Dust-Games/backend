<?php


namespace App\Modules\Admin\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User\SubRole;
use App\Modules\Admin\Http\Requests\SubRoleRequest;

class SubRoleController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return SubRole::query()->orderBy('name')->get();
    }

    /**
     * @param SubRole $subRole
     * @return SubRole
     */

    public function show(SubRole $subRole)
    {
        return $subRole;
    }


    public function update(SubRoleRequest $request, SubRole $subRole)
    {
        $subRole->update($request->validated());
        return $subRole;
    }
}
