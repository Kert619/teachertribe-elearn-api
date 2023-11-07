<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with(['lessons', 'roles', 'roles.permissions', 'user', 'users'])->whereBelongsTo($request->user())->get();

        if($request->user()->isAdmin() || $request->user()->isSuperAdmin()){
            $users = User::with(['lessons', 'roles', 'roles.permissions', 'user', 'users'])->get();
        }

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'user_id' => $request->user()->id,
        ]);

         $role =  Role::where('code', $request->role)->firstOrFail();

         $user->assignRole($role);

         $user->load(['roles', 'roles.permissions']);
        return $this->success(new UserResource($user), 'New user has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['lessons', 'roles', 'roles.permissions']);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $request->validated($request->all());

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $this->success($user, 'User has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        $user->roles()->detach();
        return $this->success(null,'User has been deleted', 204);
    }
}
