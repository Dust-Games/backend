<?php


namespace App\Modules\Admin\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Admin\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::query()->orderBy('username')->get();
    }

    /**
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return User
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        if (isset($validated['sub_roles'])) {
            $user->subRoles()->sync($validated['sub_roles']);
        }
        $user->update($validated);
        $user->load('subRoles');
        return $user;
    }

}
