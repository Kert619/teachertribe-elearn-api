<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = Permission::all();

        return PermissionResource::collection($permission);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $request->validated($request->all());

        $permission = Permission::create([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return $this->success($permission, 'New permission has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $request->validated($request->all());

        $permission->update([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return $this->success($permission, 'Permission has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->success(null, 'Permission has been deleted', 204);
    }
}
