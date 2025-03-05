<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;
use URL;

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
        $validated['password'] = Hash::make($validated['password'] ?? "12345678");

        // Handle base64-encoded profile picture
        if (!empty($validated['profile_picture'])) {
            $imageData = $this->saveBase64Image($validated['profile_picture']);
            $validated['profile_picture'] = $imageData['profile_picture'];
        }

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

        if (!empty($validated['profile_picture'])) {

            // Remove the '/storage/' part to get the relative file path
            $relativePath = str_replace('/storage/', '', $user->profile_picture);
            Storage::disk('public')->delete($relativePath);


            $imageData = $this->saveBase64Image($validated['profile_picture']);
            $validated['profile_picture'] = $imageData['profile_picture'];
        }

        $user->update($validated);

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




    private function saveBase64Image($base64Image)
    {
        // Extract the image type and data from the base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $extension = $matches[1]; // Get the image extension (e.g., png, jpeg)
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1); // Remove the base64 prefix
            $imageData = base64_decode($imageData); // Decode the base64 data

            if (!$imageData) {
                throw new \Exception("Invalid Base64 image data");
            }

            // Generate a unique filename and save the image
            $imageName = uniqid() . '.' . $extension;
            $imagePath = 'profile_pictures/' . $imageName;

            Storage::disk('public')->put($imagePath, $imageData);

            // Return the relative path and URL of the saved image
            return [
                'path' => $imagePath,
                'profile_picture' => Storage::url($imagePath),
            ];
        } else {
            throw new \Exception("Invalid image format. Expected base64-encoded image.");
        }
    }
}
