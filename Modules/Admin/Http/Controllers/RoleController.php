<?php


namespace App\Modules\Admin\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User\Role;
use App\Modules\Admin\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Role::query()->orderBy('name')->get();
    }

    /**
     * @param Role $role
     * @return Role
     */
    public function show(Role $role)
    {
        return $role;
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return Role
     */
    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->validated());
        return $role;
    }
}
