<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RolePermission;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{

    public function __construct()
    {
        // Apply middleware for permissions
        $this->middleware('permission:roles_view', ['only' => ['index']]);
        $this->middleware('permission:roles_create', ['only' => ['addRolePermission']]);
        $this->middleware('permission:roles_edit', ['only' => ['updateRolePermission']]);
        $this->middleware('permission:roles_delete', ['only' => ['index', 'show']]);

    }

    public function index(Request $request)
    {
        $perPage = $request->get('itemsPerPage', 1000000);

        $roles = Role::paginate($perPage);

        return RoleResource::collection($roles);
    }



    public function role(Request $request, $id)
    {
        $role = Role::find($id);
        return new RolePermission($role);
    }


    public function RoleWithPermission($id)  // Remove RoleResource $request
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

        return new RoleResource($role);
    }

    public function permissions()
    {
        return PermissionResource::collection(Permission::all());
    }

    public function addRolePermission(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
            'is_trainer' => 'sometimes|boolean'
        ]);

        $role = Role::create([
            'name' => $request->role_name,
            'description' => $request->description,
        ]);

        // Assign Permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role created successfully', 'role' => $role]);
    }

    public function updateRolePermission(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->update([
            'name' => $request->role_name,
            'description' => $request->description,
            'is_trainer' => $request->is_trainer
        ]);

        // Sync permissions
        $role->permissions()->sync($request->permissions);

        return response()->json(['message' => 'Role updated successfully']);
    }

}
