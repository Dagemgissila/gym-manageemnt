<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        // Apply middleware for permissions
        $this->middleware('permission:users_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:users_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->filter($request->all());
        $users = $query->paginateResults($request->all());

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Hash the password before creating the user
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        $user = User::create($validated);

        // Assign role if provided
        if (!empty($validated['role'])) {
            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Hash the password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Update user
        $user->update($validated);

        // Update role if provided
        if (!empty($validated['role'])) {
            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->syncRoles([$role]); // Remove previous roles and assign the new one
            }
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
