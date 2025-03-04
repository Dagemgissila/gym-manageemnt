<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(["error" => "These credentials do not match our records"], 400);
        }

        $user = auth('api')->user();

        if ($user->status === UserStatus::INACTIVE) {
            return response()->json(["error" => "Your Account is inactive. Please contact system admin"], 400);
        }

        if ($user->status === UserStatus::SUSPENDED) {
            return response()->json(["error" => "Your Account is suspended. Please contact system admin"], 400);
        }

        // Prepare token response data
        $response = $this->respondWithToken($token);

        // Merge with a success message and return as JSON
        return response()->json(array_merge(["message" => "Logged in successfully"], $response), 200);
    }

    protected function respondWithToken($token)
    {
        // Use auth('api')->user() to get the authenticated user
        $user = auth('api')->user();
        $user->load('roles.permissions');
        $role = $user->roles->first(); // Returns a single role object instead of an array
        $user->role = $role ? $role : null; // Single role name
        unset($user->roles);
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expire_in' => Carbon::now()->addDays(5)->toDateTimeString(),
            'user' => $user,
            // "permissions" => $user->getAllPermissions(),
        ];
    }

    public function refreshToken(Request $request)
    {
        $newToken = auth('api')->refresh();

        $response = $this->respondWithToken($newToken);

        return response()->json(array_merge(["message" => "Token fetched successfully'"], $response), 200);

    }


    public function user()
    {
        $user = auth('api')->user();
        $user->load('roles.permissions');
        $role = $user->roles->first(); // Returns a single role object instead of an array
        $user->role = $role ? $role : null; // Single role name
        unset($user->roles);

        session(['user' => $user]);

        return response()->json([
            "message" => "User retrieved successfully",
            "user" => $user
        ], 200);
    }


    public function logout()
    {
        $request = request();

        if (auth('api')->user() && $request->bearerToken() != '') {
            auth('api')->logout();
        }

        session()->flush();

        return response()->json([
            "message" => "Session closed successfull",
        ]);
    }
}
